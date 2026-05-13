<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService
    ) {}

    private function getShopper()
    {
        if (Auth::guard('customer')->check()) {
            return Auth::guard('customer')->user();
        }
        if (Auth::guard('web')->check() && Auth::guard('web')->user()->isSales()) {
            return Auth::guard('web')->user();
        }

        return null;
    }

    public function index(Request $request)
    {
        $shopper = $this->getShopper();

        // Pass Customer to resolve if it is a Customer, otherwise null (Sales uses session cart)
        $resolveCustomer = ($shopper instanceof Customer) ? $shopper : null;

        $resolved = $this->cartService->resolve($request, $resolveCustomer);
        $cart = $resolved['cart']->load(['items.product.brand']);

        $summary = $this->buildSummary($cart->items);

        // View expects 'customer' variable.
        // If Sales is logged in, we might need to pass something else or handle it in view.
        // For now, we pass $shopper as customer if it's a Customer, else null?
        // But if we pass null, the view might show "Login".
        // The Sales user IS logged in.
        // We'll pass 'customer' => $shopper. The view should handle User object if it just displays name.
        // If view checks specific Customer fields, it might break.
        // But usually header just shows name.

        $isSales = ($shopper instanceof User && $shopper->isSales());
        $myCustomers = $isSales ? Customer::where('sales_id', $shopper->id)->orderBy('full_name')->get() : collect();
        $addresses = collect();
        $activeAddressId = null;

        if ($shopper instanceof Customer) {
            $addresses = CustomerAddress::query()
                ->where('customer_id', $shopper->id)
                ->orderByDesc('is_active')
                ->orderByDesc('id')
                ->get();
            $activeAddressId = $addresses->firstWhere('is_active', true)?->id;
        }

        // Pass 'customer' as shopper (User or Customer) so the view detects logged in state
        return response()
            ->view('guest.cart.index', [
                'cart' => $cart,
                'summary' => $summary,
                'customer' => $shopper,
                'is_sales' => $isSales,
                'my_customers' => $myCustomers,
                'addresses' => $addresses,
                'active_address_id' => $activeAddressId,
            ])
            ->cookie($resolved['cookie']);
    }

    public function summary(Request $request)
    {
        $shopper = $this->getShopper();
        $resolveCustomer = ($shopper instanceof Customer) ? $shopper : null;

        $resolved = $this->cartService->resolve($request, $resolveCustomer);
        $cart = $resolved['cart']->load(['items.product']);

        $summary = $this->buildSummary($cart->items);

        return response()
            ->json(['summary' => $summary])
            ->cookie($resolved['cookie']);
    }

    public function addItem(Request $request)
    {
        Log::info('Adding item to cart', $request->all());

        try {
            $validated = $request->validate([
                'product_id' => ['required', 'integer', 'exists:products,id'],
                'quantity' => ['nullable', 'integer', 'min:1', 'max:9999'],
            ]);

            $shopper = $this->getShopper();
            Log::info('Shopper info', ['id' => $shopper?->id, 'type' => $shopper ? get_class($shopper) : 'guest']);

            $resolveCustomer = ($shopper instanceof Customer) ? $shopper : null;
            // Sales user (User model) will resolve to null customer, so it uses session based cart

            $resolved = $this->cartService->resolve($request, $resolveCustomer);
            $cart = $resolved['cart'];
            Log::info('Cart resolved', ['cart_id' => $cart->id, 'session_id' => $cart->session_id]);

            $product = Product::query()
                ->where('discontinued', false)
                ->findOrFail($validated['product_id']);

            $this->cartService->addItem($cart, $product, (int) ($validated['quantity'] ?? 1));

            $cart->load(['items.product']);
            $summary = $this->buildSummary($cart->items);

            Log::info('Item added successfully');

            $response = $request->wantsJson()
                ? response()->json(['summary' => $summary], 201)
                : redirect()->to('/cart');

            return $response->cookie($resolved['cookie']);
        } catch (\Exception $e) {
            Log::error('Error adding item to cart: '.$e->getMessage());
            Log::error($e->getTraceAsString());
            throw $e;
        }
    }

    public function setItemQuantity(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:9999'],
        ]);

        abort_if($product->discontinued, 404);

        $shopper = $this->getShopper();
        $resolveCustomer = ($shopper instanceof Customer) ? $shopper : null;
        $resolved = $this->cartService->resolve($request, $resolveCustomer);
        $cart = $resolved['cart'];

        $this->cartService->setItemQuantity($cart, $product, (int) $validated['quantity']);

        $cart->load(['items.product']);
        $summary = $this->buildSummary($cart->items);

        $response = $request->wantsJson()
            ? response()->json(['summary' => $summary])
            : redirect()->to('/cart');

        return $response->cookie($resolved['cookie']);
    }

    public function removeItem(Request $request, Product $product)
    {
        abort_if($product->discontinued, 404);

        $shopper = $this->getShopper();
        $resolveCustomer = ($shopper instanceof Customer) ? $shopper : null;
        $resolved = $this->cartService->resolve($request, $resolveCustomer);
        $cart = $resolved['cart'];

        $this->cartService->removeItem($cart, $product);

        $cart->load(['items.product']);
        $summary = $this->buildSummary($cart->items);

        $response = $request->wantsJson()
            ? response()->json(['summary' => $summary])
            : redirect()->to('/cart');

        return $response->cookie($resolved['cookie']);
    }

    public function clear(Request $request)
    {
        $shopper = $this->getShopper();
        $resolveCustomer = ($shopper instanceof Customer) ? $shopper : null;
        $resolved = $this->cartService->resolve($request, $resolveCustomer);
        $cart = $resolved['cart'];

        $this->cartService->clear($cart);

        $response = $request->wantsJson()
            ? response()->json(['summary' => $this->buildSummary(collect())])
            : redirect()->to('/cart');

        return $response->cookie($resolved['cookie']);
    }

    public function checkout(Request $request)
    {
        $shopper = $this->getShopper();
        abort_unless($shopper, 401);

        $rules = [
            'notes' => ['nullable', 'string', 'max:800'],
            'delivery_to' => ['nullable', 'string', 'max:120'],
            'delivery_phone' => ['nullable', 'string', 'max:30'],
            'delivery_address' => ['nullable', 'string', 'max:500'],
        ];

        if ($shopper instanceof User && $shopper->isSales()) {
            $rules['customer_id'] = ['required', 'exists:customers,id'];
            $rules['address_id'] = ['required', 'integer', 'exists:customer_addresses,id'];
        } elseif ($shopper instanceof Customer) {
            $rules['address_id'] = [
                'nullable',
                'integer',
                Rule::exists('customer_addresses', 'id')->where(fn ($q) => $q->where('customer_id', $shopper->id)),
            ];
        }

        $validated = $request->validate($rules);

        if ($shopper instanceof User && $shopper->isSales()) {
            $customer = Customer::query()
                ->where('id', $validated['customer_id'])
                ->where('sales_id', $shopper->id)
                ->first();

            if (! $customer) {
                abort(422, 'Customer tidak ditemukan atau bukan milik Anda.');
            }

            $address = CustomerAddress::query()
                ->where('id', $validated['address_id'])
                ->where('customer_id', $customer->id)
                ->first();

            if (! $address) {
                abort(422, 'Alamat tidak ditemukan untuk customer tersebut.');
            }
        }

        $resolveCustomer = ($shopper instanceof Customer) ? $shopper : null;
        $resolved = $this->cartService->resolve($request, $resolveCustomer);
        $cart = $resolved['cart'];

        $order = $this->cartService->checkout($cart, $shopper, $validated);

        return redirect()
            ->to('/orders/'.$order->id)
            ->withCookie($resolved['cookie']); // Make sure to use withCookie
    }

    public function customerAddresses(Request $request, Customer $customer)
    {
        $shopper = $this->getShopper();
        abort_unless($shopper && $shopper instanceof User && $shopper->isSales(), 401);
        abort_unless((int) $customer->sales_id === (int) $shopper->id, 403);

        $addresses = CustomerAddress::query()
            ->where('customer_id', $customer->id)
            ->orderByDesc('is_active')
            ->orderByDesc('id')
            ->get();

        if ($addresses->isEmpty() && trim((string) $customer->address) !== '') {
            $seeded = CustomerAddress::query()->create([
                'customer_id' => $customer->id,
                'label' => 'Alamat Utama',
                'recipient_name' => $customer->full_name,
                'phone' => $customer->phone,
                'address' => $customer->address,
                'province' => $customer->province,
                'city' => $customer->city,
                'postal_code' => $customer->postal_code,
                'is_active' => true,
            ]);

            $addresses = collect([$seeded]);
        }

        $activeAddressId = $addresses->firstWhere('is_active', true)?->id;

        return response()->json([
            'addresses' => $addresses->map(fn (CustomerAddress $addr) => [
                'id' => $addr->id,
                'label' => $addr->label,
                'recipient_name' => $addr->recipient_name,
                'phone' => $addr->phone,
                'full_address' => $addr->full_address,
                'is_active' => (bool) $addr->is_active,
            ]),
            'active_address_id' => $activeAddressId,
        ]);
    }

    private function buildSummary($items): array
    {
        $totalItems = 0;
        $subtotal = 0.0;
        $itemDetails = [];

        foreach ($items as $item) {
            $qty = (int) ($item->quantity ?? 0);
            $product = $item->product;
            $pricing = $product ? $product->pricingForQuantity($qty) : null;
            $unitPrice = (float) ($pricing['unit_price'] ?? 0);
            $discountPercent = (float) ($pricing['discount_percent'] ?? 0);
            $netPrice = (float) ($pricing['net_price'] ?? 0);
            $lineTotal = $netPrice * $qty;
            $totalItems += $qty;
            $subtotal += $lineTotal;

            if ($product) {
                $itemDetails[] = [
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'discount_percent' => $discountPercent,
                    'net_price' => $netPrice,
                    'line_total' => $lineTotal,
                ];
            }
        }

        return [
            'total_items' => $totalItems,
            'subtotal' => $subtotal,
            'grand_total' => $subtotal,
            'items' => $itemDetails,
        ];
    }
}

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-6">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold">PBS</div>
                <div>
                    <div class="text-lg font-semibold">Admin</div>
                    <div class="text-sm text-slate-500">Masuk ke dashboard</div>
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="post" action="{{ route('admin.login.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1" for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="w-full rounded-lg border-slate-200 focus:border-sky-500 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" for="password">Password</label>
                    <input id="password" name="password" type="password" required class="w-full rounded-lg border-slate-200 focus:border-sky-500 focus:ring-sky-500">
                </div>

                <div class="flex items-center justify-between">
                    <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                        Remember
                    </label>
                </div>

                <button type="submit" class="w-full py-2.5 rounded-lg bg-sky-600 text-white font-semibold hover:bg-sky-700">Login</button>
            </form>
        </div>

        <div class="text-center text-xs text-slate-500 mt-4">PAS Backend Admin</div>
    </div>
</body>
</html>


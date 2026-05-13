<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class RegionController extends Controller
{
    public function provinces()
    {
        return response()->json($this->cachedJson('regions.provinces', 'https://wilayah.id/api/provinces.json'));
    }

    public function regencies(string $provinceCode)
    {
        $provinceCode = trim($provinceCode);

        return response()->json($this->cachedJson(
            'regions.regencies.'.$provinceCode,
            'https://wilayah.id/api/regencies/'.rawurlencode($provinceCode).'.json'
        ));
    }

    public function districts(string $regencyCode)
    {
        $regencyCode = trim($regencyCode);

        return response()->json($this->cachedJson(
            'regions.v2.districts.'.$regencyCode,
            'https://wilayah.id/api/districts/'.rawurlencode($regencyCode).'.json'
        ));
    }

    public function villages(string $districtCode)
    {
        $districtCode = trim($districtCode);

        return response()->json($this->cachedJson(
            'regions.v2.villages.'.$districtCode,
            'https://wilayah.id/api/villages/'.rawurlencode($districtCode).'.json'
        ));
    }

    private function cachedJson(string $cacheKey, string $url): array
    {
        return Cache::remember($cacheKey, now()->addDay(), function () use ($url) {
            $res = Http::timeout(15)->get($url);
            if (! $res->ok()) {
                return ['data' => []];
            }

            $json = $res->json();
            if (is_array($json) && array_key_exists('data', $json)) {
                return $json;
            }

            return ['data' => []];
        });
    }
}

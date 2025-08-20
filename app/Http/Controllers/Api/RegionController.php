<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\JsonResponse;

class RegionController extends Controller
{
    /**
     * Return all regions for clients (mobile/web).
     */
    public function index(): JsonResponse
    {
        $regions = Region::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();

        return response()->json([
            'regions' => $regions,
        ]);
    }
}

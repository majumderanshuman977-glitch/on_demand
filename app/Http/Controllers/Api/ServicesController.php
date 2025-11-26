<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Services;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);

            $services = Services::paginate($perPage);

            // Transform collection to add full URL for service image
            $services->getCollection()->transform(function ($service) {
                $service->service_image_url = $service->services_image
                    ? asset('storage/' . $service->services_image)
                    : null;

                return $service; // Must return the item
            });

            return response()->json([
                'success' => true,
                'data' => $services,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;

class ApiController extends Controller
{
    /**
     * Base path for JSON files
     */
    private string $jsonPath;

    public function __construct()
    {
        $this->jsonPath = base_path() . "/public_html/json/";
    }

    /**
     * Get products data with cache headers
     */
    public function getProducts(): JsonResponse|Response
    {
        return $this->getJsonWithCacheHeaders('Productos.json');
    }

    /**
     * Get variations data with cache headers
     */
    public function getVariations(): JsonResponse|Response
    {
        return $this->getJsonWithCacheHeaders('Variaciones.json');
    }

    /**
     * Get payment methods data with cache headers
     */
    public function getPaymentMethods(): JsonResponse|Response
    {
        return $this->getJsonWithCacheHeaders('MetodosDePago.json');
    }

    /**
     * Get shipping zones data with cache headers
     */
    public function getShippingZones(): JsonResponse|Response
    {
        return $this->getJsonWithCacheHeaders('ZonasDeEnvio.json');
    }

    /**
     * Generic method to serve JSON files with proper cache headers
     */
    private function getJsonWithCacheHeaders(string $filename): JsonResponse|Response
    {
        $filePath = $this->jsonPath . $filename;

        // Check if file exists
        if (!File::exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Get file info for cache headers
        $lastModified = File::lastModified($filePath);
        $etag = md5($filename . $lastModified);
        $content = File::get($filePath);

        // Validate JSON content
        $jsonData = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Invalid JSON data'], 500);
        }

        // Create response with cache headers
        $response = response()->json($jsonData);

        $request = request();

        // Check if client is requesting no-cache (force fresh data)
        $clientCacheControl = $request->header('Cache-Control', '');
        $isNoCacheRequest = strpos(strtolower($clientCacheControl), 'no-cache') !== false ||
                           $request->header('Pragma') === 'no-cache';

        if ($isNoCacheRequest) {
            // Force fresh data, disable cache
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        } else {
            // Normal cache behavior
            $response->headers->set('Cache-Control', 'public, max-age=3600, must-revalidate');
            $response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s', $lastModified) . ' GMT');
            $response->headers->set('ETag', '"' . $etag . '"');

            // Handle conditional requests only for cacheable requests
            // Check If-None-Match header (ETag)
            if ($request->header('If-None-Match') === '"' . $etag . '"') {
                return response('', 304);
            }

            // Check If-Modified-Since header
            $ifModifiedSince = $request->header('If-Modified-Since');
            if ($ifModifiedSince && strtotime($ifModifiedSince) >= $lastModified) {
                return response('', 304);
            }
        }

        return $response;
    }
}

<?php
// app/Http/Controllers/Api/ActivityCategoryController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityCategory;

class ActivityCategoryController extends Controller
{
    /**
     * Get all activity categories
     */
    public function index()
    {
        try {
            $categories = ActivityCategory::all();

            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get activity categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific activity category
     */
    public function show($id)
    {
        try {
            $category = ActivityCategory::with('tors')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Activity category not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}

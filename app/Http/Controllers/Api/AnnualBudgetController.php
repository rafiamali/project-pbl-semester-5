<?php
// app/Http/Controllers/Api/AnnualBudgetController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AnnualBudget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnnualBudgetController extends Controller
{
    /**
     * Get all annual budgets
     */
    public function index()
    {
        try {
            $budgets = AnnualBudget::orderBy('tahun', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $budgets
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get annual budgets',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create annual budget
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required|string|size:4|unique:annual_budget,tahun',
            'budget' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $budget = AnnualBudget::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Annual budget created successfully',
                'data' => $budget
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create annual budget',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific annual budget with statistics
     */
    public function show($id)
    {
        try {
            $budget = AnnualBudget::with('tors')->findOrFail($id);

            $data = [
                'budget' => $budget,
                'remaining_budget' => $budget->getRemainingBudget(),
                'usage_percentage' => $budget->getBudgetUsagePercentage(),
                'total_tors' => $budget->tors->count(),
                'approved_tors' => $budget->tors->where('status', 'approved_by_head')->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Annual budget not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update annual budget
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'string|size:4|unique:annual_budget,tahun,' . $id . ',budget_id',
            'budget' => 'numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $budget = AnnualBudget::findOrFail($id);
            $budget->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Annual budget updated successfully',
                'data' => $budget
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update annual budget',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete annual budget
     */
    public function destroy($id)
    {
        try {
            $budget = AnnualBudget::findOrFail($id);

            // Check if budget is being used
            if ($budget->tors()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete budget that is being used by TORs'
                ], 400);
            }

            $budget->delete();

            return response()->json([
                'success' => true,
                'message' => 'Annual budget deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete annual budget',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StatusHist;
use Illuminate\Http\Request;

class RiwayatStatusController extends Controller
{
    /**
     * Get status history for TOR
     *
     * @param int $torId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTorHistory($torId)
    {
        try {
            $histories = StatusHist::with(['user', 'tor'])
                ->where('tor_id', $torId)
                ->orderBy('timestamp_aksi', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $histories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get TOR history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get status history for LPJ
     *
     * @param int $lpjId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLpjHistory($lpjId)
    {
        try {
            $histories = StatusHist::with(['user', 'lpj'])
                ->where('lpj_id', $lpjId)
                ->orderBy('timestamp_aksi', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $histories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get LPJ history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all status histories (for admin)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = StatusHist::with(['user', 'tor', 'lpj']);

            // Filter by TOR
            if ($request->has('tor_id')) {
                $query->where('tor_id', $request->tor_id);
            }

            // Filter by LPJ
            if ($request->has('lpj_id')) {
                $query->where('lpj_id', $request->lpj_id);
            }

            // Filter by user
            if ($request->has('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by date range
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('timestamp_aksi', [
                    $request->start_date,
                    $request->end_date
                ]);
            }

            $histories = $query->orderBy('timestamp_aksi', 'desc')
                ->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => $histories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get status histories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific status history
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $history = StatusHist::with(['user', 'tor', 'lpj'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $history
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Status history not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get statistics of status changes
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatistics(Request $request)
    {
        try {
            $query = StatusHist::query();

            // Filter by date range if provided
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('timestamp_aksi', [
                    $request->start_date,
                    $request->end_date
                ]);
            }

            $statistics = [
                'total_status_changes' => $query->count(),
                'by_status' => $query->groupBy('status')
                    ->selectRaw('status, count(*) as count')
                    ->pluck('count', 'status'),
                'by_user' => $query->with('user')
                    ->groupBy('user_id')
                    ->selectRaw('user_id, count(*) as count')
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [
                            $item->user->full_name ?? 'Unknown' => $item->count
                        ];
                    }),
                'recent_activities' => StatusHist::with(['user', 'tor', 'lpj'])
                    ->orderBy('timestamp_aksi', 'desc')
                    ->limit(10)
                    ->get()
            ];

            return response()->json([
                'success' => true,
                'data' => $statistics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

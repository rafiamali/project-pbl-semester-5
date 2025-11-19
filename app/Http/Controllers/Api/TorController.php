<?php
// app/Http/Controllers/Api/TorController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TorController extends Controller
{
    /**
     * Get all TORs
     */
    public function index(Request $request)
    {
        try {
            $query = Tor::with(['user', 'category', 'annualBudget', 'statusHistories']);

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by category
            if ($request->has('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            // Filter by user (my TORs)
            if ($request->has('my_tors') && $request->my_tors == true) {
                $query->where('user_id', Auth::guard('api')->id());
            }

            $tors = $query->orderBy('sub_date', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $tors
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get TORs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create new TOR
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'activity_name' => 'required|string|max:255',
            'activity_background' => 'required|string',
            'activity_purpose' => 'required|string',
            'participant' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'budget_submitted' => 'required|numeric|min:0',
            'pic' => 'required|string|max:100',
            'category_id' => 'required|exists:activity_category,category_id',
            'budget_id' => 'required|exists:annual_budget,budget_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $tor = Tor::create([
                'activity_name' => $request->activity_name,
                'activity_background' => $request->activity_background,
                'activity_purpose' => $request->activity_purpose,
                'participant' => $request->participant,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'budget_submitted' => $request->budget_submitted,
                'pic' => $request->pic,
                'category_id' => $request->category_id,
                'budget_id' => $request->budget_id,
                'user_id' => Auth::guard('api')->id(),
                'status' => 'draft',
                'current_stage' => 'draft',
            ]);

            $tor->addStatusHistory('draft', 'TOR created as draft', Auth::guard('api')->id());

            return response()->json([
                'success' => true,
                'message' => 'TOR created successfully',
                'data' => $tor->load(['user', 'category', 'annualBudget'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create TOR',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific TOR
     */
    public function show($id)
    {
        try {
            $tor = Tor::with(['user', 'category', 'annualBudget', 'statusHistories.user', 'approvals.user', 'approvals.role', 'attachments'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $tor
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'TOR not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update TOR
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'activity_name' => 'string|max:255',
            'activity_background' => 'string',
            'activity_purpose' => 'string',
            'participant' => 'string',
            'start_date' => 'date',
            'end_date' => 'date|after:start_date',
            'budget_submitted' => 'numeric|min:0',
            'pic' => 'string|max:100',
            'category_id' => 'exists:activity_category,category_id',
            'budget_id' => 'exists:annual_budget,budget_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $tor = Tor::findOrFail($id);

            // Check if user owns this TOR
            if ($tor->user_id !== Auth::guard('api')->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to update this TOR'
                ], 403);
            }

            // Only allow update if status is draft or needs_revision
            if (!in_array($tor->status, ['draft', 'needs_revision'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot update TOR in current status'
                ], 400);
            }

            $tor->update($request->all());
            $tor->addStatusHistory('updated', 'TOR updated', Auth::guard('api')->id());

            return response()->json([
                'success' => true,
                'message' => 'TOR updated successfully',
                'data' => $tor->load(['user', 'category', 'annualBudget'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update TOR',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete TOR
     */
    public function destroy($id)
    {
        try {
            $tor = Tor::findOrFail($id);

            // Check if user owns this TOR
            if ($tor->user_id !== Auth::guard('api')->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this TOR'
                ], 403);
            }

            // Only allow delete if status is draft
            if ($tor->status !== 'draft') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete TOR that has been submitted'
                ], 400);
            }

            $tor->delete();

            return response()->json([
                'success' => true,
                'message' => 'TOR deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete TOR',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit TOR
     */
    public function submit($id)
    {
        try {
            $tor = Tor::findOrFail($id);

            // Check if user owns this TOR
            if ($tor->user_id !== Auth::guard('api')->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to submit this TOR'
                ], 403);
            }

            // Only allow submit if status is draft or needs_revision
            if (!in_array($tor->status, ['draft', 'needs_revision'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot submit TOR in current status'
                ], 400);
            }

            $tor->submit(Auth::guard('api')->id());

            return response()->json([
                'success' => true,
                'message' => 'TOR submitted successfully',
                'data' => $tor
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit TOR',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Review by Secretary
     */
    public function reviewBySecretary(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:approved,rejected,request_revision',
            'catatan' => 'required_if:action,rejected,request_revision|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $tor = Tor::findOrFail($id);
            $user = Auth::guard('api')->user();

            // Check if user is secretary
            if (!$user->isSekretaris()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only secretary can review TOR'
                ], 403);
            }

            // Check if TOR is in correct stage
            if ($tor->status !== 'submitted') {
                return response()->json([
                    'success' => false,
                    'message' => 'TOR is not in submitted stage'
                ], 400);
            }

            $action = $request->action;
            $catatan = $request->catatan;

            if ($action === 'approved') {
                $tor->approveBySecretary($user->user_id, $user->role_id, $catatan);
                $message = 'TOR reviewed by secretary';
            } elseif ($action === 'rejected') {
                $tor->reject($user->user_id, $user->role_id, $catatan);
                $message = 'TOR rejected by secretary';
            } else {
                $tor->requestRevision($user->user_id, $user->role_id, $catatan);
                $message = 'Revision requested by secretary';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $tor
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to review TOR',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify by Admin
     */
    public function verifyByAdmin(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:approved,rejected,request_revision',
            'catatan' => 'required_if:action,rejected,request_revision|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $tor = Tor::findOrFail($id);
            $user = Auth::guard('api')->user();

            // Check if user is admin
            if (!$user->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only admin can verify TOR'
                ], 403);
            }

            // Check if TOR is in correct stage
            if ($tor->status !== 'reviewed_by_secretary') {
                return response()->json([
                    'success' => false,
                    'message' => 'TOR must be reviewed by secretary first'
                ], 400);
            }

            $action = $request->action;
            $catatan = $request->catatan;

            if ($action === 'approved') {
                $tor->verifyByAdmin($user->user_id, $user->role_id, $catatan);
                $message = 'TOR verified by admin';
            } elseif ($action === 'rejected') {
                $tor->reject($user->user_id, $user->role_id, $catatan);
                $message = 'TOR rejected by admin';
            } else {
                $tor->requestRevision($user->user_id, $user->role_id, $catatan);
                $message = 'Revision requested by admin';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $tor
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify TOR',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve by Head
     */
    public function approveByHead(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:approved,rejected,request_revision',
            'catatan' => 'required_if:action,rejected,request_revision|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $tor = Tor::findOrFail($id);
            $user = Auth::guard('api')->user();

            // Check if user is head
            if (!$user->isKetua()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only department head can approve TOR'
                ], 403);
            }

            // Check if TOR is in correct stage
            if ($tor->status !== 'verified_by_admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'TOR must be verified by admin first'
                ], 400);
            }

            $action = $request->action;
            $catatan = $request->catatan;

            if ($action === 'approved') {
                $tor->approveByHead($user->user_id, $user->role_id, $catatan);
                $message = 'TOR approved by department head';
            } elseif ($action === 'rejected') {
                $tor->reject($user->user_id, $user->role_id, $catatan);
                $message = 'TOR rejected by department head';
            } else {
                $tor->requestRevision($user->user_id, $user->role_id, $catatan);
                $message = 'Revision requested by department head';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $tor
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve TOR',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

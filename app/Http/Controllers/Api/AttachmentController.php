<?php
// app/Http/Controllers/Api/AttachmentController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Models\Tor;
use App\Models\Lpj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AttachmentController extends Controller
{
    /**
     * Upload attachment
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:10240', // Max 10MB
            'tor_id' => 'required_without:lpj_id|exists:tor,tor_id',
            'lpj_id' => 'required_without:tor_id|exists:lpj,lpj_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Check ownership
            if ($request->has('tor_id')) {
                $tor = Tor::findOrFail($request->tor_id);
                if ($tor->user_id !== Auth::guard('api')->id()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized to upload attachment for this TOR'
                    ], 403);
                }
            }

            if ($request->has('lpj_id')) {
                $lpj = Lpj::findOrFail($request->lpj_id);
                if ($lpj->user_id !== Auth::guard('api')->id()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized to upload attachment for this LPJ'
                    ], 403);
                }
            }

            $file = $request->file('file');
            $path = $file->store('attachments', 'public');

            $attachment = Attachment::create([
                'file_path' => $path,
                'tor_id' => $request->tor_id ?? null,
                'lpj_id' => $request->lpj_id ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'data' => $attachment
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload file',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download attachment
     */
    public function download($id)
    {
        try {
            $attachment = Attachment::findOrFail($id);

            if (!Storage::disk('public')->exists($attachment->file_path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found'
                ], 404);
            }

            return Storage::disk('public')->download($attachment->file_path);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to download file',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete attachment
     */
    public function destroy($id)
    {
        try {
            $attachment = Attachment::findOrFail($id);

            // Check ownership
            if ($attachment->tor_id) {
                $tor = $attachment->tor;
                if ($tor->user_id !== Auth::guard('api')->id()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized to delete this attachment'
                    ], 403);
                }
            }

            if ($attachment->lpj_id) {
                $lpj = $attachment->lpj;
                if ($lpj->user_id !== Auth::guard('api')->id()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized to delete this attachment'
                    ], 403);
                }
            }

            // Delete file from storage
            if (Storage::disk('public')->exists($attachment->file_path)) {
                Storage::disk('public')->delete($attachment->file_path);
            }

            $attachment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Attachment deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete attachment',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

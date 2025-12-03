<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\TicketAttachment;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TicketAttachmentController extends Controller
{
    /**
     * Upload attachments to a ticket
     */
    public function upload(Request $request, $ticketId)
    {
        try {
            $request->validate([
                'attachments' => 'required|array',
                'attachments.*' => 'file|max:10240', // 10MB max
            ]);

            $ticket = Ticket::findOrFail($ticketId);
            $uploadedFiles = [];

            foreach ($request->file('attachments') as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $fileName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
                
                // Get file properties BEFORE moving
                $fileSize = $file->getSize();
                $mimeType = $file->getMimeType();
                
                // Store in public/uploads/tickets directory
                $path = $file->move(public_path('uploads/tickets'), $fileName);
                $relativePath = 'uploads/tickets/' . $fileName;

                // Create attachment record
                $attachment = TicketAttachment::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => auth()->id(),
                    'file_name' => $originalName,
                    'file_path' => $relativePath,
                    'file_size' => $fileSize,
                    'file_type' => $mimeType,
                ]);

                $uploadedFiles[] = $attachment;

                \Log::info('Ticket attachment uploaded', [
                    'ticket_id' => $ticket->id,
                    'file_name' => $originalName,
                    'file_path' => $relativePath,
                    'file_size' => $fileSize
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => count($uploadedFiles) . ' file(s) uploaded successfully',
                'attachments' => $uploadedFiles
            ]);
        } catch (\Throwable $th) {
            \Log::error('Error uploading ticket attachments: ' . $th->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => $th->getMessage() ?? 'Failed to upload files'
            ], 400);
        }
    }

    /**
     * Delete an attachment
     */
    public function destroy($id)
    {
        try {
            $attachment = TicketAttachment::findOrFail($id);
            
            // Delete file from storage
            if ($attachment->file_path) {
                $fullPath = public_path($attachment->file_path);
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                    \Log::info('Deleted attachment file: ' . $attachment->file_path);
                }
            }

            // Delete record
            $attachment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Attachment deleted successfully'
            ]);
        } catch (\Throwable $th) {
            \Log::error('Error deleting attachment: ' . $th->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => $th->getMessage() ?? 'Failed to delete attachment'
            ], 400);
        }
    }
}


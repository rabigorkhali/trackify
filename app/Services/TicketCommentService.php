<?php

namespace App\Services;

use App\Models\TicketComment;

class TicketCommentService extends Service
{
    public function __construct(TicketComment $model)
    {
        parent::__construct($model);
    }

    public function store($request)
    {
        $data = $request->except('_token', 'attachments');
        $data['user_id'] = auth()->id();
        
        // Handle multiple file uploads
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                // Get file info BEFORE moving
                $originalName = $file->getClientOriginalName();
                $mimeType = $file->getClientMimeType();
                $fileSize = $file->getSize();
                
                // Generate unique filename and move
                $fileName = time() . '_' . uniqid() . '_' . $originalName;
                $file->move(public_path('uploads/ticket_comments'), $fileName);
                
                // Store file info
                $attachments[] = [
                    'name' => $originalName,
                    'path' => 'uploads/ticket_comments/' . $fileName,
                    'type' => $mimeType,
                    'size' => $fileSize
                ];
            }
        }
        
        $data['attachments'] = !empty($attachments) ? $attachments : null;
        
        return $this->model->create($data);
    }
}


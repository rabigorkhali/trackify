<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FileManagerController extends Controller
{
    public function index(Request $request)
    {
        return view('backend.system.file-manager');
    }
    public function ckeditorUpload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'upload' => 'required|image|mimes:jpg,jpeg,png,gif,webp,svg|max:10240' // Max: 10MB
        ]);

        if ($validator->fails()) {
            return  'Invalid file. Must be an image (JPG, PNG, GIF, WEBP, SVG) and under 10MB.';
        }
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');

            // ✅ Keep the original filename but sanitize it
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();

            // ✅ Sanitize filename (remove spaces, special chars)
            $safeFilename = Str::slug($originalFilename);
            $finalFilename = $safeFilename . '.' . $extension;

            // ✅ Ensure filename uniqueness (prevent overwrites)
            $counter = 1;
            $uploadPath = public_path('uploads/ckeditor/');
            while (file_exists($uploadPath . $finalFilename)) {
                $finalFilename = $safeFilename . '_' . $counter . '.' . $extension;
                $counter++;
            }

            // ✅ Move file to uploads folder
            $file->move($uploadPath, $finalFilename);

            return asset('uploads/ckeditor/' . $finalFilename);
        }

        return 'File upload failed.';
    }

}

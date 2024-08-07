<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function store(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|file|mimes:jpg,png,pdf,docx|max:2048',
        ]);

        // Handle the file upload
        if ($request->file()) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $fileName, 'public');

            // You can save the file path in the database if needed
            // ...

            return back()->with('success', 'File uploaded successfully!')->with('file', $fileName);
        }

        return back()->withErrors(['file' => 'File upload failed.']);
    }
}

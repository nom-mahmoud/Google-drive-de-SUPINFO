<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'required|file',
            'folder_id' => 'nullable|exists:folders,id'
        ]);

        if ($request->folder_id) {
            Folder::where('user_id', $request->user()->id)->findOrFail($request->folder_id);
        }

        $uploadedFiles = [];
        foreach ($request->file('files') as $uploadedFile) {
            $uuidName = Str::uuid()->toString();
            $diskPath = $uploadedFile->storeAs('', $uuidName, 'local_storage');

            $file = File::create([
                'original_name' => $uploadedFile->getClientOriginalName(),
                'mime_type' => $uploadedFile->getMimeType(),
                'size' => $uploadedFile->getSize(),
                'disk_path' => $diskPath,
                'folder_id' => $request->folder_id,
                'user_id' => $request->user()->id,
            ]);
            $uploadedFiles[] = $file;
        }

        return response()->json($uploadedFiles, 201);
    }

    public function download(Request $request, File $file)
    {
        if ($file->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return Storage::disk('local_storage')->download($file->disk_path, $file->original_name);
    }
    
    public function preview(Request $request, File $file)
    {
        if ($file->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        return Storage::disk('local_storage')->response($file->disk_path);
    }

    public function update(Request $request, File $file)
    {
        if ($file->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'original_name' => 'required|string|max:255',
        ]);

        $file->update(['original_name' => $request->original_name]);
        return response()->json($file);
    }

    public function destroy(Request $request, File $file)
    {
        if ($file->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        Storage::disk('local_storage')->delete($file->disk_path);
        $file->delete();

        return response()->json(['message' => 'File deleted']);
    }
}

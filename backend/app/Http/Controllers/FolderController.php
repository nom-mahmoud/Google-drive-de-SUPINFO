<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function index(Request $request)
    {
        $parentId = $request->query('parent_id');

        $folders = Folder::where('user_id', $request->user()->id)
            ->where('parent_id', $parentId)
            ->get();

        $files = $parentId
            ? \App\Models\File::where('user_id', $request->user()->id)->where('folder_id', $parentId)->get()
            : \App\Models\File::where('user_id', $request->user()->id)->whereNull('folder_id')->get();

        return response()->json([
            'folders' => $folders,
            'files' => $files,
            'breadcrumbs' => $this->getBreadcrumbs($parentId),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id',
        ]);

        if ($request->parent_id) {
            Folder::where('user_id', $request->user()->id)->findOrFail($request->parent_id);
        }

        $folder = Folder::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'user_id' => $request->user()->id,
        ]);

        return response()->json($folder, 201);
    }

    public function update(Request $request, Folder $folder)
    {
        if ($folder->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $folder->update(['name' => $request->name]);
        return response()->json($folder);
    }

    public function destroy(Request $request, Folder $folder)
    {
        if ($folder->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $folder->delete();
        return response()->json(['message' => 'Folder deleted']);
    }

    public function download(Request $request, Folder $folder)
    {
        if ($folder->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $zipFileName = $folder->name . '.zip';
        $zipPath = storage_path('app/local_storage/' . $zipFileName);

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            $this->addFolderToZip($folder, $zip, $folder->name);
            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    private function addFolderToZip($folder, $zip, $currentPath)
    {
        // Add files in this folder
        foreach ($folder->files as $file) {
            $filePath = storage_path('app/local_storage/' . $file->disk_path);
            if (file_exists($filePath)) {
                $zip->addFile($filePath, $currentPath . '/' . $file->original_name);
            }
        }

        // Recursively add subfolders
        foreach ($folder->children as $child) {
            $zip->addEmptyDir($currentPath . '/' . $child->name);
            $this->addFolderToZip($child, $zip, $currentPath . '/' . $child->name);
        }
    }

    private function getBreadcrumbs($folderId)
    {
        $breadcrumbs = [];
        while ($folderId) {
            $folder = Folder::find($folderId);
            if (!$folder) break;
            array_unshift($breadcrumbs, [
                'id' => $folder->id,
                'name' => $folder->name,
            ]);
            $folderId = $folder->parent_id;
        }

        array_unshift($breadcrumbs, ['id' => null, 'name' => 'Root']);
        return $breadcrumbs;
    }
}

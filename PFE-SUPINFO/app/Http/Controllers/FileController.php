<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function index(Request $request)
    {
        $folderId = $request->get('folder_id');
        $user = Auth::user();

        $folders = Folder::where('user_id', $user->id)
            ->where('parent_id', $folderId)
            ->get();

        $files = File::where('user_id', $user->id)
            ->where('folder_id', $folderId)
            ->get();

        $currentFolder = $folderId ? Folder::findOrFail($folderId) : null;
        
        // Breadcrumb logic
        $breadcrumbs = [];
        $tempFolder = $currentFolder;
        while ($tempFolder) {
            array_unshift($breadcrumbs, $tempFolder);
            $tempFolder = $tempFolder->parent;
        }

        $userAgent = $request->header('User-Agent');
        $isMobile = preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $userAgent);
        
        $view = $isMobile ? 'mobile.files.index' : 'web.files.index';

        return view($view, compact('folders', 'files', 'currentFolder', 'breadcrumbs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|max:51200', // 50MB max as per UI
            'folder_id' => 'nullable|exists:folders,id',
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $uploadedFile) {
                $path = $uploadedFile->store('supfile/' . Auth::id(), 'local');
                
                File::create([
                    'user_id' => Auth::id(),
                    'folder_id' => $request->folder_id,
                    'name' => $uploadedFile->getClientOriginalName(),
                    'path' => $path,
                    'size' => $uploadedFile->getSize(),
                    'mime_type' => $uploadedFile->getMimeType(),
                ]);
            }
        }

        return back()->with('success', 'Fichiers envoyés avec succès.');
    }

    public function download(File $file)
    {
        if ($file->user_id !== Auth::id()) {
            abort(403);
        }

        return Storage::disk('local')->download($file->path, $file->name);
    }

    public function destroy(File $file)
    {
        if ($file->user_id !== Auth::id()) {
            abort(403);
        }

        $file->delete();
        return back()->with('success', 'Fichier déplacé dans la corbeille.');
    }

    public function trash()
    {
        $deletedFolders = Folder::onlyTrashed()->where('user_id', Auth::id())->get();
        $deletedFiles = File::onlyTrashed()->where('user_id', Auth::id())->get();

        return view('web.files.trash', compact('deletedFolders', 'deletedFiles'));
    }
}

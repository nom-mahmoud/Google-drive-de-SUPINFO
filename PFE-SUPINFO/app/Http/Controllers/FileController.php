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
        $search = $request->get('q');
        $user = Auth::user();

        if ($search) {
            // Search matching folders and files recursively across all user's directories
            $folders = Folder::where('user_id', $user->id)
                ->where('name', 'like', "%{$search}%")
                ->get();

            $files = File::where('user_id', $user->id)
                ->where('name', 'like', "%{$search}%")
                ->get();
        } else {
            $folders = Folder::where('user_id', $user->id)
                ->where('parent_id', $folderId)
                ->get();

            $files = File::where('user_id', $user->id)
                ->where('folder_id', $folderId)
                ->get();
        }

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

        $allFolders = Folder::where('user_id', $user->id)->get();
        
        return view($view, compact('folders', 'files', 'currentFolder', 'breadcrumbs', 'allFolders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|max:51200', // 50MB max as per UI
            'folder_id' => 'nullable|exists:folders,id',
        ]);

        $user = Auth::user();
        $totalBytesUsed = File::where('user_id', $user->id)->sum('size');
        $quotaMaxBytes = $user->storage_limit ?? (30 * 1024 * 1024 * 1024); // fallback 30 GB

        if ($request->hasFile('files')) {
            $totalUploadSize = 0;
            foreach ($request->file('files') as $uploadedFile) {
                $totalUploadSize += $uploadedFile->getSize();
            }

            // Storage quota enforcement
            if ($totalBytesUsed + $totalUploadSize > $quotaMaxBytes) {
                return back()->withErrors(['error' => 'Espace de stockage restant insuffisant pour effectuer cet import.']);
            }

            foreach ($request->file('files') as $uploadedFile) {
                $path = $uploadedFile->store('supfile/' . $user->id, 'local');
                
                File::create([
                    'user_id' => $user->id,
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

    public function trash(Request $request)
    {
        $deletedFolders = Folder::onlyTrashed()->where('user_id', Auth::id())->get();
        $deletedFiles = File::onlyTrashed()->where('user_id', Auth::id())->get();

        $userAgent = $request->header('User-Agent');
        $isMobile = preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $userAgent);
        
        $view = $isMobile ? 'mobile.files.trash' : 'web.files.trash';

        return view($view, compact('deletedFolders', 'deletedFiles'));
    }

    public function preview(File $file)
    {
        if ($file->user_id !== Auth::id()) {
            abort(403);
        }

        $path = Storage::disk('local')->path($file->path);
        
        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Content-Type' => $file->mime_type,
            'Content-Disposition' => 'inline; filename="' . $file->name . '"'
        ]);
    }

    public function restore($id)
    {
        $file = File::onlyTrashed()->where('user_id', Auth::id())->findOrFail($id);
        $file->restore();

        return back()->with('success', 'Fichier restauré.');
    }

    public function forceDelete($id)
    {
        $file = File::onlyTrashed()->where('user_id', Auth::id())->findOrFail($id);
        
        // Delete actual file from storage
        if (Storage::disk('local')->exists($file->path)) {
            Storage::disk('local')->delete($file->path);
        }

        $file->forceDelete();

        return back()->with('success', 'Fichier supprimé définitivement.');
    }

    public function rename(Request $request, File $file)
    {
        if ($file->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $file->update([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Fichier renommé avec succès.');
    }

    public function move(Request $request, File $file)
    {
        if ($file->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'folder_id' => 'nullable|exists:folders,id',
        ]);

        // Security: Ensure target folder belongs to the user
        if ($request->folder_id) {
            $targetFolder = Folder::findOrFail($request->folder_id);
            if ($targetFolder->user_id !== Auth::id()) {
                abort(403);
            }
        }

        $file->update([
            'folder_id' => $request->folder_id,
        ]);

        return back()->with('success', 'Fichier déplacé avec succès.');
    }
}

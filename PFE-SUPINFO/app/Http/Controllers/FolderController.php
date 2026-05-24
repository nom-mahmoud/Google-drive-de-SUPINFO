<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FolderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id',
        ]);

        Folder::create([
            'name' => $request->name,
            'user_id' => Auth::id(),
            'parent_id' => $request->parent_id,
        ]);

        return back()->with('success', 'Dossier créé avec succès.');
    }

    public function destroy(Folder $folder)
    {
        $this->authorizeAccess($folder);
        $folder->delete();

        return back()->with('success', 'Dossier déplacé dans la corbeille.');
    }

    public function restore($id)
    {
        $folder = Folder::onlyTrashed()->where('user_id', Auth::id())->findOrFail($id);
        $folder->restore();

        return back()->with('success', 'Dossier restauré.');
    }

    public function downloadZip(Folder $folder)
    {
        $this->authorizeAccess($folder);

        $zip = new \ZipArchive();
        $zipFileName = tempnam(sys_get_temp_dir(), 'supfile-zip-') . '.zip';

        if ($zip->open($zipFileName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return back()->withErrors(['error' => 'Impossible de générer le fichier ZIP.']);
        }

        $this->addFolderToZip($folder, $zip, '');

        $zip->close();

        return response()->download($zipFileName, $folder->name . '.zip')->deleteFileAfterSend(true);
    }

    private function addFolderToZip(Folder $folder, \ZipArchive $zip, $parentPath)
    {
        $folderPath = $parentPath ? $parentPath . '/' . $folder->name : $folder->name;
        
        $zip->addEmptyDir($folderPath);

        foreach ($folder->files as $file) {
            $storagePath = Storage::disk('local')->path($file->path);
            if (file_exists($storagePath)) {
                $zip->addFile($storagePath, $folderPath . '/' . $file->name);
            }
        }

        foreach ($folder->children as $child) {
            $this->addFolderToZip($child, $zip, $folderPath);
        }
    }

    private function authorizeAccess(Folder $folder)
    {
        if ($folder->user_id != Auth::id()) {
            abort(403);
        }
    }

    public function forceDelete($id)
    {
        $folder = Folder::onlyTrashed()->where('user_id', Auth::id())->findOrFail($id);
        
        $this->deleteFolderPermanently($folder);

        return back()->with('success', 'Dossier et son contenu supprimés définitivement.');
    }

    private function deleteFolderPermanently(Folder $folder)
    {
        // Deleting files
        foreach ($folder->files()->withTrashed()->get() as $file) {
            if (Storage::disk('local')->exists($file->path)) {
                Storage::disk('local')->delete($file->path);
            }
            $file->forceDelete();
        }

        // Deleting children recursively
        foreach ($folder->children()->withTrashed()->get() as $child) {
            $this->deleteFolderPermanently($child);
        }

        $folder->forceDelete();
    }

    public function rename(Request $request, Folder $folder)
    {
        $this->authorizeAccess($folder);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $folder->update([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Dossier renommé avec succès.');
    }

    public function move(Request $request, Folder $folder)
    {
        $this->authorizeAccess($folder);

        $request->validate([
            'parent_id' => 'nullable|exists:folders,id',
        ]);

        // Security check: cannot move a folder into itself or into its own descendants
        if ($request->parent_id) {
            $targetFolder = Folder::findOrFail($request->parent_id);
            if ($targetFolder->user_id != Auth::id()) {
                abort(403);
            }

            $temp = $targetFolder;
            while ($temp) {
                if ($temp->id == $folder->id) {
                    return back()->withErrors(['error' => 'Impossible de déplacer un dossier à l\'intérieur de lui-même ou de ses sous-dossiers.']);
                }
                $temp = $temp->parent;
            }
        }

        $folder->update([
            'parent_id' => $request->parent_id,
        ]);

        return back()->with('success', 'Dossier déplacé avec succès.');
    }
}

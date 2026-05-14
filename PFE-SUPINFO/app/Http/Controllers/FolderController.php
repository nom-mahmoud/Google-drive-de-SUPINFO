<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    private function authorizeAccess(Folder $folder)
    {
        if ($folder->user_id !== Auth::id()) {
            abort(403);
        }
    }
}

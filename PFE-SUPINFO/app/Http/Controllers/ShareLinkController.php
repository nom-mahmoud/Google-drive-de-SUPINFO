<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use App\Models\ShareLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ShareLinkController extends Controller
{
    private function isMobile(Request $request)
    {
        return preg_match('/Mobile|Android|BlackBerry|iPhone|Windows Phone/i', $request->header('User-Agent'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file_id' => 'nullable|exists:files,id',
            'folder_id' => 'nullable|exists:folders,id',
            'expires_at' => 'nullable|date|after:now',
            'password' => 'nullable|string|min:4',
        ]);

        if (!$request->file_id && !$request->folder_id) {
            return back()->withErrors(['error' => 'Sélectionnez un fichier ou un dossier à partager.']);
        }

        // Validate ownership
        if ($request->file_id) {
            $file = File::findOrFail($request->file_id);
            if ($file->user_id !== Auth::id()) abort(403);
        }
        if ($request->folder_id) {
            $folder = Folder::findOrFail($request->folder_id);
            if ($folder->user_id !== Auth::id()) abort(403);
        }

        $shareLink = ShareLink::create([
            'user_id' => Auth::id(),
            'file_id' => $request->file_id,
            'folder_id' => $request->folder_id,
            'token' => Str::random(32),
            'expires_at' => $request->expires_at,
            'password' => $request->password ? Hash::make($request->password) : null,
        ]);

        $url = route('shares.public', $shareLink->token);

        return back()->with('share_success', 'Lien de partage créé avec succès !')
                     ->with('share_url', $url);
    }

    public function destroy(ShareLink $shareLink)
    {
        if ($shareLink->user_id !== Auth::id()) {
            abort(403);
        }

        $shareLink->delete();

        return back()->with('success', 'Lien de partage révoqué.');
    }

    public function show(Request $request, $token)
    {
        $shareLink = ShareLink::where('token', $token)->firstOrFail();

        // Check expiry
        if ($shareLink->expires_at && $shareLink->expires_at->isPast()) {
            abort(404, 'Ce lien de partage a expiré.');
        }

        // Check password protection
        if ($shareLink->password) {
            if (!session("verified_share_$token")) {
                $viewPath = $this->isMobile($request) ? 'mobile.shares.password' : 'web.shares.password';
                return view($viewPath, compact('token'));
            }
        }

        $isMobile = $this->isMobile($request);

        if ($shareLink->file_id) {
            $file = $shareLink->file;
            $viewPath = $isMobile ? 'mobile.shares.file' : 'web.shares.file';
            return view($viewPath, compact('shareLink', 'file'));
        }

        if ($shareLink->folder_id) {
            $folder = $shareLink->folder;
            $currentFolderId = $request->get('subfolder_id', $folder->id);

            // Ensure subfolder is actually a descendant of the shared folder (basic security)
            $currentFolder = Folder::findOrFail($currentFolderId);
            $temp = $currentFolder;
            $isDescendant = false;
            while ($temp) {
                if ($temp->id == $folder->id) {
                    $isDescendant = true;
                    break;
                }
                $temp = $temp->parent;
            }

            if (!$isDescendant) {
                $currentFolder = $folder;
            }

            $folders = Folder::where('parent_id', $currentFolder->id)->get();
            $files = File::where('folder_id', $currentFolder->id)->get();

            // Breadcrumb logic relative to shared root
            $breadcrumbs = [];
            $temp = $currentFolder;
            while ($temp && $temp->id != $folder->id) {
                array_unshift($breadcrumbs, $temp);
                $temp = $temp->parent;
            }
            // Add the root folder itself at the front
            array_unshift($breadcrumbs, $folder);

            $viewPath = $isMobile ? 'mobile.shares.folder' : 'web.shares.folder';
            return view($viewPath, compact('shareLink', 'folder', 'currentFolder', 'folders', 'files', 'breadcrumbs'));
        }

        abort(404);
    }

    public function verify(Request $request, $token)
    {
        $shareLink = ShareLink::where('token', $token)->firstOrFail();
        
        $request->validate([
            'password' => 'required|string',
        ]);

        if (Hash::check($request->password, $shareLink->password)) {
            session(["verified_share_$token" => true]);
            return redirect()->route('shares.public', $token);
        }

        return back()->withErrors(['password' => 'Mot de passe incorrect.']);
    }

    public function download(Request $request, $token, File $file)
    {
        $shareLink = ShareLink::where('token', $token)->firstOrFail();

        // Security check expiration & password
        if ($shareLink->expires_at && $shareLink->expires_at->isPast()) {
            abort(403, 'Lien expiré.');
        }
        if ($shareLink->password && !session("verified_share_$token")) {
            abort(403, 'Mot de passe requis.');
        }

        // Verify the file belongs to the shared folder or is the shared file itself
        if ($shareLink->file_id) {
            if ($shareLink->file_id !== $file->id) abort(403);
        } else {
            // It must be inside the shared folder tree
            $tempFolder = $file->folder;
            $isDescendant = false;
            while ($tempFolder) {
                if ($tempFolder->id == $shareLink->folder_id) {
                    $isDescendant = true;
                    break;
                }
                $tempFolder = $tempFolder->parent;
            }
            if (!$isDescendant) abort(403);
        }

        return Storage::disk('local')->download($file->path, $file->name);
    }
}

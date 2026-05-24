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
            'file_id' => 'nullable|required_without:folder_id|exists:files,id',
            'folder_id' => 'nullable|required_without:file_id|exists:folders,id',
            'expires_in_minutes' => 'nullable|integer|min:1',
            'password' => 'nullable|string|min:4',
        ]);

        $fileId = $request->file_id;
        $folderId = $request->folder_id;

        if ($fileId) {
            $file = File::findOrFail($fileId);
            if ($file->user_id !== Auth::id()) abort(403);
        } elseif ($folderId) {
            $folder = Folder::findOrFail($folderId);
            if ($folder->user_id !== Auth::id()) abort(403);
        }

        $expiresAt = null;
        if ($request->filled('expires_in_minutes')) {
            $expiresAt = now()->addMinutes((int)$request->expires_in_minutes);
        }

        $shareLink = ShareLink::create([
            'user_id' => Auth::id(),
            'file_id' => $fileId,
            'folder_id' => $folderId,
            'token' => Str::random(32),
            'expires_at' => $expiresAt,
            'password' => $request->password ? Hash::make($request->password) : null,
        ]);

        $url = route('shares.public', $shareLink->token);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'share_url' => $url,
                'token' => $shareLink->token
            ]);
        }

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
            $viewPath = $this->isMobile($request) ? 'mobile.shares.expired' : 'web.shares.expired';
            return view($viewPath);
        }

        // Increment views count
        $shareLink->increment('views_count');

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

    public function preview(Request $request, $token, File $file)
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

        $path = Storage::disk('local')->path($file->path);
        
        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Content-Type' => $file->mime_type,
            'Content-Disposition' => 'inline; filename="' . $file->name . '"'
        ]);
    }
}

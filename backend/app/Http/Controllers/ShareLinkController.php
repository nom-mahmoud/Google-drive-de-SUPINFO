<?php

namespace App\Http\Controllers;

use App\Models\ShareLink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ShareLinkController extends Controller
{
    public function index(Request $request)
    {
        $links = ShareLink::whereHas('file', function ($query) use ($request) {
            $query->where('user_id', $request->user()->id);
        })->orWhereHas('folder', function ($query) use ($request) {
            $query->where('user_id', $request->user()->id);
        })->with(['file', 'folder'])->get();

        return response()->json($links);
    }

    public function store(Request $request)
    {
        $request->validate([
            'file_id' => 'nullable|exists:files,id',
            'folder_id' => 'nullable|exists:folders,id',
            'expires_at' => 'nullable|date|after:now',
            'password' => 'nullable|string|min:4'
        ]);

        if (!$request->file_id && !$request->folder_id) {
            return response()->json(['message' => 'File or Folder must be specified'], 422);
        }

        $token = Str::random(32);
        
        $shareLink = ShareLink::create([
            'token' => $token,
            'file_id' => $request->file_id,
            'folder_id' => $request->folder_id,
            'expires_at' => $request->expires_at,
            'password_hash' => $request->password ? Hash::make($request->password) : null,
        ]);

        return response()->json($shareLink, 201);
    }

    public function destroy(Request $request, ShareLink $shareLink)
    {
        $shareLink->delete();
        return response()->json(['message' => 'Link revoked']);
    }

    // Public Endpoint
    public function access(Request $request, $token)
    {
        $link = ShareLink::where('token', $token)->where('is_active', true)->firstOrFail();

        if ($link->expires_at && $link->expires_at->isPast()) {
            return response()->json(['message' => 'Link expired'], 410);
        }

        if ($link->password_hash && !Hash::check($request->header('X-Share-Password'), $link->password_hash)) {
            return response()->json(['message' => 'Password required or incorrect'], 403);
        }

        if ($link->file_id) {
            return response()->json(['type' => 'file', 'item' => $link->file]);
        }
        
        if ($link->folder_id) {
            return response()->json(['type' => 'folder', 'item' => $link->folder]);
        }
        
        return response()->json(['message' => 'Not found'], 404);
    }
}

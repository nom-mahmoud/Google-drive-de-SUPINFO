<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->query('q');
        $type = $request->query('type');
        $userId = $request->user()->id;

        if (!$query && !$type) {
            return response()->json(['files' => [], 'folders' => []]);
        }

        $filesQuery = File::where('user_id', $userId);
        
        if ($query) {
            $filesQuery->where('original_name', 'ilike', "%{$query}%");
        }
        if ($type) {
            $filesQuery->where('mime_type', 'ilike', "%{$type}%");
        }

        $folders = Folder::where('user_id', $userId)->where('name', 'ilike', "%{$query}%")->get();

        return response()->json([
            'files' => $filesQuery->get(),
            'folders' => $folders,
        ]);
    }
}

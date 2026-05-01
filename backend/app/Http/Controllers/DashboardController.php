<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        
        // This query parses MIME types like "image/png" and extracts "image" for grouping
        $storageUsage = \Illuminate\Support\Facades\DB::table('files')
            ->where('user_id', $userId)
            ->selectRaw("SPLIT_PART(mime_type, '/', 1) as type, SUM(size) as total_size")
            ->groupBy('type')
            ->get();

        $totalSize = File::where('user_id', $userId)->sum('size');
        
        $recentFiles = File::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'quota' => [
                'used' => $totalSize,
                'max' => 30 * 1024 * 1024 * 1024, // 30 GB
            ],
            'storage_by_type' => $storageUsage,
            'recent_files' => $recentFiles
        ]);
    }
}

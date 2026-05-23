<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use App\Models\ShareLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private function isMobile(Request $request)
    {
        return preg_match('/Mobile|Android|BlackBerry|iPhone|Windows Phone/i', $request->header('User-Agent'));
    }

    public function index(Request $request)
    {
        $userId = Auth::id();

        // Count items
        $foldersCount = Folder::where('user_id', $userId)->count();
        $filesCount = File::where('user_id', $userId)->count();
        $sharesCount = ShareLink::where('user_id', $userId)->count();
        $trashCount = File::onlyTrashed()->where('user_id', $userId)->count() + Folder::onlyTrashed()->where('user_id', $userId)->count();

        // Calculate storage
        $totalBytesUsed = File::where('user_id', $userId)->sum('size');
        $quotaMaxBytes = 30 * 1024 * 1024 * 1024; // 30 GB
        $quotaPercentage = min(100, round(($totalBytesUsed / $quotaMaxBytes) * 100, 2));

        // Format space used
        $spaceUsedFormatted = $this->formatBytes($totalBytesUsed);
        $quotaMaxFormatted = $this->formatBytes($quotaMaxBytes);

        // Recent items
        $recentFiles = File::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        // Breakdown by file types
        $imagesCount = File::where('user_id', $userId)->where('mime_type', 'like', 'image/%')->count();
        $pdfsCount = File::where('user_id', $userId)->where('mime_type', 'application/pdf')->count();
        $docsCount = File::where('user_id', $userId)
            ->where(function($query) {
                $query->where('mime_type', 'like', '%word%')
                      ->orWhere('mime_type', 'like', '%excel%')
                      ->orWhere('mime_type', 'like', '%powerpoint%')
                      ->orWhere('mime_type', 'like', '%text%')
                      ->orWhere('mime_type', 'application/msword')
                      ->orWhere('mime_type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            })->count();
        
        $othersCount = $filesCount - ($imagesCount + $pdfsCount + $docsCount);

        $viewPath = $this->isMobile($request) ? 'mobile.dashboard' : 'web.dashboard';

        return view($viewPath, compact(
            'foldersCount',
            'filesCount',
            'sharesCount',
            'trashCount',
            'totalBytesUsed',
            'quotaMaxBytes',
            'quotaPercentage',
            'spaceUsedFormatted',
            'quotaMaxFormatted',
            'recentFiles',
            'imagesCount',
            'pdfsCount',
            'docsCount',
            'othersCount'
        ));
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'Ko', 'Mo', 'Go', 'To'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

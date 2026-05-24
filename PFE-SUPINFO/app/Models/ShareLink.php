<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'file_id',
        'folder_id',
        'token',
        'expires_at',
        'password',
        'views_count',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }
}

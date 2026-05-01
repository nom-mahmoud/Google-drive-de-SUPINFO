<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'file_id',
        'folder_id',
        'expires_at',
        'password_hash',
        'is_active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'workspace_id',
        'folder_id',
        'uploaded_by_user_id',
        'original_name',
        'display_name',
        'file_path',
        'file_size',
        'mime_type',
        'extension',
        'description',
        'tags',
        'is_starred',
        'status',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_starred' => 'boolean',
        'file_size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the workspace that owns the file.
     */
    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    /**
     * Get the folder that contains the file.
     */
    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    /**
     * Get the user who uploaded the file.
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }

    /**
     * Scope for active files only.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for starred files.
     */
    public function scopeStarred($query)
    {
        return $query->where('is_starred', true);
    }

    /**
     * Scope for recent files.
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope for search by name or description.
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('original_name', 'like', "%{$term}%")
              ->orWhere('display_name', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%");
        });
    }

    /**
     * Scope for filtering by file type.
     */
    public function scopeOfType($query, $type)
    {
        $mimeTypes = [
            'documents' => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain'],
            'images' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'],
            'video' => ['video/mp4', 'video/mpeg', 'video/quicktime', 'video/x-msvideo'],
            'music' => ['audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/mp3'],
        ];

        if (isset($mimeTypes[$type])) {
            return $query->whereIn('mime_type', $mimeTypes[$type]);
        }

        return $query;
    }

    /**
     * Get formatted file size.
     */
    public function getFormattedSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get file icon based on extension.
     */
    public function getFileIconAttribute()
    {
        $icons = [
            'pdf' => 'ri-file-pdf-line',
            'doc' => 'ri-file-word-line',
            'docx' => 'ri-file-word-line',
            'xls' => 'ri-file-excel-line',
            'xlsx' => 'ri-file-excel-line',
            'ppt' => 'ri-file-ppt-line',
            'pptx' => 'ri-file-ppt-line',
            'jpg' => 'ri-image-line',
            'jpeg' => 'ri-image-line',
            'png' => 'ri-image-line',
            'gif' => 'ri-image-line',
            'mp4' => 'ri-video-line',
            'avi' => 'ri-video-line',
            'mp3' => 'ri-music-line',
            'wav' => 'ri-music-line',
            'zip' => 'ri-file-zip-line',
            'rar' => 'ri-file-zip-line',
        ];

        return $icons[$this->extension] ?? 'ri-file-text-line';
    }
}

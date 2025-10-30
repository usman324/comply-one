<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Folder extends Model
{
    use HasFactory;

    protected $fillable = [
        'workspace_id',
        'parent_folder_id',
        'created_by_user_id',
        'name',
        'description',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the workspace that owns the folder.
     */
    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    /**
     * Get the parent folder.
     */
    public function parentFolder()
    {
        return $this->belongsTo(Folder::class, 'parent_folder_id');
    }

    /**
     * Get the child folders.
     */
    public function childFolders()
    {
        return $this->hasMany(Folder::class, 'parent_folder_id');
    }

    /**
     * Get all files in this folder.
     */
    public function files()
    {
        return $this->hasMany(File::class);
    }

    /**
     * Get the user who created the folder.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Get folder size (sum of all files).
     */
    public function getFolderSizeAttribute()
    {
        return $this->files()->sum('file_size');
    }

    /**
     * Get total file count.
     */
    public function getFileCountAttribute()
    {
        return $this->files()->count();
    }

    /**
     * Scope for active folders only.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for root folders (no parent).
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_folder_id');
    }

    /**
     * Get breadcrumb path for folder.
     */
    public function getBreadcrumb()
    {
        $breadcrumb = [];
        $folder = $this;

        while ($folder) {
            array_unshift($breadcrumb, [
                'id' => $folder->id,
                'name' => $folder->name,
            ]);
            $folder = $folder->parentFolder;
        }

        return $breadcrumb;
    }
}
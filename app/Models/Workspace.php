<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workspace extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'workspace_number',
        'name',
        'description',
        'type',
        'status',
        'avatar',
        'owner_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Get the owner of the workspace
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the user who created the workspace
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the workspace
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get all questionnaire responses for this workspace
     */
    public function questionnaireResponses()
    {
        return $this->hasMany(QuestionnaireResponse::class);
    }

    /**
     * Get workspace members (if you have workspace members functionality)
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'workspace_members')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    /**
     * Scope to filter by name
     */
    public function scopeByName($query, $name)
    {
        if ($name) {
            return $query->where('name', 'like', '%' . $name . '%');
        }
        return $query;
    }

    /**
     * Scope to filter by type
     */
    public function scopeByType($query, $type)
    {
        if ($type) {
            return $query->where('type', $type);
        }
        return $query;
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    /**
     * Get workspace avatar URL
     */
    public function getAvatarUrl()
    {
        if ($this->avatar) {
            return asset('storage/workspace/' . $this->avatar);
        }
        return asset('images/default-workspace.png');
    }

    /**
     * Get formatted status badge
     */
    public function getStatusBadge()
    {
        return match ($this->status) {
            'active' => '<span class="badge bg-success">Active</span>',
            'inactive' => '<span class="badge bg-danger">Inactive</span>',
            'pending' => '<span class="badge bg-warning">Pending</span>',
            default => '<span class="badge bg-secondary">' . ucfirst($this->status) . '</span>',
        };
    }

    /**
     * Get formatted type badge
     */
    public function getTypeBadge()
    {
        return match ($this->type) {
            'personal' => '<span class="badge bg-info">Personal</span>',
            'team' => '<span class="badge bg-primary">Team</span>',
            'enterprise' => '<span class="badge bg-success">Enterprise</span>',
            default => '<span class="badge bg-secondary">' . ucfirst($this->type) . '</span>',
        };
    }

    /**
     * Get the workspace's number
     */
    public static function generateWorkspaceNumber(): string
    {
        return 'COMPLYONE-' . str_pad(self::count() + 1, 6, '0', STR_PAD_LEFT);
    }
}

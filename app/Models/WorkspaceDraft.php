<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkspaceDraft extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'draft_data',
    ];

    protected $casts = [
        'draft_data' => 'array',
    ];

    /**
     * Get the user that owns the draft
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

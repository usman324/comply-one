<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'questionnaire_id',
        'section_id',
        'question',
        'description',
        'type',
        'options',
        'is_required',
        'order',
        'validation_rules',
        'placeholder',
        'help_text',
        'default_value',
        // 'is_active',
    ];

    protected $casts = [
        'options' => 'array',
        'validation_rules' => 'array',
        'is_required' => 'boolean',
        // 'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Many-to-Many: Question belongs to many Workspaces
     */
    public function workspaces()
    {
        return $this->belongsToMany(Workspace::class, 'workspace_questions')
            ->withPivot(['order', 'is_required', 'workspace_specific_options'])
            ->withTimestamps();
    }

    /**
     * Get workspace-specific settings
     */
    public function getWorkspaceSettings($workspaceId)
    {
        $pivot = $this->workspaces()->where('workspace_id', $workspaceId)->first()?->pivot;
        
        if (!$pivot) return null;
        
        return [
            'order' => $pivot->order,
            'is_required' => $pivot->is_required || $this->is_required,
            'workspace_specific_options' => json_decode($pivot->workspace_specific_options, true),
        ];
    }

    /**
     * Check if question is assigned to a workspace
     */
    public function isAssignedTo($workspaceId)
    {
        return $this->workspaces()->where('workspace_id', $workspaceId)->exists();
    }

    /**
     * Get count of workspaces using this question
     */
    public function getWorkspaceCount()
    {
        return $this->workspaces()->count();
    }

    /**
     * Questionnaire relationship
     */
    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /**
     * Section relationship
     */
    public function section()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /**
     * Responses relationship
     */
    public function responses()
    {
        return $this->hasMany(QuestionnaireResponse::class);
    }

    /**
     * Get responses for a specific workspace
     */
    public function getWorkspaceResponses($workspaceId)
    {
        return $this->responses()->where('workspace_id', $workspaceId)->get();
    }

    /**
     * Get response statistics
     */
    public function getResponseStats($workspaceId = null)
    {
        $query = $this->responses();
        
        if ($workspaceId) {
            $query->where('workspace_id', $workspaceId);
        }
        
        $responses = $query->get();
        
        return [
            'total' => $responses->count(),
            'unique_answers' => $responses->pluck('answer')->unique()->count(),
            'completion_rate' => $this->getCompletionRate($workspaceId),
        ];
    }

    /**
     * Get completion rate
     */
    public function getCompletionRate($workspaceId = null)
    {
        if ($workspaceId) {
            $workspace = Workspace::find($workspaceId);
            if (!$workspace) return 0;
            
            $totalWorkspaces = 1;
            $answered = $this->responses()->where('workspace_id', $workspaceId)->exists() ? 1 : 0;
        } else {
            $totalWorkspaces = $this->workspaces()->count();
            $answeredWorkspaces = $this->responses()->distinct('workspace_id')->count('workspace_id');
            $answered = $answeredWorkspaces;
        }
        
        return $totalWorkspaces > 0 ? round(($answered / $totalWorkspaces) * 100, 2) : 0;
    }

    /**
     * Get options as array (for select, radio, checkbox types)
     */
    public function getOptionsArray()
    {
        if (is_string($this->options)) {
            return json_decode($this->options, true) ?: [];
        }
        
        return $this->options ?: [];
    }

    /**
     * Check if question has options
     */
    public function hasOptions()
    {
        return in_array($this->type, ['select', 'radio', 'checkbox', 'multiselect']);
    }

    /**
     * Get validation rules as array
     */
    public function getValidationRulesArray()
    {
        if (is_string($this->validation_rules)) {
            return json_decode($this->validation_rules, true) ?: [];
        }
        
        return $this->validation_rules ?: [];
    }

    /**
     * Generate HTML input based on question type
     */
    public function renderInput($name = null, $value = null, $workspaceId = null)
    {
        $name = $name ?: "question_{$this->id}";
        $required = $this->is_required ? 'required' : '';
        $placeholder = $this->placeholder ?: $this->question;
        
        // Get workspace-specific settings if applicable
        if ($workspaceId) {
            $settings = $this->getWorkspaceSettings($workspaceId);
            if ($settings && $settings['is_required']) {
                $required = 'required';
            }
        }

        switch ($this->type) {
            case 'text':
                return "<input type='text' class='form-control' name='{$name}' placeholder='{$placeholder}' value='{$value}' {$required}>";
            
            case 'textarea':
                return "<textarea class='form-control' name='{$name}' rows='3' placeholder='{$placeholder}' {$required}>{$value}</textarea>";
            
            case 'number':
                return "<input type='number' class='form-control' name='{$name}' placeholder='{$placeholder}' value='{$value}' {$required}>";
            
            case 'email':
                return "<input type='email' class='form-control' name='{$name}' placeholder='{$placeholder}' value='{$value}' {$required}>";
            
            case 'date':
                return "<input type='date' class='form-control' name='{$name}' value='{$value}' {$required}>";
            
            case 'select':
                $html = "<select class='form-select' name='{$name}' {$required}>";
                $html .= "<option value=''>Select an option</option>";
                foreach ($this->getOptionsArray() as $option) {
                    $selected = $value == $option ? 'selected' : '';
                    $html .= "<option value='{$option}' {$selected}>{$option}</option>";
                }
                $html .= "</select>";
                return $html;
            
            case 'radio':
                $html = "<div class='radio-group'>";
                foreach ($this->getOptionsArray() as $option) {
                    $checked = $value == $option ? 'checked' : '';
                    $html .= "<div class='form-check'>";
                    $html .= "<input class='form-check-input' type='radio' name='{$name}' value='{$option}' {$checked} {$required}>";
                    $html .= "<label class='form-check-label'>{$option}</label>";
                    $html .= "</div>";
                }
                $html .= "</div>";
                return $html;
            
            case 'checkbox':
                $values = is_array($value) ? $value : [];
                $html = "<div class='checkbox-group'>";
                foreach ($this->getOptionsArray() as $option) {
                    $checked = in_array($option, $values) ? 'checked' : '';
                    $html .= "<div class='form-check'>";
                    $html .= "<input class='form-check-input' type='checkbox' name='{$name}[]' value='{$option}' {$checked}>";
                    $html .= "<label class='form-check-label'>{$option}</label>";
                    $html .= "</div>";
                }
                $html .= "</div>";
                return $html;
            
            default:
                return "<input type='text' class='form-control' name='{$name}' value='{$value}' {$required}>";
        }
    }

    /**
     * Scope: Active questions
     */
    // public function scopeActive($query)
    // {
    //     return $query->where('is_active', true);
    // }

    /**
     * Scope: Required questions
     */
    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    /**
     * Scope: By type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: By section
     */
    public function scopeInSection($query, $sectionId)
    {
        return $query->where('section_id', $sectionId);
    }

    /**
     * Scope: Ordered
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}

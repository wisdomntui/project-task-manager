<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'project_id',
        'priority',
    ];

    /**
     * Returns project that a task belongs to
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}

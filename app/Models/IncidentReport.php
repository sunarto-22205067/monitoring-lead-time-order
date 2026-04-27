<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IncidentReport extends Model
{
    protected $fillable = [
        'title',
        'description',
        'happened_at',
        'location',
        'work_unit',
        'priority',
        'status',
        'created_by',
        'assigned_to',
    ];

    protected $casts = [
        'happened_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(IncidentAttachment::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(IncidentLog::class);
    }
}

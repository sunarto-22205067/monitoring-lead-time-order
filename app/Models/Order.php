<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'project_name',
        'design_type',
        'status_project',
        'category',
        'program_type',
        'program_year',
        'regional',
        'witel',
        'datel',
        'sto',
        'wok',
        'mitra_name',
        'surveyor',
        'household_count',
        'odp_plan',
        'odp_realization',
        'port_plan',
        'port_realization',
        'alpro_data',
        'capex_per_port',
        'po_value',
        'gr_value',
        'ir_value',
        'estimated_go_live',
        'actual_go_live',
        'start_date',
        'finish_installation_date',
        'duration_automation',
        'duration_review',
        'duration_approval',
        'duration_go_live',
        'description',
        'user_id',
    ];

    protected $casts = [
        'estimated_go_live'       => 'date',
        'actual_go_live'          => 'date',
        'start_date'              => 'date',
        'finish_installation_date'=> 'date',
        'capex_per_port'          => 'decimal:2',
        'po_value'                => 'decimal:2',
        'gr_value'                => 'decimal:2',
        'ir_value'                => 'decimal:2',
        'household_count'         => 'integer',
        'odp_plan'                => 'integer',
        'odp_realization'         => 'integer',
        'port_plan'               => 'integer',
        'port_realization'        => 'integer',
        'duration_automation'     => 'integer',
        'duration_review'         => 'integer',
        'duration_approval'       => 'integer',
        'duration_go_live'        => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getOdpProgressAttribute(): float
    {
        if ($this->odp_plan <= 0) return 0;
        return round(($this->odp_realization / $this->odp_plan) * 100, 1);
    }

    public function getPortProgressAttribute(): float
    {
        if ($this->port_plan <= 0) return 0;
        return round(($this->port_realization / $this->port_plan) * 100, 1);
    }

    public function scopeActive($query)
    {
        return $query->where('status_project', 'active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status_project', 'completed');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status_project', 'overdue');
    }
}

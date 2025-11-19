<?php
// app/Models/AnnualBudget.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnnualBudget extends Model
{
    use SoftDeletes;

    protected $table = 'annual_budget';
    protected $primaryKey = 'budget_id';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';

    protected $fillable = [
        'tahun',
        'budget',
    ];

    protected $casts = [
        'budget' => 'decimal:2',
    ];

    public function tors()
    {
        return $this->hasMany(Tor::class, 'budget_id', 'budget_id');
    }

    /**
     * Get remaining budget
     */
    public function getRemainingBudget()
    {
        $usedBudget = $this->tors()
            ->where('status', 'approved_by_head')
            ->sum('budget_submitted');

        return $this->budget - $usedBudget;
    }

    /**
     * Get budget usage percentage
     */
    public function getBudgetUsagePercentage()
    {
        $usedBudget = $this->tors()
            ->where('status', 'approved_by_head')
            ->sum('budget_submitted');

        return ($usedBudget / $this->budget) * 100;
    }
}

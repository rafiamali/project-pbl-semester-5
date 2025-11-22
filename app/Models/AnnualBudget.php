<?php
// app/Models/AnnualBudget.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $budget_id
 * @property string $tahun
 * @property numeric $budget
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tor> $tors
 * @property-read int|null $tors_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnnualBudget newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnnualBudget newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnnualBudget onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnnualBudget query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnnualBudget whereBudget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnnualBudget whereBudgetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnnualBudget whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnnualBudget whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnnualBudget whereTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnnualBudget whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnnualBudget withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnnualBudget withoutTrashed()
 * @mixin \Eloquent
 */
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

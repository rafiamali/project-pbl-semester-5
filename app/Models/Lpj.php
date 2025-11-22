<?php
// app/Models/Lpj.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $lpj_id
 * @property string $activity_result
 * @property string $activity_evaluation
 * @property numeric $budget_used
 * @property string $status
 * @property string $current_stage
 * @property \Illuminate\Support\Carbon $sub_date
 * @property string $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $tor_id
 * @property int|null $user_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LpjApprov> $approvals
 * @property-read int|null $approvals_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attachment> $attachments
 * @property-read int|null $attachments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StatusHist> $statusHistories
 * @property-read int|null $status_histories_count
 * @property-read \App\Models\Tor|null $tor
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpj newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpj newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpj onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpj query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpj whereActivityEvaluation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpj whereActivityResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpj whereBudgetUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpj whereCurrentStage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpj whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpj whereLpjId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpj whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpj whereSubDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpj whereTorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpj whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpj whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpj withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpj withoutTrashed()
 * @mixin \Eloquent
 */
class Lpj extends Model
{
    use SoftDeletes;

    protected $table = 'lpj';
    protected $primaryKey = 'lpj_id';

    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';
    public $timestamps = false;

    protected $fillable = [
        'tor_id',
        'user_id',
        'activity_result',
        'activity_evaluation',
        'budget_used',
        'status',
        'current_stage',
    ];

    protected $casts = [
        'sub_date' => 'datetime',
        'budget_used' => 'decimal:2',
    ];

    // Relationships
    public function tor()
    {
        return $this->belongsTo(Tor::class, 'tor_id', 'tor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'lpj_id', 'lpj_id');
    }

    public function statusHistories()
    {
        return $this->hasMany(StatusHist::class, 'lpj_id', 'lpj_id');
    }

    public function approvals()
    {
        return $this->hasMany(LpjApprov::class, 'lpj_id', 'lpj_id');
    }

    /**
     * Submit LPJ
     */
    public function submit($userId)
    {
        $this->status = 'submitted';
        $this->current_stage = 'submitted';
        $this->save();

        $this->addStatusHistory('submitted', 'LPJ submitted for approval', $userId);
    }

    /**
     * Add status history
     */
    public function addStatusHistory($status, $catatan = null, $userId = null)
    {
        StatusHist::create([
            'lpj_id' => $this->lpj_id,
            'tor_id' => $this->tor_id,
            'user_id' => $userId,
            'status' => $status,
            'catatan' => $catatan,
        ]);
    }

    /**
     * Add approval record
     */
    public function addApproval($userId, $roleId, $action, $catatan = null)
    {
        LpjApprov::create([
            'lpj_id' => $this->lpj_id,
            'user_id' => $userId,
            'role_id' => $roleId,
            'status' => $this->status,
            'action' => $action,
            'catatan' => $catatan,
        ]);
    }

    /**
     * Compare budget
     */
    public function compareBudget()
    {
        $tor = $this->tor;
        $difference = $tor->budget_submitted - $this->budget_used;
        $percentage = ($this->budget_used / $tor->budget_submitted) * 100;

        return [
            'budget_submitted' => $tor->budget_submitted,
            'budget_used' => $this->budget_used,
            'difference' => $difference,
            'percentage' => round($percentage, 2),
        ];
    }

    /**
     * Approve by secretary
     */
    public function approveBySecretary($userId, $roleId, $catatan = null)
    {
        $this->status = 'reviewed_by_secretary';
        $this->current_stage = 'reviewed_by_secretary';
        $this->save();

        $this->addStatusHistory('reviewed_by_secretary', $catatan, $userId);
        $this->addApproval($userId, $roleId, 'approved', $catatan);
    }

    /**
     * Verify by admin
     */
    public function verifyByAdmin($userId, $roleId, $catatan = null)
    {
        $this->status = 'verified_by_admin';
        $this->current_stage = 'verified_by_admin';
        $this->save();

        $this->addStatusHistory('verified_by_admin', $catatan, $userId);
        $this->addApproval($userId, $roleId, 'approved', $catatan);
    }

    /**
     * Approve by head
     */
    public function approveByHead($userId, $roleId, $catatan = null)
    {
        $this->status = 'approved_by_head';
        $this->current_stage = 'approved_by_head';
        $this->save();

        $this->addStatusHistory('approved_by_head', $catatan, $userId);
        $this->addApproval($userId, $roleId, 'approved', $catatan);
    }

    /**
     * Reject LPJ
     */
    public function reject($userId, $roleId, $catatan)
    {
        $this->status = 'rejected';
        $this->current_stage = 'rejected';
        $this->save();

        $this->addStatusHistory('rejected', $catatan, $userId);
        $this->addApproval($userId, $roleId, 'rejected', $catatan);
    }

    /**
     * Request revision
     */
    public function requestRevision($userId, $roleId, $catatan)
    {
        $this->status = 'needs_revision';
        $this->current_stage = 'needs_revision';
        $this->save();

        $this->addStatusHistory('needs_revision', $catatan, $userId);
        $this->addApproval($userId, $roleId, 'request_revision', $catatan);
    }
}

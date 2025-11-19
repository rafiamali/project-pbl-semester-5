<?php
// app/Models/Tor.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tor extends Model
{
    use SoftDeletes;

    protected $table = 'tor';
    protected $primaryKey = 'tor_id';

    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';
    public $timestamps = false;

    protected $fillable = [
        'activity_name',
        'activity_background',
        'activity_purpose',
        'participant',
        'start_date',
        'end_date',
        'budget_submitted',
        'pic',
        'status',
        'current_stage',
        'category_id',
        'user_id',
        'budget_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'sub_date' => 'datetime',
        'budget_submitted' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(ActivityCategory::class, 'category_id', 'category_id');
    }

    public function annualBudget()
    {
        return $this->belongsTo(AnnualBudget::class, 'budget_id', 'budget_id');
    }

    public function lpj()
    {
        return $this->hasOne(Lpj::class, 'tor_id', 'tor_id');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'tor_id', 'tor_id');
    }

    public function statusHistories()
    {
        return $this->hasMany(StatusHist::class, 'tor_id', 'tor_id');
    }

    public function approvals()
    {
        return $this->hasMany(TorApprov::class, 'tor_id', 'tor_id');
    }

    /**
     * Submit TOR
     */
    public function submit($userId)
    {
        $this->status = 'submitted';
        $this->current_stage = 'submitted';
        $this->save();

        $this->addStatusHistory('submitted', 'TOR submitted for approval', $userId);
    }

    /**
     * Add status history
     */
    public function addStatusHistory($status, $catatan = null, $userId = null)
    {
        StatusHist::create([
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
        TorApprov::create([
            'tor_id' => $this->tor_id,
            'user_id' => $userId,
            'role_id' => $roleId,
            'status' => $this->status,
            'action' => $action,
            'catatan' => $catatan,
        ]);
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
     * Reject TOR
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

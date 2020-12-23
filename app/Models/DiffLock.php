<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Diff;
use App\Models\Member;

class DiffLock extends Model
{
    use HasFactory;

    protected $fillable = ['member_id', 'diff_id'];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            Member::class,
            'id',
            'id',
            'member_id',
            'user_id'
        );
    }

    public function diff()
    {
        return $this->belongsTo(Diff::class, 'diff_id');
    }
}

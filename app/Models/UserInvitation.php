<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Diff;
use App\Models\Member;

class UserInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'invited_partner_id',
        'author_id',
        'diff_id'
    ];

    /**
     * 招待した相手
     */
    public function invitedPartnerUser()
    {
        return $this->belongsTo(User::class, 'invited_partner_id');
    }

    
    /**
     * 招待先Diff
     */
    public function diff()
    {
        return $this->belongsTo(Diff::class, 'diff_id');
    }

    /**
     * 招待作成者
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * 招待の取り下げ
     */
    public function cancel()
    {
        $this->delete();
    }

  
}

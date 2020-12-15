<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Diff;

class Member extends Model
{
    use HasFactory;

    /**
     * dbのカラム user_id references id on users;
     */
    public function user()
    {

    }

    /**
     * dbのカラム diff_id references id on diffs;
     */
    public function diff()
    {
        
    }
}

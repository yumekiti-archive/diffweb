<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Diff extends Model
{
    use HasFactory;

    protected $fillable = ['source_text', 'compared_text', 'title'];

    public function lockedUser(){
        return $this->belongsTo(User::class, 'updating_user_id', 'id');
    }

    public function members(){
        return $this->belongsToMany(User::class, 'members', 'diff_id', 'user_id');
    }
}

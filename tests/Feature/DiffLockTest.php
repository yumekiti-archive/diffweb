<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Diff;

class DiffLockTest extends TestCase
{
    public $diff;
    public $me;
    public $member;

    public function setUp(){
        parent::setUp();


    }
}

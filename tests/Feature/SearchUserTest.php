<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use PHPUnit\Framework\Assert;

use App\Models\Diff;
use App\Models\Member;


class SearchUserTest extends TestCase
{
    use RefreshDatabase;
    
    public $diff;

    public function setUp(): void
    {
        parent::setUp();

        // 無関係なユーザー
        User::factory()->count(25)->create();

        Diff::factory()->count(10)->create();
        Diff::get()->each(function($diff){
            $diff->members()->saveMany(User::factory()->count(10)->create());
        });

        // １：１なユーザーとDiff
        $users = User::factory()->count(10)->create();
        $users->each(function($user){
            $user->diffs()->save(Diff::factory()->make());
        });


        // 累計ユーザー数 135
        // Diff数 10 + 10 = 20
        
    }

    public function testテストデータのユーザー数チェック()
    {
        Assert::assertEquals(135, User::count());
    }

    public function testテストデータのDiff数チェック()
    {
        Assert::assertEquals(20, Diff::count());
    }
}

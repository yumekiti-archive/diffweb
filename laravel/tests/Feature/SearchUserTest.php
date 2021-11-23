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
        // 非所属メンバ 25
        
    }

    public function testテストデータのユーザー数チェック()
    {
        Assert::assertEquals(135, User::count());
    }

    public function testテストデータのDiff数チェック()
    {
        Assert::assertEquals(20, Diff::count());
    }

   

    public function testDiff以外のメンバーとどこにも属していないメンバーの取得()
    {
        $diff = Diff::withCount('members')->orderBy('members_count', 'desc')->first();
        Assert::assertNotNull($diff);

        $count = User::notDiffMembers($diff)->count();
        Assert::assertEquals(135 - $diff->members()->count(), $count);
        
    }

    public function testDiff以外のメンバーのLIKE句を利用した検索()
    {
        $diff = Diff::withCount('members')->orderBy('members_count', 'desc')->first();
        Assert::assertNotNull($diff);

        User::factory()->create([
            'user_name' => 'hogehogepiyo'
        ]);
        $name = 'piyopiyohogehoge';
        $user = User::factory()->make(['user_name' => $name ]);
        $diff->members()->save($user);
        $user->diffs()->save(Diff::factory()->make());
        $count = User::notDiffMembers($diff)->where('user_name', 'like', "%piyo%")->count();
        Assert::assertEquals(1, $count);
    }
}

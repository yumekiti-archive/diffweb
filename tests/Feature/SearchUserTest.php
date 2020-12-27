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

        $this->seed();
        $this->diff = Diff::withCount('members')->orderBy('members_count')->first();

    }

    public function testSetUpが動いているか()
    {
        Assert::assertNotNull($this->diff);
    }

    public function testLeftJoinが機能しているのか()
    {

        $leftJoinCount = User::leftJoin('members', 'users.id', '=', 'members.id')->count();

        Assert::assertEquals($leftJoinCount,  User::count());
    }

    public function testLeftJoinから未所属のユーザーを正常に取得できるか()
    {
        $notMemberCount = User::leftJoin('members', 'users.id', '=', 'members.id')->where('members.diff_id', '<>', $this->diff->id)->count();
        $notAnyMemberCount = User::leftJoin('members', 'users.id', '=', 'members.id')->whereNull('members.id')->count();

        $diffMemberCount = $this->diff->members()->count();

        Assert::assertEquals(User::count(), $notMemberCount + $notAnyMemberCount + $diffMemberCount);
        
    }

}

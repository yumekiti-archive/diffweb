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

    public function test連続したクエリの配列の中身は同じであるか()
    {



        $notMembers = User::leftJoin('members', 'users.id', '=', 'members.id')->where('members.diff_id', '<>', $this->diff->id)->get();
        $notAnyMembers = User::leftJoin('members', 'users.id', '=', 'members.id')->whereNull('members.id')->get();
        $beforeJoinCount =  $notMembers->count() + $notAnyMembers->count();

        $merged = $notMembers->merge($notAnyMembers);
        $array1 = [];
        foreach($merged as $item){
            $array1[$item->id] = $item;
        }
        $diff = $this->diff;

        $notAnyMembersOrNotMember = User::leftJoin('members', 'users.id', '=', 'members.id')->where(function($query) use ($diff){
            $query->whereNull('members.id')
            ->orWhere('members.diff_id', '<>', $diff->id);
        })->get();

        Assert::assertEquals($merged->count(), $notAnyMembersOrNotMember->count());
        Assert::assertNotEmpty($array1);
        foreach($notAnyMembersOrNotMember as $user){
            unset($array1[$user->id]);
        }

        Assert::assertEmpty($array1);

        


    }

    public function test連続したクエリは有効か()
    {
        $notMemberCount = User::leftJoin('members', 'users.id', '=', 'members.id')->where('members.diff_id', '<>', $this->diff->id)->count();
        $notAnyMemberCount = User::leftJoin('members', 'users.id', '=', 'members.id')->whereNull('members.id')->count();

        $diffMemberCount = $this->diff->members()->count();

        Assert::assertEquals(User::count(), $notMemberCount + $notAnyMemberCount + $diffMemberCount);

        $count = $notMemberCount + $notAnyMemberCount + $diffMemberCount;

        $diff = $this->diff;
        $notAnyMemberCount = User::leftJoin('members', 'users.id', '=', 'members.id')->where(function($query) use ($diff){
            $query->whereNull('members.id')
            ->orWhere('members.diff_id', '<>', $diff->id);
        })->count();
        $diffMemberCount = $this->diff->members()->count();
        
        Assert::assertEquals($count, $notAnyMemberCount + $diffMemberCount);
    }

    public function test未所属のユーザーを正常に取得できるか()
    {
        $diff = $this->diff;
        $notAnyMemberCount = User::leftJoin('members', 'users.id', '=', 'members.id')->where(function($query) use ($diff){
            $query->whereNull('members.id')
            ->orWhere('members.diff_id', '<>', $diff->id);
        })->count();
        $diffMemberCount = $this->diff->members()->count();
        
        Assert::assertEquals(User::count(), $notAnyMemberCount + $diffMemberCount);

    }

}

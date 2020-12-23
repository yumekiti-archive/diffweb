<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\UserInvitation;
use App\Models\User;
use App\Models\Diff;
use PHPUnit\Framework\Assert;


class InvitationTest extends TestCase
{
    
    use RefreshDatabase;


    

    /**
     * 招待の発行をテストします。
     */
    public function testCreateInvitation()
    {
        $user = User::factory()->create();
        $diff = $user->diffs()->save(Diff::factory()->make());
        $partner = User::factory()->create();
        $invitation = $diff->invitations()->save(new UserInvitation([
            'author_id' => $user->id,
            'invited_partner_id' => $partner->id
        ]));
        Assert::assertNotNull($invitation);
    }

    /**
     * 招待の発行と取得をテストします。
     */
    public function testCreateAndGetInvitation(){
        $user = User::factory()->create();

        $diff = $user->diffs()->save(Diff::factory()->make());

        $partner = User::factory()->create();
        $invitation = $diff->invitations()->create([
            'author_id' => $user->id,
            'invited_partner_id' => $partner->id
        ]);
        Assert::assertEquals($invitation->id, $partner->invitationsToMe()->findOrFail($invitation->id)->id);

        
        Assert::assertEquals($invitation->id, $diff->invitations()->findOrFail($invitation->id)->id);

    }


    /**
     * 招待の発行をテストします。
     */
    public function testInvite()
    {
        $me = User::factory()->create();
        $diff = Diff::factory()->make();
        $me->diffs()->save($diff);
        $partner = User::factory()->create();

        Assert::assertNotNull($diff->invite($me, $partner));
    }

    /**
     * メンバーに対して招待を発行した時にエラーが発生することをテストします。
     * @test
     * @expectedException App\Exceptions\InvitedUserMemberedException
     */
    public function testInvalidMemberedFail()
    {
        $this->expectException(\App\Exceptions\InvitedUserMemberedException::class);
        $me = User::factory()->create();
        $diff = Diff::factory()->make();
        $me->diffs()->save($diff);
        $member = User::factory()->make();
        $diff->members()->save($member);

        $diff->invite($me, $member);
    }

    /**
     * 招待済みのユーザーに対して招待を発行した時にエラーが発生することをテストします。
     * @test
     * @exceptedException App\Exceptions\InvitedUserInvitedException
     */
    public function testInvalidInvitedFail()
    {
        $this->expectException(\App\Exceptions\InvitedUserInvitedException::class);
        $me = User::factory()->create();
        $diff = Diff::factory()->make();
        $me->diffs()->save($diff);
        $invited = User::factory()->create();
        $diff->invite($me, $invited);

        $diff->invite($me, $invited);
    }

    /**
     * 招待承認テスト
     */
    public function testAcceptInvitation()
    {
        $me = User::factory()->create();
        $diff = Diff::factory()->create();
        $member = User::factory()->create();
        $diff->members()->attach($member);

        $userInvitation = $diff->invite($member, $me);

        $userInvitation->accept();

        Assert::assertNotNull($diff->members()->find($me->id));

    }

    /**
     * 招待拒否テスト
     */
    public function testRejectInvitation()
    {
        $me = User::factory()->create();
        $diff= Diff::factory()->create();
        $member = User::factory()->create();
        $diff->members()->attach($member);
        $userInvitation = $diff->invite($member, $me);
        $userInvitation->reject();
        Assert::assertNull($diff->invitations()->where('invited_partner_id', $me->id)->first());

    }

    /**
     * 招待キャンセルテスト
     */
    public function testCancelInvitation()
    {
        $me = User::factory()->create();
        $diff = Diff::factory()->create();
        $member = User::factory()->create();
        $diff->members()->attach($member);
        $userInvitation = $diff->invite($member, $me);
        $userInvitation->cancel();
        Assert::assertNull($diff->invitations()->where('invited_partner_id', $me->id)->first());
    }
}

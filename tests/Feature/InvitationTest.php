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


}

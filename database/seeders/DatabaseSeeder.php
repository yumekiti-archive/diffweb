<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Diff;
use App\Models\UserInvitation;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // テストユーザー
        $testUser = User::create([
            'email' => 'test@test.jp',
            'user_name' => 'test',
            'password' => Hash::make('testtest'),
            'name' => 'てすと太郎',
        ]);
        $testUser->diffs()->saveMany(Diff::factory()->count(10)->make());
        $lockedUser = User::create([
            'email' => 'testlock@test.jp',
            'user_name' => 'testlock',
            'password' => Hash::make('testtest'),
            'name' => 'ろっく太郎',
        ]);

        $testUser->diffs()->get()->each(function($diff) use ($lockedUser){
            $diff->members()->saveMany(User::factory()->count( $diff->id % 5 + 1)->create());
            if( $diff->id %  7 === 0){
                $diff->members()->attach($lockedUser);
                $diff->save();

                $diff->lock($lockedUser);
                $diff->save();
            }
        });


        // 招待用データ作成
        $diff = Diff::factory()->make();
        $testUser->diffs()->save($diff);

        $invitationPartner = User::create([
            'email' => 'testinvite@test.jp',
            'user_name' => 'testinvite',
            'password' => Hash::make('testtest'),
            'name' => '招待され太郎',
        ]);

        $invitation = new UserInvitation([
            'invited_partner_id' => $invitationPartner->id,
            'author_id' => $testUser->id,
        ]);
        $diff->invitations()->save($invitation);
        

        
    }
}

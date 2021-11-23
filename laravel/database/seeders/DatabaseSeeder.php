<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Diff;
use App\Models\UserInvitation;
use Illuminate\Support\Facades\Hash;
use App\Enums\Authority;


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

        User::factory()->count(100)->create();

        User::factory()->count(40)->create()->each(function ($u) use ($testUser){
            $testUser->diffs()->first()->addMember($u, new Authority(Authority::READ_ONLY));
        });

        $testUser->diffs()->get()->each(function($diff) use ($lockedUser){
            User::factory()->count(40)->create()->each(function($user) use ($diff){
                $diff->addMember($user, new Authority(Authority::READ_ONLY));
            });
            
            for($i = 0; $i < 20; $i++){
                $diff->invite($diff->members()->with(['user'])->inRandomOrder()->first()->user, User::factory()->create(), new Authority(Authority::READ_AND_WRITE));
            }
            if( $diff->id %  3 === 0){
                $diff->addMember($lockedUser);
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

        Diff::factory()->count(30)->create()->each(function(Diff $diff) use ($invitationPartner){
            $user = User::factory()->create();
            $diff->addMember($user);
            $diff->invite($user, $invitationPartner, new Authority(Authority::READ_ONLY));
            $diff->save();
        });
                
    }
}

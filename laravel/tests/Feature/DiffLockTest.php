<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Diff;
use App\Exceptions\InvalidAccessException;
use PHPUnit\Framework\Assert;

class DiffLockTest extends TestCase
{
    use RefreshDatabase;

    public $diff;
    public $me;
    public $member;

    public function setUp(): void
    {
        parent::setUp();

        $this->diff = Diff::factory()->create();
        $this->me = User::factory()->create();
        $this->member = User::factory()->create();
        $this->diff->members()->attach($this->me);
        $this->diff->members()->attach($this->member);
    }

    /**
     * ロックできるかテスト
     */
    public function testLock()
    {
        $this->diff->lock($this->me);
        Assert::assertNotNull($this->diff->locked()->first());
    }

    /**
     * ロック解除
     */
    public function testUnlock()
    {
        $this->diff->lock($this->me);
        $this->diff->unlock($this->me);
        Assert::assertNull($this->diff->locked()->first());
    }

    /**
     * ロック競合
     */
    public function testConflictLock()
    {
        $this->diff->lock($this->me);
        Assert::assertFalse($this->diff->lock($this->member));
        Assert::assertEquals($this->diff->locked()->first()->member()->first()->user_id, $this->me->id);

    }

    /**
     * ロックしているユーザー以外のメンバーのロック解除
     */
    public function testUnlockOtherMember()
    {
        $this->diff->lock($this->me);
        Assert::assertFalse($this->diff->unlock($this->member));
        Assert::assertEquals($this->diff->locked()->first()->user()->first()->id, $this->me->id);
    }

    /**
     * メンバー以外のロック例外テスト
     */
    public function testLockByInvalidMemberFail()
    {
        $this->expectException(\App\Exceptions\InvalidAccessException::class);
        $this->diff->lock(User::factory()->create());

    }

    /**
     * メンバー以外のアンロック例外テスト
     */
    public function testUnlockByInvalidMemberFail()
    {
        $this->expectException(\App\Exceptions\InvalidAccessException::class);
        $this->diff->lock($this->me);
        $this->diff->unlock(User::factory()->create());

    }


}

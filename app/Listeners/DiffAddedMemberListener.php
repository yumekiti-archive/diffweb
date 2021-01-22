<?php

namespace App\Listeners;

use App\Events\DiffAddedMember;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DiffAddedMemberListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  DiffAddedMember  $event
     * @return void
     */
    public function handle(DiffAddedMember $event)
    {
        //
        $diff = $event->member->diff()->first();
        $count = $diff->members()->count();
        if($count == 1){
            $res = $diff->lock($event->member->user()->first());
        }else{
            \Log::debug('メンバー数は１以上');
        }
    }
}

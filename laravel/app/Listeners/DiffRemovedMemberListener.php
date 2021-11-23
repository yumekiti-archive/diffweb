<?php

namespace App\Listeners;

use App\Events\DiffRemovedMember;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DiffRemovedMemberListener
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
     * @param  DiffRemovedMember  $event
     * @return void
     */
    public function handle(DiffRemovedMember $event)
    {
        //
        $diff = $event->member->diff()->first();
        $count = $diff->members()->count();
        if($count == 1){
            $diff->lock($diff->members()->first()->user()->first());
        }
    }
}

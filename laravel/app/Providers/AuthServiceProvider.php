<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Diff;
use App\Enums\Authority;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
        Gate::define('diff-admin', function($user, Diff $diff){
            return $diff->findMemberByUser($user)->first()->authority->value <= Authority::ADMIN;            
        });

        Gate::define('diff-read-and-write', function($user, Diff $diff){
            return $diff->findMemberByUser($user)->first()->authority->value <= Authority::READ_AND_WRITE;
        });

        Gate::define('diff-read-only', function($user, Diff $diff){
            return $diff->findMemberByUser($user)->fisrt()->authority->value <= Authority::READ_ONLY;
        });
    }
}

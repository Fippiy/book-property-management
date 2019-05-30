<?php

namespace App\Providers;

use App\Property;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(
            'components.user_nav', function($view) {
                $view->with([
                    'name' => Auth::user()->name,
                    'user_book_count'=> Property::userGetBook()->total(),
                    ]);
            }
        );
    }
}

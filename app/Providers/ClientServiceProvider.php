<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use View;


class ClientServiceProvider extends ServiceProvider {

    public function boot()
    {
//        require_once(__DIR__.'/routes.php');
//        require_once(__DIR__.'/apiRoutes.php');
//        View::addNamespace('Client', __DIR__.'/Views/');

        //Register individual event subscribers

//        $this->app->events->subscribe(new Events\Mailer\Client());


    }

    public function register()
    {
        //Repository Bindings

        $this->app->bind(
            'App\Repo\ClientTaskRepositoryInterface',
            'App\Repo\Eloquent\ClientTaskRepository'
        );

    }

}

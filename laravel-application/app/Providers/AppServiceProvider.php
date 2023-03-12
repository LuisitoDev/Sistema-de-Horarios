<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Laravel WhereHas() and With()
        //https://dev.to/othmane_nemli/laravel-wherehas-and-with-550o
        Builder::macro('withWhereHas', function ($relation, $constraint) {
                return $this->whereHas($relation, $constraint)->with($relation, $constraint);
            }
        );
    }
}

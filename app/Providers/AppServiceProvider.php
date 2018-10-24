<?php

namespace App\Providers;

use App\Models\Topic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //设置laravel默认字符串最大长度 1000bytes/4
        Schema::defaultStringLength(250);
        //将侧边栏的视图数据注入
        $topics = Topic::all();
        View::composer('layout._sidebar', function ($view) {
           $topics = Topic::all();
           $view->with('topics', $topics);
        });

        DB::listen(function ($query){
            $sql = $query->sql;
            $bindings = $query->bindings;
            $time = $query->time;
            Log::debug(var_export(compact('sql', 'bindings', 'time'), true));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

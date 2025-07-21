<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Course;
use App\Models\Product;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            // View::share adds data (variables) that are shared through all views
            View::share('categories', Category::all());
        } catch (\Exception $e) {
            // No need to do anything – this just ensures that no exception is
            // thrown if "products" table does not exist
        }
    }
}

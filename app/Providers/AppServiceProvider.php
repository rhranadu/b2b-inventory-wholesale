<?php

namespace App\Providers;

use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'product_brands' => 'App\ProductBrand',
            'vendors' => 'App\Vendor',
            'product_categories' => 'App\ProductCategory',
            'products' => 'App\Product',
        ]);
        Builder::defaultStringLength(191); // Update defaultStringLength

        // Using view composer to set following variables globally
        view()->composer('*',function($view) {
            if (Auth::check()) {
                $oparator_role = 'Operator';
                $account_role = 'Account';
                $sale = true;
                // $oparator_role = in_array('Operator', auth()->user()->roles->pluck('name')->toArray());
                // $account_role = in_array('Account', auth()->user()->roles->pluck('name')->toArray());
                // $sale = false;
                // $sale_role = in_array('Sale', auth()->user()->roles->pluck('name')->toArray());
                // if (Auth::user()->warehouse_type_name === 'show-room') {
                //     $sale = true;
                // } else {
                //     $sale = false;
                // }
                $view->with('oparator_role', $oparator_role);
                $view->with('account_role', $account_role);
                $view->with('sale', $sale);
            }
        });
    }
}

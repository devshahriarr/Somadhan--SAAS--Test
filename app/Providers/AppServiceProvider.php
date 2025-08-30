<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\Stock;
use App\Models\Variation;
use App\Observers\ProductObserver;
use App\Observers\StockObserver;
use App\Observers\VariationObserver;
use App\Repositories\RepositoryClasses\BankRepository;
use App\Repositories\RepositoryClasses\BranchRepository;
use App\Repositories\RepositoryClasses\BrandRepository;
use App\Repositories\RepositoryClasses\CategoryRepository;
use App\Repositories\RepositoryClasses\CustomerRepository;
use App\Repositories\RepositoryClasses\DamageRepository;
use App\Repositories\RepositoryClasses\EmployeeRepository;
use App\Repositories\RepositoryClasses\SubCategoryRepository;
use App\Repositories\RepositoryInterfaces\BankInterface;
use App\Repositories\RepositoryInterfaces\BranchInterface;
use App\Repositories\RepositoryInterfaces\BrandInterface;
use App\Repositories\RepositoryInterfaces\CategoryInterface;
use App\Repositories\RepositoryInterfaces\CustomerInterfaces;
use App\Repositories\RepositoryInterfaces\DamageInterface;
use App\Repositories\RepositoryInterfaces\EmployeeInterface;
use App\Repositories\RepositoryInterfaces\SubCategoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CategoryInterface::class, CategoryRepository::class);
        $this->app->bind(BrandInterface::class, BrandRepository::class);
        $this->app->bind(SubCategoryInterface::class, SubCategoryRepository::class);
        $this->app->bind(BankInterface::class, BankRepository::class);
        $this->app->bind(BranchInterface::class, BranchRepository::class);
        $this->app->bind(CustomerInterfaces::class, CustomerRepository::class);
        $this->app->bind(EmployeeInterface::class, EmployeeRepository::class);
        $this->app->bind(DamageInterface::class, DamageRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Product::observe(ProductObserver::class);
        Variation::observe(VariationObserver::class);
        Stock::observe(StockObserver::class);

        if (env('DB_LOGGING', false)) {
            \DB::listen(function ($query) {
                \Log::info($query->sql, $query->bindings);
            });
        }

        \DB::listen(function ($query) {
            \Log::info('SQL: ' . $query->sql . ' Bindings: ' . json_encode($query->bindings));
        });
    }
    // public function boot()
    // {
    //     \App\Models\Product::observe(\App\Observers\ProductObserver::class);
    //     \App\Models\Variation::observe(\App\Observers\VariationObserver::class);
    // }
}

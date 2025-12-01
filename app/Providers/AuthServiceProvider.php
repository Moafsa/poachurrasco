<?php

namespace App\Providers;

use App\Models\Establishment;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Recipe;
use App\Models\Service;
use App\Models\Video;
use App\Policies\EstablishmentPolicy;
use App\Policies\ProductPolicy;
use App\Policies\PromotionPolicy;
use App\Policies\RecipePolicy;
use App\Policies\ServicePolicy;
use App\Policies\VideoPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Establishment::class => EstablishmentPolicy::class,
        Product::class => ProductPolicy::class,
        Promotion::class => PromotionPolicy::class,
        Service::class => ServicePolicy::class,
        Recipe::class => RecipePolicy::class,
        Video::class => VideoPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            return method_exists($user, 'isAdmin') && $user->isAdmin() ? true : null;
        });
    }
}













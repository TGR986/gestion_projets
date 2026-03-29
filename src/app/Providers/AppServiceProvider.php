<?php

namespace App\Providers;

use App\Models\Projet;
use App\Models\ProjetEtape;
use App\Models\Document;
use App\Policies\ProjetPolicy;
use App\Policies\EtapePolicy;
use App\Policies\DocumentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Projet::class => ProjetPolicy::class,
        ProjetEtape::class => EtapePolicy::class,
        Document::class => DocumentPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
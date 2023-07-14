<?php

namespace App\Providers;

use App\Interfaces\AdminInterface;
use App\Interfaces\DocumentationInterface;
use App\Interfaces\Employee_projectInterface;
use App\Interfaces\EmployeeInterface;
use App\Interfaces\ProjectInterface;
use App\Repositories\AdminRepository;
use App\Repositories\DocumentationRepository;
use App\Repositories\Employee_projectRepositories;
use Illuminate\Support\ServiceProvider;
use App\Repositories\EmployeeRepository;
use App\Repositories\ProjectRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AdminInterface::class, AdminRepository::class);
        $this->app->bind(EmployeeInterface::class, EmployeeRepository::class);
        $this->app->bind(ProjectInterface::class, ProjectRepository::class);
        $this->app->bind(Employee_projectInterface::class, Employee_projectRepositories::class);
        $this->app->bind(DocumentationInterface::class, DocumentationRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

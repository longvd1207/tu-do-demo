<?php

namespace App\Providers;

use App\Repositories\Area\AreaRepository;
use App\Repositories\Area\AreaRepositoryInterface;
use App\Repositories\Company\CompanyRepository;
use App\Repositories\Company\CompanyRepositoryInterface;
use App\Repositories\Customer\CustomerRepository;
use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Repositories\PaymentStatus\PaymentStatusRepository;
use App\Repositories\PaymentStatus\PaymentStatusRepositoryInterface;

use App\Repositories\DeviceConfig\DeviceConfigRepository;
use App\Repositories\DeviceConfig\DeviceConfigRepositoryInterface;
use App\Repositories\FunSpot\FunSpotRepository;
use App\Repositories\FunSpot\FunSpotRepositoryInterface;
use App\Repositories\Map\MapRepository;
use App\Repositories\Map\MapRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\Event\EventRepositoryInterface;
use App\Repositories\Service\ServiceRepository;
use App\Repositories\Service\ServiceRepositoryInterface;
use App\Repositories\Ticket\TicketRepositoryInterface;
use App\Repositories\Ticket\TicketRepository;
use App\Repositories\TicketType\TicketTypeRepository;
use App\Repositories\TicketType\TicketTypeRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;


use App\Repositories\Role\RoleRepository;
use App\Repositories\Role\RoleRepositoryInterface;

use App\Repositories\Permission\PermissionRepository;
use App\Repositories\Permission\PermissionRepositoryInterface;

use App\Repositories\Log\LogRepository;
use App\Repositories\Log\LogRepositoryInterface;

use App\Repositories\BaseRepository;
use App\Repositories\BaseRepositoryInterface;

use App\Repositories\Event\EventRepository;
use App\Repositories\MailHistory\MailHistoryRepository;
use App\Repositories\MailHistory\MailHistoryRepositoryInterface;
use App\Repositories\Order\OrderRepository;
use App\Repositories\WarningEvent\WarningEventRepository;
use App\Repositories\WarningEvent\WarningEventRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton(
            BaseRepository::class,
            BaseRepositoryInterface::class
        );

        $this->app->singleton(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        $this->app->singleton(
            RoleRepositoryInterface::class,
            RoleRepository::class
        );

        $this->app->singleton(
            PermissionRepositoryInterface::class,
            PermissionRepository::class
        );


        $this->app->singleton(
            DeviceConfigRepositoryInterface::class,
            DeviceConfigRepository::class
        );

        $this->app->singleton(
            MapRepositoryInterface::class,
            MapRepository::class
        );

        $this->app->singleton(
            ServiceRepositoryInterface::class,
            ServiceRepository::class
        );

        $this->app->singleton(
            PaymentStatusRepositoryInterface::class,
            PaymentStatusRepository::class
        );

        $this->app->singleton(
            EventRepositoryInterface::class,
            EventRepository::class
        );

        $this->app->singleton(
            OrderRepositoryInterface::class,
            OrderRepository::class
        );

        $this->app->singleton(
            FunSpotRepositoryInterface::class,
            FunSpotRepository::class
        );

        $this->app->singleton(
            TicketTypeRepositoryInterface::class,
            TicketTypeRepository::class
        );

        $this->app->singleton(
            TicketRepositoryInterface::class,
            TicketRepository::class
        );

        $this->app->singleton(
            AreaRepositoryInterface::class,
            AreaRepository::class
        );


        $this->app->singleton(
            CompanyRepositoryInterface::class,
            CompanyRepository::class
        );

        $this->app->singleton(
            LogRepositoryInterface::class,
            LogRepository::class
        );

        $this->app->singleton(
            CustomerRepositoryInterface::class,
            CustomerRepository::class
        );

        $this->app->singleton(
            MailHistoryRepositoryInterface::class,
            MailHistoryRepository::class
        );

        $this->app->singleton(
            WarningEventRepositoryInterface::class,
            WarningEventRepository::class
        );


    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    }
}

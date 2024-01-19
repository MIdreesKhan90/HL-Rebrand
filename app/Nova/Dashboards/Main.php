<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\OrdersCompleted;
use App\Nova\Metrics\TotalCustomers;
use App\Nova\Metrics\TotalDeliveryBoys;
use App\Nova\Metrics\TotalOrders;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
//            new Help,
            (new TotalCustomers())->defaultRange('ALL')->width('1/2'),
            (new TotalDeliveryBoys())->defaultRange('ALL')->width('1/2'),
            (new TotalOrders())->defaultRange('ALL')->width('1/2'),
            (new OrdersCompleted())->defaultRange('ALL')->width('1/2'),
        ];
    }
}

<?php

namespace VotreNamespace\LaravelLivewireShop\Traits;

use VotreNamespace\LaravelLivewireShop\Models\Order;
use Carbon\Carbon;

trait HasAnalytics
{
    public function getSalesAnalytics($period = 30)
    {
        $startDate = Carbon::now()->subDays($period);
        
        return [
            'total_orders' => Order::where('created_at', '>=', $startDate)->count(),
            'total_revenue' => Order::where('created_at', '>=', $startDate)->sum('total'),
            'average_order_value' => Order::where('created_at', '>=', $startDate)->avg('total'),
            'orders_by_day' => Order::where('created_at', '>=', $startDate)
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(total) as revenue')
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
        ];
    }

    public function getTopSellingProducts($limit = 10)
    {
        return \VotreNamespace\LaravelLivewireShop\Models\Product::withCount(['orderItems as total_sold' => function($query) {
                $query->selectRaw('SUM(quantity)');
            }])
            ->orderBy('total_sold', 'desc')
            ->take($limit)
            ->get();
    }

    public function getCustomerAnalytics()
    {
        return [
            'total_customers' => Order::distinct('billing_address->email')->count(),
            'repeat_customers' => Order::selectRaw('billing_address->>"$.email" as email')
                ->groupBy('email')
                ->havingRaw('COUNT(*) > 1')
                ->count(),
            'average_customer_value' => Order::selectRaw('billing_address->>"$.email" as email, SUM(total) as total')
                ->groupBy('email')
                ->avg('total'),
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Address;
use App\Models\Review;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    /**
     * Display the seller dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $seller = Auth::user();
        
        // Get date range from request or use default (last 7 days)
        $period = $request->input('period', 'last7days');
        $startDate = null;
        $endDate = null;
        
        // Set date range based on selected period
        switch ($period) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                break;
            case 'yesterday':
                $startDate = Carbon::yesterday();
                $endDate = Carbon::yesterday()->endOfDay();
                break;
            case 'last7days':
                $startDate = Carbon::now()->subDays(7)->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                break;
            case 'last30days':
                $startDate = Carbon::now()->subDays(30)->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                break;
            case 'thisMonth':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth(); 
                break;
            case 'lastMonth':
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                break;
            case 'custom':
                $startDate = $request->has('start') ? Carbon::parse($request->input('start')) : now()->subDays(7);
                $endDate = $request->has('end') ? Carbon::parse($request->input('end')) : now();
                break;
        }
        
        if ($startDate->gt($endDate)) {
            $endDate = $startDate->copy()->addDay();
        }
        
        // Get previous period for comparison
        $previousStartDate = (clone $startDate)->subDays($endDate->diffInDays($startDate) + 1);
        $previousEndDate = (clone $startDate)->subDay();
        
        // Get total revenue for current period
        $totalRevenue = $this->getTotalRevenue($seller->id, $startDate, $endDate);
        
        // Get total revenue for previous period for comparison
        $previousTotalRevenue = $this->getTotalRevenue($seller->id, $previousStartDate, $previousEndDate);
        
        // Calculate revenue growth percentage
        $revenueGrowth = $previousTotalRevenue > 0 
            ? (($totalRevenue - $previousTotalRevenue) / $previousTotalRevenue) * 100 
            : 0;
        
        // Get total orders for current period
        $totalOrders = $this->getTotalOrders($seller->id, $startDate, $endDate);
        
        // Get total orders for previous period for comparison
        $previousTotalOrders = $this->getTotalOrders($seller->id, $previousStartDate, $previousEndDate);
        
        // Calculate order growth percentage
        $orderGrowth = $previousTotalOrders > 0 
            ? (($totalOrders - $previousTotalOrders) / $previousTotalOrders) * 100 
            : 0;
        
        // Calculate average order value
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        
        // Calculate previous average order value
        $previousAverageOrderValue = $previousTotalOrders > 0 ? $previousTotalRevenue / $previousTotalOrders : 0;
        
        // Calculate AOV growth percentage
        $aovGrowth = $previousAverageOrderValue > 0 
            ? (($averageOrderValue - $previousAverageOrderValue) / $previousAverageOrderValue) * 100 
            : 0;
        
        // Calculate conversion rate (orders / visits)
        // Assuming you have a visits or page_views table
        $visits = $this->getVisitCount($seller->id, $startDate, $endDate);
        $conversionRate = $visits > 0 ? ($totalOrders / $visits) * 100 : 0;
        
        // Calculate previous conversion rate
        $previousVisits = $this->getVisitCount($seller->id, $previousStartDate, $previousEndDate);
        $previousConversionRate = $previousVisits > 0 ? ($previousTotalOrders / $previousVisits) * 100 : 0;
        
        // Calculate conversion rate growth
        $conversionGrowth = $previousConversionRate > 0 
            ? (($conversionRate - $previousConversionRate) / $previousConversionRate) * 100 
            : 0;
        
        // Get pending orders count
        $pendingOrders = $this->getOrderCountByStatus($seller->id, 'pending');
        
        // Get processing orders count
        $processingOrders = $this->getOrderCountByStatus($seller->id, 'processing');
        
        // Get low stock products count
        $lowStockProducts = $this->getLowStockProductsCount($seller->id);
        
        // Get new reviews count
        $newReviews = $this->getNewReviewsCount($seller->id, $startDate, $endDate);
        
        // Get average rating
        $averageRating = $this->getAverageRating($seller->id);
        
        // Get recent orders
        $recentOrders = $this->getRecentOrders($seller->id, 5);
        
        // Get top products
        $topProducts = $this->getTopProducts($seller->id, $startDate, $endDate, 5);
        
        // Get recent reviews
        $recentReviews = $this->getRecentReviews($seller->id, 5);
        
        // Get sales and revenue data for chart
        $salesRevenueData = $this->getSalesRevenueData($seller->id, $startDate, $endDate);
        
        // Get product performance data for chart
        $productPerformanceData = $this->getProductPerformanceData($seller->id, $startDate, $endDate);
        
        // Get order status data for chart
        $orderStatusData = $this->getOrderStatusData($seller->id);
        
        // Get payment methods data for chart
        $paymentMethodsData = $this->getPaymentMethodsData($seller->id, $startDate, $endDate);
        
        // Get customer demographics data for chart
        $customerDemographicsData = $this->getCustomerDemographicsData($seller->id);
        
        // // Get review ratings data for chart
        $reviewRatingsData = $this->getReviewRatingsData($seller->id);

        
        return view('seller.dashboard', compact(
            'totalRevenue',
            'revenueGrowth',
            'totalOrders',
            'orderGrowth',
            'averageOrderValue',
            'aovGrowth',
            'conversionRate',
            'conversionGrowth',
            'pendingOrders',
            'processingOrders',
            'lowStockProducts',
            'newReviews',
            'averageRating',
            'recentOrders',
            'topProducts',
            'recentReviews',
            'salesRevenueData',
            'productPerformanceData',
            'orderStatusData',
            'paymentMethodsData',
            'customerDemographicsData',
            'reviewRatingsData'
        ));
    }
    
    /**
     * Get chart data for AJAX requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChartData(Request $request)
    {
        $seller = Auth::user();
        $period = $request->input('period', 'daily');
        
        // Set date range based on period
        $startDate = null;
        $endDate = Carbon::now()->endOfDay();
        
        switch ($period) {
            case 'daily':
                $startDate = Carbon::now()->subDays(14)->startOfDay();
                break;
            case 'weekly':
                $startDate = Carbon::now()->subWeeks(12)->startOfWeek();
                break;
            case 'monthly':
                $startDate = Carbon::now()->subMonths(12)->startOfMonth();
                break;
            default:
                $startDate = Carbon::now()->subDays(14)->startOfDay();
        }
        
        // Get sales and revenue data for the selected period
        $salesRevenueData = $this->getSalesRevenueData($seller->id, $startDate, $endDate, $period);
        
        return response()->json($salesRevenueData);
    }
    
    /**
     * Get total revenue for the seller within the given date range.
     *
     * @param  int  $sellerId
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return float
     */
    private function getTotalRevenue($sellerId, $startDate, $endDate)
    {
        return OrderItem::whereHas('product', function ($q) use ($sellerId) {
                $q->where('user_id', $sellerId);
            })
            ->whereHas('order', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                  ->whereNotIn('status', ['cancelled', 'failed']);
            })
            ->sum(DB::raw('order_items.price * order_items.quantity')); 
    }
    
    /**
     * Get total orders for the seller within the given date range.
     *
     * @param  int  $sellerId
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return int
     */
    private function getTotalOrders($sellerId, $startDate, $endDate)
    {
        return Order::whereHas('orderItems.product', function ($q) use ($sellerId) {
                $q->where('user_id', $sellerId);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->count();
    }
    
    /**
     * Get visit count for the seller within the given date range.
     * This is a placeholder - you would need to implement actual visit tracking.
     *
     * @param  int  $sellerId
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return int
     */
    private function getVisitCount($sellerId, $startDate, $endDate)
    {
        // This is a placeholder. In a real application, you would query a visits or analytics table
        // For now, we'll estimate visits as 10x the number of orders
        $orderCount = $this->getTotalOrders($sellerId, $startDate, $endDate);
        return $orderCount * 10;
    }
    
    /**
     * Get order count by status for the seller.
     *
     * @param  int  $sellerId
     * @param  string  $status
     * @return int
     */
    private function getOrderCountByStatus($sellerId, $status)
    {
        return Order::whereHas('orderItems.product', function ($q) use ($sellerId) {
            $q->where('user_id', $sellerId);
        })
        ->where('status', $status) 
        ->count();
    }
    
    /**
     * Get count of products with low stock.
     *
     * @param  int  $sellerId
     * @return int
     */
    private function getLowStockProductsCount($sellerId)
    {
        // Define what "low stock" means - for example, less than 10 items
        $lowStockThreshold = 10;
        
        return Product::where('user_id', $sellerId)
            ->where('stock', '<', $lowStockThreshold)
            ->where('stock', '>', 0) // Only include products that are not out of stock
            ->count();
    }
    
    /**
     * Get count of new reviews within the given date range.
     *
     * @param  int  $sellerId
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return int
     */
    private function getNewReviewsCount($sellerId, $startDate, $endDate)
    {
        return Review::whereHas('product', function ($query) use ($sellerId) {
                $query->where('user_id', $sellerId);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }
    
    /**
     * Get average rating for the seller's products.
     *
     * @param  int  $sellerId
     * @return float
     */
    private function getAverageRating($sellerId)
    {
        return Review::whereHas('product', function ($query) use ($sellerId) {
                $query->where('user_id', $sellerId);
            })
            ->avg('rating') ?? 0;
    }
    
    /**
     * Get recent orders for the seller.
     *
     * @param  int  $sellerId
     * @param  int  $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getRecentOrders($sellerId, $limit)
    {
        return Order::whereHas('orderItems.product', function ($q) use ($sellerId) {
                $q->where('user_id', $sellerId);
            })
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Get top selling products for the seller within the given date range.
     *
     * @param  int  $sellerId
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  int  $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getTopProducts($sellerId, $startDate, $endDate, $limit)
    {
        return Product::where('user_id', $sellerId)
            ->withCount(['orderItems as sold' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('order', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate])
                      ->where('status', '!=', 'cancelled');
                });
            }])
            ->withSum(['orderItems as revenue' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('order', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate])
                      ->where('status', '!=', 'cancelled');
                });
            }], DB::raw('price * quantity'))
            ->with('category', 'photos')
            ->orderBy('sold', 'desc')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Get recent reviews for the seller's products.
     *
     * @param  int  $sellerId
     * @param  int  $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getRecentReviews($sellerId, $limit)
    {
        return Review::whereHas('product', function ($query) use ($sellerId) {
                $query->where('user_id', $sellerId);
            })
            ->with('product', 'user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Get sales and revenue data for chart.
     *
     * @param  int  $sellerId
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  string  $periodType
     * @return array
     */
    private function getSalesRevenueData($sellerId, $startDate, $endDate, $periodType = null)
    {
        // Determine the appropriate grouping based on date range or specified period type
        $diffInDays = $startDate->diffInDays($endDate);
        
        if ($periodType === 'daily' || ($periodType === null && $diffInDays <= 31)) {
            // Daily data for up to a month
            $groupBy = 'date';
            $format = 'Y-m-d';
            $displayFormat = 'd M';
        } elseif ($periodType === 'weekly' || ($periodType === null && $diffInDays <= 92)) {
            // Weekly data for up to 3 months
            $groupBy = 'week';
            $format = 'Y-W';
            $displayFormat = 'W';
        } else {
            // Monthly data for longer periods
            $groupBy = 'month';
            $format = 'Y-m';
            $displayFormat = 'M Y';
        }
        
        // Get sales data
        $salesData = Order::whereHas('orderItems.product', function($q) use ($sellerId) {
            $q->where('user_id', $sellerId);
        })
        ->whereBetween('created_at', [$startDate, $endDate])
        ->where('status', '!=', 'cancelled')
        ->select(
            DB::raw("DATE_FORMAT(created_at, '{$format}') as period"),
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(total_amount) as revenue')
        )
        ->groupBy('period')
        ->orderBy('period')
        ->get();
        
        // Create a complete date range with zeros for missing dates
        $dateRange = [];
        $labels = [];
        $sales = [];
        $revenue = [];
        
        if ($groupBy === 'date') {
            $period = CarbonPeriod::create($startDate, $endDate);
            
            foreach ($period as $date) {
                $formattedDate = $date->format($format);
                $dateRange[$formattedDate] = [
                    'count' => 0,
                    'revenue' => 0
                ];
                $labels[] = $date->format($displayFormat);
            }
        } elseif ($groupBy === 'week') {
            $currentDate = clone $startDate;
            
            while ($currentDate <= $endDate) {
                $weekEnd = (clone $currentDate)->endOfWeek()->min($endDate);
                $formattedDate = $currentDate->format($format);
                $dateRange[$formattedDate] = [
                    'count' => 0,
                    'revenue' => 0
                ];
                $labels[] = 'Week ' . $currentDate->format($displayFormat);
                $currentDate = $weekEnd->addDay();
            }
        } else {
            $currentDate = clone $startDate->startOfMonth();
            
            while ($currentDate <= $endDate) {
                $formattedDate = $currentDate->format($format);
                $dateRange[$formattedDate] = [
                    'count' => 0,
                    'revenue' => 0
                ];
                $labels[] = $currentDate->format($displayFormat);
                $currentDate->addMonth();
            }
        }
        
        // Fill in actual data
        foreach ($salesData as $data) {
            if (isset($dateRange[$data->period])) {
                $dateRange[$data->period]['count'] = $data->count;
                $dateRange[$data->period]['revenue'] = $data->revenue;
            }
        }
        
        // Extract data for charts
        foreach ($dateRange as $data) {
            $sales[] = $data['count'];
            $revenue[] = $data['revenue'];
        }
        
        return [
            'labels' => $labels,
            'sales' => $sales,
            'revenue' => $revenue
        ];
    }
    
    /**
     * Get product performance data for chart.
     *
     * @param  int  $sellerId
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    private function getProductPerformanceData($sellerId, $startDate, $endDate)
    {
        $products = $this->getTopProducts($sellerId, $startDate, $endDate, 5);
        
        $labels = [];
        $salesData = [];
        $revenueData = [];
        $viewsData = [];
        
        foreach ($products as $product) {
            $labels[] = $product->name;
            $salesData[] = $product->sold;
            $revenueData[] = $product->revenue;
            
            // Get product views (placeholder - you would need to implement actual view tracking)
            $viewsData[] = $product->sold * rand(5, 15); // Simulating views as 5-15x sales
        }
        
        return [
            'labels' => $labels,
            'sales' => $salesData,
            'revenue' => $revenueData,
            'views' => $viewsData
        ];
    }
    
    /**
     * Get order status data for chart.
     *
     * @param  int  $sellerId
     * @return array
     */
    private function getOrderStatusData($sellerId)
    {
        $statuses = ['pending', 'processing', 'shipped', 'completed', 'cancelled'];
        $data = [];
        
        foreach ($statuses as $status) {
            $data[] = $this->getOrderCountByStatus($sellerId, $status);
        }
        
        return [
            'labels' => ['Pending', 'Processing', 'Shipped', 'Completed', 'Cancelled'],
            'data' => $data
        ];
    }
    
    /**
     * Get payment methods data for chart.
     *
     * @param  int  $sellerId
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    private function getPaymentMethodsData($sellerId, $startDate, $endDate)
    {
        // Get payment data for the seller within the specified date range
        $paymentData = Payment::whereBetween('created_at', [$startDate, $endDate])
            ->whereHas('transaction.order.orderItems.product', function($query) use ($sellerId) {
                $query->where('user_id', $sellerId);
            })
            ->select('payment_method', DB::raw('COUNT(*) as count'))
            ->groupBy('payment_method')
            ->get();
    
        $labels = [];
        $data = [];
        
        foreach ($paymentData as $payment) {
            $method = ucfirst(str_replace('_', ' ', $payment->payment_method));
            $labels[] = $method;
            $data[] = $payment->count;
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
    
    /**
     * Get customer demographics data for chart.
     *
     * @param  int  $sellerId
     * @return array
     */
    private function getCustomerDemographicsData($sellerId)
    {
        // Query untuk mengambil jumlah alamat berdasarkan tipe untuk seller tertentu
        $addressTypes = ['home', 'office', 'other'];
        $data = [];
    
        foreach ($addressTypes as $type) {
            $count = Address::where('user_id', $sellerId)
                ->where('address_type', $type)
                ->count();
    
            $data[] = $count;
        }
    
        return [
            'labels' => ['Home', 'Office', 'Other'],
            'data' => $data
        ];
    }
    
    
    /**
     * Get review ratings data for chart.
     *
     * @param  int  $sellerId
     * @return array
     */
    private function getReviewRatingsData($sellerId)
    {
        $ratingsData = Review::whereHas('product', function ($query) use ($sellerId) {
                $query->where('user_id', $sellerId);
            })
            ->select('rating', DB::raw('COUNT(*) as count'))
            ->groupBy('rating')
            ->orderBy('rating')
            ->get();
        
        $data = array_fill(0, 5, 0); // Initialize with zeros for ratings 1-5
        
        foreach ($ratingsData as $rating) {
            if ($rating->rating >= 1 && $rating->rating <= 5) {
                $data[$rating->rating - 1] = $rating->count;
            }
        }
        
        return [
            'labels' => ['1 ★', '2 ★', '3 ★', '4 ★', '5 ★'],
            'data' => $data
        ];
    }
}

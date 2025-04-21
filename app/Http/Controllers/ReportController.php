<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Payment;
use App\Models\Store;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesExport;
use App\Exports\ProductsExport;
use App\Exports\CustomersExport;
use App\Exports\PaymentsExport;

class ReportController extends Controller
{
    /**
     * Display the reports page
     */
    public function index(Request $request)
    {
        $seller = Auth::user();
        
        // Get report type and date range
        $reportType = $request->input('type', 'sales');
        $startDate = $request->input('start') ? Carbon::parse($request->input('start')) : Carbon::now()->startOfMonth();
        $endDate = $request->input('end') ? Carbon::parse($request->input('end')) : Carbon::now()->endOfMonth();
        
        // Ensure end date is at the end of the day
        $endDate = $endDate->endOfDay();
        
        // Get sales summary data
        $totalSales = Order::where('user_id', $seller->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
            
        $totalOrders = Order::where('user_id', $seller->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->count();
            
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;
        
        // Get new customers in this period
        $newCustomers = User::whereHas('orders', function ($query) use ($seller, $startDate, $endDate) {
            $query->where('user_id', $seller->id)
                ->whereBetween('created_at', [$startDate, $endDate]);
        })
        ->whereDoesntHave('orders', function ($query) use ($seller, $startDate) {
            $query->where('user_id', $seller->id)
                ->where('created_at', '<', $startDate);
        })
        ->count();
        
        // Prepare chart data
        $chartData = [];
        $chartLabels = [];
        
        // Determine interval based on date range
        $diffInDays = $startDate->diffInDays($endDate);
        
        if ($diffInDays <= 31) {
            // Daily data for up to a month
            $currentDate = clone $startDate;
            
            while ($currentDate <= $endDate) {
                $dailySales = Order::where('user_id', $seller->id)
                    ->whereDate('created_at', $currentDate)
                    ->where('status', '!=', 'cancelled')
                    ->sum('total_amount');
                    
                $chartLabels[] = $currentDate->format('d M');
                $chartData[] = $dailySales;
                
                $currentDate->addDay();
            }
        } else {
            // Weekly data for longer periods
            $currentDate = clone $startDate;
            
            while ($currentDate <= $endDate) {
                $weekEnd = (clone $currentDate)->addDays(6)->min($endDate);
                
                $weeklySales = Order::where('user_id', $seller->id)
                    ->whereBetween('created_at', [$currentDate, $weekEnd->endOfDay()])
                    ->where('status', '!=', 'cancelled')
                    ->sum('total_amount');
                    
                $chartLabels[] = $currentDate->format('d M') . ' - ' . $weekEnd->format('d M');
                $chartData[] = $weeklySales;
                
                $currentDate = $weekEnd->addDay();
            }
        }
        
        // Get report data based on type
        $transactions = null;
        $productReports = null;
        $customerReports = null;
        $paymentReports = null;
        
        switch ($reportType) {
            case 'sales':
                $transactions = Order::where('user_id', $seller->id)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->with(['user', 'payment'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
                break;
                
            case 'products':
                $productReports = Product::where('user_id', $seller->id)
                    ->withCount(['orderItems as sold_count' => function ($query) use ($startDate, $endDate) {
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
                    ->orderBy('sold_count', 'desc')
                    ->get();
                break;
                
            case 'customers':
                $customerReports = User::whereHas('orders', function ($query) use ($seller, $startDate, $endDate) {
                    $query->where('user_id', $seller->id)
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->where('status', '!=', 'cancelled');
                })
                ->withCount(['orders as order_count' => function ($query) use ($seller, $startDate, $endDate) {
                    $query->where('user_id', $seller->id)
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->where('status', '!=', 'cancelled');
                }])
                ->withSum(['orders as total_spent' => function ($query) use ($seller, $startDate, $endDate) {
                    $query->where('user_id', $seller->id)
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->where('status', '!=', 'cancelled');
                }], 'total_amount')
                ->withMax(['orders as last_order_date' => function ($query) use ($seller) {
                    $query->where('user_id', $seller->id)
                        ->where('status', '!=', 'cancelled');
                }], 'created_at')
                ->orderBy('total_spent', 'desc')
                ->get();
                break;
                
            case 'payments':
                $totalPaymentAmount = Payment::whereHas('transaction.order', function ($query) use ($seller, $startDate, $endDate) {
                    $query->where('user_id', $seller->id)
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->where('status', '!=', 'cancelled');
                })->sum('amount');
                
                $paymentReports = Payment::whereHas('transaction.order', function ($query) use ($seller, $startDate, $endDate) {
                    $query->where('user_id', $seller->id)
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->where('status', '!=', 'cancelled');
                })
                ->select('payment_method', 
                    DB::raw('COUNT(*) as transaction_count'), 
                    DB::raw('SUM(amount) as total_amount'))
                ->groupBy('payment_method')
                ->orderBy('total_amount', 'desc')
                ->get();
                
                // Calculate percentages
                foreach ($paymentReports as $payment) {
                    $payment->percentage = $totalPaymentAmount > 0 ? 
                        ($payment->total_amount / $totalPaymentAmount) * 100 : 0;
                }
                break;
        }
        
        return view('seller.reports', compact(
            'reportType',
            'startDate',
            'endDate',
            'totalSales',
            'totalOrders',
            'averageOrderValue',
            'newCustomers',
            'chartLabels',
            'chartData',
            'transactions',
            'productReports',
            'customerReports',
            'paymentReports'
        ));
    }

    /**
     * Export report data to Excel
     */
    public function export(Request $request)
    {
        $seller = Auth::user();
        
        // Get report type and date range
        $reportType = $request->input('type', 'sales');
        $startDate = $request->input('start') ? Carbon::parse($request->input('start')) : Carbon::now()->startOfMonth();
        $endDate = $request->input('end') ? Carbon::parse($request->input('end')) : Carbon::now()->endOfMonth();
        
        // Ensure end date is at the end of the day
        $endDate = $endDate->endOfDay();
        
        // Get store information
        $store = Store::where('user_id', $seller->id)->first();
        $storeName = $store ? $store->name : $seller->name;
        
        // Generate filename
        $filename = 'TeknoShop_' . $storeName . '_' . $reportType . '_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.xlsx';
        
        // Export based on report type
        switch ($reportType) {
            case 'sales':
                return Excel::download(new SalesExport($seller->id, $startDate, $endDate), $filename);
                
            case 'products':
                return Excel::download(new ProductsExport($seller->id, $startDate, $endDate), $filename);
                
            case 'customers':
                return Excel::download(new CustomersExport($seller->id, $startDate, $endDate), $filename);
                
            case 'payments':
                return Excel::download(new PaymentsExport($seller->id, $startDate, $endDate), $filename);
                
            default:
                return back()->with('error', 'Tipe laporan tidak valid');
        }
    }
}

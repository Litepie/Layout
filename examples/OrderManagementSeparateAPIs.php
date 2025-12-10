<?php

/**
 * Example 2: E-commerce Order Management with Separate APIs per Section
 * 
 * Scenario: Complex admin view with heavy, independent datasets.
 * Each section loads from its own API endpoint with different loading strategies.
 * Also demonstrates nested data keys for shared data.
 * 
 * Benefits:
 * - Light data loads immediately
 * - Heavy data loads on-demand (lazy loading)
 * - Independent sections can reload without affecting others
 * - Better performance for large datasets
 * - Supports real-time updates per section
 */

use Litepie\Layout\LayoutBuilder;
use Litepie\Form\Fields\Text;
use Litepie\Form\Fields\Select;
use Litepie\Form\Fields\Date;

class OrderManagementController
{
    /**
     * Get the layout structure (called once, can be cached)
     */
    public function getLayout()
    {
        $layout = LayoutBuilder::create('order', 'management')
            
            // Quick Stats - Load immediately on mount
            ->statsSection('order_stats')
                ->title('Order Statistics')
                ->subtitle('Real-time order metrics')
                ->icon('shopping-cart')
                ->dataUrl('/api/orders/stats') // Separate, light endpoint
                ->dataParams(['period' => 'today'])
                ->loadOnMount(true) // Load immediately
                ->reloadOnChange(false)
                ->columns(4)
                ->addMetric('total_orders', 'Total Orders', [
                    'icon' => 'shopping-bag',
                    'format' => 'number',
                    'show_trend' => true,
                    'color' => 'blue'
                ])
                ->addMetric('pending_orders', 'Pending', [
                    'icon' => 'clock',
                    'format' => 'number',
                    'show_change' => true,
                    'color' => 'orange'
                ])
                ->addMetric('revenue', 'Revenue', [
                    'icon' => 'dollar-sign',
                    'format' => 'currency',
                    'prefix' => '$',
                    'show_trend' => true,
                    'color' => 'green'
                ])
                ->addMetric('avg_order_value', 'Avg Order', [
                    'icon' => 'trending-up',
                    'format' => 'currency',
                    'prefix' => '$',
                    'show_trend' => true,
                    'color' => 'purple'
                ])
                ->addAction('Refresh', '#', ['icon' => 'refresh', 'action' => 'reload'])
            
            // Order Search/Filter Form - Static form, no data loading
            ->formSection('order_filters')
                ->label('Search & Filter Orders')
                ->icon('filter')
                ->columns(4)
                ->collapsible(true)
                ->addFormFields([
                    Text::make('search')
                        ->label('Search')
                        ->placeholder('Order ID, Customer name, Email...'),
                    
                    Select::make('status')
                        ->label('Status')
                        ->options([
                            '' => 'All',
                            'pending' => 'Pending',
                            'processing' => 'Processing',
                            'shipped' => 'Shipped',
                            'delivered' => 'Delivered',
                            'cancelled' => 'Cancelled',
                        ]),
                    
                    Date::make('date_from')
                        ->label('Date From'),
                    
                    Date::make('date_to')
                        ->label('Date To'),
                ])
                ->addAction('Search', '#', ['style' => 'primary', 'action' => 'filter'])
                ->addAction('Reset', '#', ['style' => 'secondary', 'action' => 'reset'])
            
            // Main Orders Table - Heavy data, load immediately with pagination
            ->tableSection('orders_list')
                ->title('Orders')
                ->subtitle('Manage customer orders')
                ->icon('list')
                ->dataUrl('/api/orders') // Separate, heavy endpoint
                ->dataParams([
                    'per_page' => 25,
                    'sort' => '-created_at',
                    'include' => 'customer,items_count'
                ])
                ->loadOnMount(true) // Load immediately
                ->reloadOnChange(true) // Reload when filters change
                ->columns([
                    ['key' => 'order_number', 'label' => 'Order #', 'sortable' => true],
                    ['key' => 'customer_name', 'label' => 'Customer', 'sortable' => true],
                    ['key' => 'items_count', 'label' => 'Items', 'sortable' => true],
                    ['key' => 'total', 'label' => 'Total', 'sortable' => true],
                    ['key' => 'status', 'label' => 'Status', 'sortable' => true],
                    ['key' => 'created_at', 'label' => 'Date', 'sortable' => true],
                    ['key' => 'actions', 'label' => 'Actions', 'sortable' => false],
                ])
                ->searchable(true)
                ->sortable(true)
                ->paginated(true)
                ->perPage(25)
                ->defaultSort('created_at', 'desc')
            
            // Tabs for detailed views - Each tab loads separately
            ->tabsSection('order_details_tabs')
                ->title('Order Details')
                ->position('top')
                
                // Tab 1: Order Items - Load when tab is viewed
                ->addTab('items', 'Items', [], [
                    'icon' => 'package',
                    'component' => 'order-items-table'
                ])
                
                // Tab 2: Customer Info - Load when tab is viewed
                ->addTab('customer', 'Customer', [], [
                    'icon' => 'user',
                    'component' => 'customer-info-card'
                ])
                
                // Tab 3: Payment History - Load when tab is viewed
                ->addTab('payments', 'Payments', [], [
                    'icon' => 'credit-card',
                    'component' => 'payment-history-table'
                ])
                
                // Tab 4: Shipping - Load when tab is viewed
                ->addTab('shipping', 'Shipping', [], [
                    'icon' => 'truck',
                    'component' => 'shipping-tracking'
                ])
                
                // Tab 5: Activity Log - Heavy, lazy load
                ->addTab('activity', 'Activity Log', [], [
                    'icon' => 'activity',
                    'component' => 'activity-log-table'
                ])
                
                ->activeTab('items')
            
            // Order Items Table (inside tab) - Lazy load when tab opens
            ->tableSection('order_items')
                ->title('Order Items')
                ->dataUrl('/api/orders/{order_id}/items') // Separate endpoint
                ->loadOnMount(false) // Don't load initially
                ->reloadOnChange(true) // Reload when order selection changes
                ->columns([
                    ['key' => 'product_name', 'label' => 'Product'],
                    ['key' => 'sku', 'label' => 'SKU'],
                    ['key' => 'quantity', 'label' => 'Qty'],
                    ['key' => 'price', 'label' => 'Price'],
                    ['key' => 'total', 'label' => 'Total'],
                ])
                ->searchable(false)
                ->paginated(false)
            
            // Customer Info Card (inside tab) - Lazy load
            ->cardSection('customer_info')
                ->title('Customer Information')
                ->dataUrl('/api/orders/{order_id}/customer') // Separate endpoint
                ->loadOnMount(false) // Lazy load
                ->reloadOnChange(true)
                ->variant('outlined')
            
            // Payment History Table (inside tab) - Lazy load
            ->tableSection('payment_history')
                ->title('Payment History')
                ->dataUrl('/api/orders/{order_id}/payments') // Separate endpoint
                ->loadOnMount(false)
                ->reloadOnChange(true)
                ->columns([
                    ['key' => 'payment_id', 'label' => 'Payment ID'],
                    ['key' => 'method', 'label' => 'Method'],
                    ['key' => 'amount', 'label' => 'Amount'],
                    ['key' => 'status', 'label' => 'Status'],
                    ['key' => 'date', 'label' => 'Date'],
                ])
                ->paginated(false)
            
            // Shipping Tracking Card (inside tab) - Lazy load
            ->cardSection('shipping_tracking')
                ->title('Shipping Tracking')
                ->dataUrl('/api/orders/{order_id}/shipping') // Separate endpoint
                ->loadOnMount(false)
                ->reloadOnChange(true)
            
            // Activity Log Table (inside tab) - Very heavy, lazy load
            ->tableSection('activity_log')
                ->title('Activity Log')
                ->subtitle('Complete order history')
                ->dataUrl('/api/orders/{order_id}/activity') // Separate, heavy endpoint
                ->dataParams(['per_page' => 50])
                ->loadOnMount(false) // Definitely lazy load this
                ->reloadOnChange(true)
                ->columns([
                    ['key' => 'timestamp', 'label' => 'Time', 'sortable' => true],
                    ['key' => 'user', 'label' => 'User'],
                    ['key' => 'action', 'label' => 'Action'],
                    ['key' => 'description', 'label' => 'Description'],
                    ['key' => 'ip_address', 'label' => 'IP Address'],
                ])
                ->searchable(true)
                ->paginated(true)
                ->perPage(50)
            
            // Recent Customer Orders (accordion) - Load when expanded
            ->accordionSection('customer_orders')
                ->title('Customer\'s Other Orders')
                ->multiple(false)
                ->addItem('other_orders', 'View All Orders', [], [
                    'icon' => 'shopping-bag',
                    'description' => 'All orders from this customer'
                ])
            
            // Customer Orders Table (inside accordion) - Load when expanded
            ->tableSection('customer_all_orders')
                ->dataUrl('/api/customers/{customer_id}/orders')
                ->loadOnMount(false) // Load when accordion expands
                ->columns([
                    ['key' => 'order_number', 'label' => 'Order #'],
                    ['key' => 'date', 'label' => 'Date'],
                    ['key' => 'total', 'label' => 'Total'],
                    ['key' => 'status', 'label' => 'Status'],
                ])
                ->paginated(true)
                ->perPage(10)
            
            ->build();

        return response()->json($layout);
    }

    /**
     * Get order statistics (light, fast)
     */
    public function getOrderStats(Request $request)
    {
        $period = $request->input('period', 'today');
        
        return response()->json([
            'total_orders' => Order::wherePeriod($period)->count(),
            'total_orders_trend' => 15.3,
            'pending_orders' => Order::where('status', 'pending')->count(),
            'pending_orders_change' => -5,
            'revenue' => Order::wherePeriod($period)->sum('total'),
            'revenue_trend' => 23.7,
            'avg_order_value' => Order::wherePeriod($period)->avg('total'),
            'avg_order_value_trend' => 8.2,
        ]);
    }

    /**
     * Get orders list (heavy, paginated)
     */
    public function getOrders(Request $request)
    {
        $query = Order::with(['customer'])
            ->withCount('items');

        // Apply filters from form
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($q) => 
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                  );
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($dateFrom = $request->input('date_from')) {
            $query->where('created_at', '>=', $dateFrom);
        }

        if ($dateTo = $request->input('date_to')) {
            $query->where('created_at', '<=', $dateTo);
        }

        // Apply sorting
        $sortColumn = $request->input('sort', '-created_at');
        $direction = str_starts_with($sortColumn, '-') ? 'desc' : 'asc';
        $column = ltrim($sortColumn, '-');
        $query->orderBy($column, $direction);

        return $query->paginate($request->input('per_page', 25));
    }

    /**
     * Get order items (loaded when tab is opened)
     */
    public function getOrderItems($orderId)
    {
        return OrderItem::where('order_id', $orderId)
            ->with('product')
            ->get()
            ->map(fn($item) => [
                'product_name' => $item->product->name,
                'sku' => $item->product->sku,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'total' => $item->quantity * $item->price,
            ]);
    }

    /**
     * Get customer info (loaded when tab is opened)
     */
    public function getOrderCustomer($orderId)
    {
        $order = Order::with('customer')->findOrFail($orderId);
        
        return [
            'name' => $order->customer->name,
            'email' => $order->customer->email,
            'phone' => $order->customer->phone,
            'address' => $order->customer->address,
            'total_orders' => $order->customer->orders()->count(),
            'lifetime_value' => $order->customer->orders()->sum('total'),
            'member_since' => $order->customer->created_at->format('M d, Y'),
        ];
    }

    /**
     * Get payment history (loaded when tab is opened)
     */
    public function getOrderPayments($orderId)
    {
        return Payment::where('order_id', $orderId)
            ->get()
            ->map(fn($payment) => [
                'payment_id' => $payment->id,
                'method' => $payment->method,
                'amount' => $payment->amount,
                'status' => $payment->status,
                'date' => $payment->created_at->format('M d, Y H:i'),
            ]);
    }

    /**
     * Get shipping info (loaded when tab is opened)
     */
    public function getOrderShipping($orderId)
    {
        $order = Order::with('shipment')->findOrFail($orderId);
        
        return [
            'tracking_number' => $order->shipment->tracking_number,
            'carrier' => $order->shipment->carrier,
            'status' => $order->shipment->status,
            'shipped_date' => $order->shipment->shipped_at,
            'estimated_delivery' => $order->shipment->estimated_delivery,
            'tracking_url' => $order->shipment->tracking_url,
            'tracking_events' => $order->shipment->tracking_events,
        ];
    }

    /**
     * Get activity log (heavy, loaded when tab is opened)
     */
    public function getOrderActivity($orderId, Request $request)
    {
        return ActivityLog::where('order_id', $orderId)
            ->when($request->input('search'), function($q, $search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 50));
    }

    /**
     * Get customer's all orders (loaded when accordion expands)
     */
    public function getCustomerOrders($customerId, Request $request)
    {
        return Order::where('customer_id', $customerId)
            ->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 10));
    }
}

/**
 * Frontend Implementation (React/Vue example)
 */
/*
// 1. Fetch layout (once, cached)
const layout = await fetch('/api/layouts/order/management').then(r => r.json());

// 2. Component load behavior
layout.components.forEach(component => {
  
  // Stats: Load immediately
  if (component.name === 'order_stats' && component.load_on_mount) {
    loadComponentData(component.data_url, component.data_params);
    // GET /api/orders/stats?period=today
  }
  
  // Orders table: Load immediately
  if (component.name === 'orders_list' && component.load_on_mount) {
    loadComponentData(component.data_url, component.data_params);
    // GET /api/orders?per_page=25&sort=-created_at&include=customer,items_count
  }
  
  // Order items: Load when order is selected
  if (component.name === 'order_items' && !component.load_on_mount) {
    onOrderSelected((orderId) => {
      const url = component.data_url.replace('{order_id}', orderId);
      loadComponentData(url);
      // GET /api/orders/123/items
    });
  }
  
  // Tab content: Load when tab is opened
  if (component.name === 'payment_history' && !component.load_on_mount) {
    onTabOpen('payments', (orderId) => {
      const url = component.data_url.replace('{order_id}', orderId);
      loadComponentData(url);
      // GET /api/orders/123/payments
    });
  }
  
  // Activity log: Load when tab is opened (heavy)
  if (component.name === 'activity_log' && !component.load_on_mount) {
    onTabOpen('activity', (orderId) => {
      const url = component.data_url.replace('{order_id}', orderId);
      loadComponentData(url, component.data_params);
      // GET /api/orders/123/activity?per_page=50
    });
  }
  
  // Reload on filter change
  if (component.reload_on_change) {
    onFilterChange((filters) => {
      const params = { ...component.data_params, ...filters };
      loadComponentData(component.data_url, params);
      // GET /api/orders?per_page=25&status=pending&search=...
    });
  }
});

// Result: Different loading strategies
// - Stats + Main table: Loaded immediately
// - Tab content: Loaded on-demand when tabs open
// - Heavy data: Only loaded when needed
// - Filtered data: Reloaded when filters change
*/

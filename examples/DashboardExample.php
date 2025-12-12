<?php

/**
 * Dashboard Example
 *
 * Demonstrates creating a comprehensive dashboard with stats, charts, and tables.
 */

use Litepie\Layout\Facades\Layout;

// Create dashboard layout
$layout = Layout::create('admin-dashboard')
    ->title('Dashboard')
    ->setSharedData([
        'user' => auth()->user(),
        'date' => now()->format('F j, Y'),
    ])

    // Header section with breadcrumbs and welcome message
    ->section('header', function ($section) {
        // Breadcrumb navigation
        $section->breadcrumb('navigation')
            ->addItem('Home', '/')
            ->addItem('Dashboard')
            ->separator('›');

        // Welcome message
        $section->alert('welcome')
            ->content('Welcome back! Here\'s what\'s happening today.')
            ->variant('info')
            ->icon('smile')
            ->dismissible(true);
    })

    // Main content section
    ->section('main', function ($section) {
        // Top stats grid - 4 columns
        $section->grid('stats-grid')
            ->columns(4)
            ->gap('1.5rem')

            // Total Users stat
            ->addComponent(
                $section->stats('total-users')
                    ->title('Total Users')
                    ->icon('users')
                    ->dataUrl('/api/stats/users')
                    ->value(15234)
                    ->change('+12.5%')
                    ->trend('up')
                    ->permissions(['view-users'])
            )

            // Active Sessions stat
            ->addComponent(
                $section->stats('active-sessions')
                    ->title('Active Sessions')
                    ->icon('activity')
                    ->dataUrl('/api/stats/sessions')
                    ->value(892)
                    ->change('+5.2%')
                    ->trend('up')
            )

            // Revenue stat
            ->addComponent(
                $section->stats('revenue')
                    ->title('Total Revenue')
                    ->icon('dollar-sign')
                    ->dataUrl('/api/stats/revenue')
                    ->value(98650)
                    ->prefix('$')
                    ->change('+18.3%')
                    ->trend('up')
                    ->permissions(['view-financials'])
            )

            // Pending Tasks stat
            ->addComponent(
                $section->stats('pending-tasks')
                    ->title('Pending Tasks')
                    ->icon('check-square')
                    ->dataUrl('/api/stats/tasks')
                    ->value(23)
                    ->change('-8.1%')
                    ->trend('down')
            );

        // Main content grid - 2 columns (2/3 left, 1/3 right)
        $section->grid('main-content')
            ->columns(3)
            ->gap('1.5rem')

            // Left column (takes 2 columns) - Charts and tables
            ->addComponent(
                $section->layout('left-column')
                    ->setDeviceConfig('mobile', ['order' => 2])

                    // Sales chart
                    ->section('body')
                    ->chart('sales-chart')
                    ->title('Sales Overview')
                    ->subtitle('Last 30 days')
                    ->chartType('line')
                    ->dataUrl('/api/charts/sales')
                    ->labels(['Week 1', 'Week 2', 'Week 3', 'Week 4'])
                    ->datasets([
                        [
                            'label' => 'This Month',
                            'data' => [12000, 19000, 15000, 22000],
                            'borderColor' => 'rgb(75, 192, 192)',
                        ],
                        [
                            'label' => 'Last Month',
                            'data' => [10000, 15000, 13000, 18000],
                            'borderColor' => 'rgb(255, 99, 132)',
                        ],
                    ])
                    ->options([
                        'responsive' => true,
                        'maintainAspectRatio' => false,
                        'tension' => 0.4,
                    ])
                    ->permissions(['view-analytics'])
                    ->endSection()

                    // Recent orders table
                    ->section('body')
                    ->table('recent-orders')
                    ->title('Recent Orders')
                    ->subtitle('Latest 10 orders')
                    ->dataUrl('/api/orders/recent')
                    ->addColumn('id', 'Order #', [
                        'width' => '100px',
                        'sortable' => true,
                    ])
                    ->addColumn('customer', 'Customer', [
                        'sortable' => true,
                    ])
                    ->addColumn('product', 'Product')
                    ->addColumn('amount', 'Amount', [
                        'sortable' => true,
                    ])
                    ->addColumn('status', 'Status', [
                        'filterable' => true,
                    ])
                    ->addColumn('date', 'Date', [
                        'sortable' => true,
                    ])
                    ->addAction('view', 'View Details', [
                        'icon' => 'eye',
                    ])
                    ->sortable(true)
                    ->paginate(10)
                    ->permissions(['view-orders'])
                    ->endSection()
            )

            // Right column (takes 1 column) - Sidebar content
            ->addComponent(
                $section->layout('right-column')
                    ->setDeviceConfig('mobile', ['order' => 1])

                    // Quick actions card
                    ->section('body')
                    ->card('quick-actions')
                    ->title('Quick Actions')
                    ->icon('zap')
                    ->addAction('new_user', 'Add New User', [
                        'icon' => 'user-plus',
                        'url' => '/admin/users/create',
                        'variant' => 'primary',
                    ])
                    ->addAction('new_product', 'Add Product', [
                        'icon' => 'package',
                        'url' => '/admin/products/create',
                        'variant' => 'secondary',
                    ])
                    ->addAction('view_reports', 'View Reports', [
                        'icon' => 'bar-chart',
                        'url' => '/admin/reports',
                        'variant' => 'secondary',
                    ])
                    ->addAction('settings', 'Settings', [
                        'icon' => 'settings',
                        'url' => '/admin/settings',
                        'variant' => 'secondary',
                    ])
                    ->endSection()

                    // Recent activity timeline
                    ->section('body')
                    ->timeline('activity')
                    ->title('Recent Activity')
                    ->icon('clock')
                    ->dataUrl('/api/activity/recent')
                    ->orientation('vertical')
                    ->addEvent([
                        'title' => 'New user registered',
                        'date' => '5 minutes ago',
                        'description' => 'John Doe created an account',
                        'icon' => 'user-plus',
                    ])
                    ->addEvent([
                        'title' => 'Order completed',
                        'date' => '15 minutes ago',
                        'description' => 'Order #12345 was completed',
                        'icon' => 'check-circle',
                    ])
                    ->addEvent([
                        'title' => 'Payment received',
                        'date' => '1 hour ago',
                        'description' => '$450 received from customer',
                        'icon' => 'dollar-sign',
                    ])
                    ->endSection()

                    // System status card
                    ->section('body')
                    ->card('system-status')
                    ->title('System Status')
                    ->icon('server')
                    ->dataUrl('/api/system/status')
                    ->addField('cpu_usage', 'CPU Usage', '45%')
                    ->addField('memory_usage', 'Memory', '2.4 GB / 8 GB')
                    ->addField('disk_usage', 'Disk Space', '125 GB / 500 GB')
                    ->addField('uptime', 'Uptime', '15 days, 4 hours')
                    ->addAction('details', 'View Details', [
                        'url' => '/admin/system',
                        'icon' => 'external-link',
                    ])
                    ->permissions(['view-system'])
                    ->endSection()
            );
    })

    // Footer section
    ->section('footer', function ($section) {
        $section->text('copyright')
            ->content('© 2025 Your Company. All rights reserved.')
            ->align('center')
            ->meta(['color' => 'muted']);
    })

    // Enable caching for performance
    ->cache()
    ->ttl(900) // 15 minutes
    ->key('dashboard-'.auth()->id())
    ->tags(['dashboard', 'user-'.auth()->id()])

    // Before render hook
    ->beforeRender(function ($layout) {
        \Log::info('Rendering dashboard for user: '.auth()->id());
    })

    // After render hook
    ->afterRender(function ($layout, $output) {
        \Log::info('Dashboard rendered successfully');
    })

    // Resolve authorization
    ->resolveAuthorization(auth()->user());

// Render the layout
return $layout->render();

<?php

/**
 * Advanced Features Usage Examples
 *
 * Demonstrates Phase 1, 2 features and Responsive Layouts
 */

use Litepie\Layout\LayoutBuilder;

// ============================================================================
// PHASE 1: CACHING
// ============================================================================

// Enable caching for improved performance
$builder = LayoutBuilder::create('dashboard', 'view')
    ->cache(true, 3600) // Cache for 1 hour
    ->cacheKey('dashboard_user_'.auth()->id())
    ->sharedDataUrl('/api/dashboard')

    ->statsSection('metrics')
    ->title('Key Metrics')
    ->dataKey('metrics')
    ->useSharedData(true);
$layout = $builder->build()->toArray();

// Check if cached
if ($layout->isCached()) {
    // Will return cached version
    $output = $layout->toArray();
} else {
    // Will generate and cache
    $output = $layout->toArray();
}

// Invalidate cache when data changes
$layout->invalidateCache();

// ============================================================================
// PHASE 1: EVENTS & LIFECYCLE HOOKS
// ============================================================================

$builder = LayoutBuilder::create('user', 'profile')
    ->debug(true)

    ->formSection('profile_form')
    ->title('Edit Profile')

        // Before render hook - transform data
    ->onBeforeRender(function ($data, $section) {
        logger()->info('Rendering section: '.$section->getName());

        return $data;
    })

        // After render hook - post-process
    ->onAfterRender(function ($rendered, $section) {
        $rendered['_rendered_at'] = now()->toIso8601String();

        return $rendered;
    })

        // Data loaded hook - transform API response
    ->onDataLoaded(function ($data, $section) {
        logger()->info('Data loaded for: '.$section->getName());

        return array_map('strtoupper', $data); // Example transform
    })

        // Data error hook - error handling
    ->onDataError(function ($exception, $section) {
        logger()->error('Failed to load data: '.$exception->getMessage());
        // Could notify admin, set fallback, etc.
    });
$layout = $builder->build()->toArray();

// ============================================================================
// PHASE 1: VALIDATION
// ============================================================================

$builder = LayoutBuilder::create('settings', 'edit')

    ->formSection('app_settings')
    ->title('Application Settings')

        // Enable validation
    ->validate(true, strict: false)

        // Add data validation rules
    ->validateData([
        'app_name' => 'required|string|max:255',
        'app_url' => 'required|url',
        'admin_email' => 'required|email',
        'maintenance_mode' => 'boolean',
    ]);
$layout = $builder->build()->toArray();

// Validate configuration
$validation = $layout->assertions()
    ->assertHasSection('app_settings')
    ->assertSectionVisible('app_settings')
    ->assertSectionType('app_settings', 'form');

// ============================================================================
// PHASE 1: INTERNATIONALIZATION (i18n)
// ============================================================================

$builder = LayoutBuilder::create('product', 'view')

    ->textSection('description')
        // Use translation keys
    ->title('product.details.title')
    ->subtitle('product.details.subtitle')

        // Set locale
    ->locale('es')

        // Enable auto-translation
    ->autoTranslate(true)

    ->cardSection('specifications')
    ->title(__('product.specs.title'))
    ->subtitle(__('product.specs.subtitle'));
$layout = $builder->build()->toArray();

// Multi-language support
$languages = ['en', 'es', 'fr', 'de'];
foreach ($languages as $lang) {
    $localizedLayout = LayoutBuilder::create('product', 'view')
        ->textSection('info')
        ->title('product.title')
        ->locale($lang)
        ->autoTranslate(true)
        ->build();
}

// ============================================================================
// PHASE 2: DEBUG MODE
// ============================================================================

$builder = LayoutBuilder::create('complex', 'dashboard')
    ->debug(true) // Enable debug mode
    ->sharedDataUrl('/api/dashboard/data')

    ->statsSection('metrics')
    ->title('Metrics')
    ->useSharedData(true, 'stats')

    ->chartSection('sales')
    ->title('Sales Chart')
    ->dataUrl('/api/charts/sales');
$layout = $builder->build()->toArray();

$output = $layout->toArray();

// Debug output includes:
// - Execution time
// - Memory usage
// - Data queries and duration
// - Events fired
// - Component tree structure
print_r($output['_debug']);
/*
Array (
    [execution_time] => 125.45ms
    [queries] => 2
    [query_count] => 2
    [total_query_time] => 45.2
    [events] => 6
    [memory_usage] => 2.45MB
    [peak_memory] => 3.12MB
)
*/

// ============================================================================
// PHASE 2: TESTING HELPERS
// ============================================================================

// In your PHPUnit tests
// use Litepie\Layout\LayoutBuilder; // Already imported at top

class LayoutTest extends TestCase
{
    public function test_user_dashboard_layout()
    {
        $builder = LayoutBuilder::create('user', 'dashboard')
            ->statsSection('metrics')
            ->title('Key Metrics')
            ->chartSection('activity')
            ->title('Activity Chart')
            ->dataUrl('/api/activity');
$layout = $builder->build()->toArray();

        // Use assertions
        $layout->assertions()
            ->assertHasSection('metrics')
            ->assertHasSection('activity')
            ->assertSectionVisible('metrics')
            ->assertSectionType('activity', 'chart')
            ->assertSectionHasProperty('activity', 'title')
            ->assertSectionProperty('activity', 'title', 'Activity Chart')
            ->assertSectionHasDataUrl('activity', '/api/activity')
            ->assertSectionCount(2);

        // Create snapshot for regression testing
        $snapshot = $layout->snapshot();
        $this->assertMatchesSnapshot($snapshot);
    }
}

// ============================================================================
// PHASE 2: EXPORT/IMPORT
// ============================================================================

// Build a layout
$builder = LayoutBuilder::create('user', 'profile')
    ->sharedDataUrl('/api/users/{id}')

    ->cardSection('header')
    ->title('Profile Header')
    ->useSharedData(true, 'profile.header')

    ->formSection('details')
    ->title('Profile Details')
    ->dataUrl('/api/users/{id}/details');
$layout = $builder->build()->toArray();

// Export to JSON
$json = $layout->toJson(pretty: true);
file_put_contents('layouts/user-profile.json', $json);

// Export to YAML (requires symfony/yaml)
$yaml = $layout->toYaml();
file_put_contents('layouts/user-profile.yaml', $yaml);

// Export with metadata
$exported = $layout->export('json');

// Import from JSON
$importedLayout = LayoutBuilder::importJson($json);

// Import from array
$config = [
    'name' => 'product',
    'mode' => 'view',
    'shared_data_url' => '/api/products/{id}',
    'sections' => [
        [
            'type' => 'card',
            'name' => 'product_info',
            'title' => 'Product Information',
            'use_shared_data' => true,
            'data_key' => 'product',
        ],
        [
            'type' => 'table',
            'name' => 'reviews',
            'title' => 'Customer Reviews',
            'data_url' => '/api/products/{id}/reviews',
        ],
    ],
];

$importedLayout = LayoutBuilder::importArray($config);

// ============================================================================
// PHASE 2: CONDITIONAL LOGIC ENGINE
// ============================================================================

$builder = LayoutBuilder::create('order', 'view')
    ->sharedDataUrl('/api/orders/{id}')

    // Show section only if order status is 'pending'
    ->alertSection('pending_alert')
    ->warning()
    ->message('Order is pending approval')
    ->showWhen('order.status', '==', 'pending')

    // Hide section when order is cancelled
    ->cardSection('shipping_info')
    ->title('Shipping Information')
    ->hideWhen('order.status', '==', 'cancelled')
    ->hideWhen('order.status', '==', 'refunded')

    // Enable payment button only when order is confirmed
    ->formSection('payment')
    ->title('Payment')
    ->enableWhen('order.status', '==', 'confirmed')
    ->enableWhen('order.payment_method', 'not_null', null)

    // Multiple conditions with OR logic
    ->alertSection('urgent')
    ->error()
    ->showWhen('order.priority', '==', 'urgent')
    ->conditionLogic('OR')
    ->showWhen('order.amount', '>', 1000)

    // String expression syntax
    ->cardSection('admin_actions')
    ->title('Admin Actions')
    ->showWhen('user.role == admin')

    // Complex conditions
    ->tableSection('history')
    ->title('Order History')
    ->showWhen([
        'field' => 'user.permissions',
        'operator' => 'contains',
        'value' => 'view_history',
    ]);
$layout = $builder->build()->toArray();

// Evaluate conditions with context
$context = [
    'order' => [
        'status' => 'pending',
        'priority' => 'urgent',
        'amount' => 1500,
        'payment_method' => 'credit_card',
    ],
    'user' => [
        'role' => 'admin',
        'permissions' => ['view_orders', 'view_history'],
    ],
];

// Frontend will evaluate conditions based on data
$output = $layout->toArray();

// ============================================================================
// PHASE 3: RESPONSIVE LAYOUTS
// ============================================================================

$builder = LayoutBuilder::create('products', 'grid')
    ->sharedDataUrl('/api/products')

    // Responsive grid columns
    ->gridSection('product_grid')
    ->title('Products')
    ->columns([
        'xs' => 1,    // 1 column on mobile
        'sm' => 2,    // 2 columns on small tablets
        'md' => 3,    // 3 columns on tablets
        'lg' => 4,    // 4 columns on desktop
        'xl' => 6,    // 6 columns on large screens
    ])
    ->useSharedData(true, 'products')

    // Responsive visibility
    ->textSection('mobile_banner')
    ->title('Mobile Special Offer')
    ->visibleOn(['xs', 'sm']) // Only show on mobile

    ->cardSection('desktop_sidebar')
    ->title('Filters')
    ->hiddenOn(['xs', 'sm']) // Hide on mobile
    ->visibleOn(['md', 'lg', 'xl'])

    // Helper methods
    ->alertSection('desktop_notice')
    ->info()
    ->desktopOnly()

    ->statsSection('mobile_stats')
    ->title('Quick Stats')
    ->mobileOnly()

    ->tableSection('data_table')
    ->title('Product List')
    ->hiddenMobile() // Alternative to desktopOnly

    // Responsive order
    ->cardSection('primary_content')
    ->title('Main Content')
    ->responsiveOrder([
        'xs' => 2,  // Second on mobile
        'md' => 1,  // First on tablet+
    ])

    ->cardSection('sidebar')
    ->title('Sidebar')
    ->responsiveOrder([
        'xs' => 1,  // First on mobile
        'md' => 2,  // Second on tablet+
    ])

    // Target specific device
    ->cardSection('tablet_view')
    ->title('Tablet Optimized')
    ->forDevice('tablet')
    ->tabletOnly();
$layout = $builder->build()->toArray();

// ============================================================================
// REAL-WORLD EXAMPLE: E-commerce with All Features
// ============================================================================

$builder = LayoutBuilder::create('shop', 'product_detail')
    // Enable caching
    ->cache(true, 1800)
    ->cacheKey('product_'.request('id'))

    // Enable debug in development
    ->debug(config('app.debug'))

    // Shared data source
    ->sharedDataUrl('/api/products/{id}')

    // Alerts with conditions
    ->alertSection('low_stock')
    ->warning()
    ->title('Low Stock')
    ->message('Only few items left!')
    ->showWhen('product.stock', '<', 5)
    ->showWhen('product.stock', '>', 0)
    ->conditionLogic('AND')
    ->mobileOnly()

    // Responsive product gallery
    ->mediaSection('gallery')
    ->title('product.gallery.title')
    ->locale(app()->getLocale())
    ->autoTranslate(true)
    ->gallery()
    ->columns([
        'xs' => 1,
        'sm' => 2,
        'md' => 3,
        'lg' => 4,
    ])
    ->useSharedData(true, 'product.images')
    ->onDataLoaded(function ($images) {
        return array_map(fn ($img) => $img + ['optimized' => true], $images);
    })

    // Mobile-only quick actions
    ->badgeSection('mobile_actions')
    ->title('Quick Actions')
    ->mobileOnly()
    ->useSharedData(true, 'product.tags')

    // Desktop-only detailed specs
    ->tableSection('specifications')
    ->title('product.specs.title')
    ->desktopOnly()
    ->useSharedData(true, 'product.specifications')
    ->validate(true)
    ->validateData([
        '*.name' => 'required|string',
        '*.value' => 'required',
    ])

    // Conditional admin panel
    ->cardSection('admin_panel')
    ->title('Admin Controls')
    ->showWhen('user.role', 'in', ['admin', 'manager'])
    ->hiddenMobile()
    ->dataUrl('/api/products/{id}/admin')
    ->onBeforeRender(function ($data) {
        audit_log('admin_panel_accessed', ['product_id' => request('id')]);

        return $data;
    })

    // Review section with events
    ->commentSection('reviews')
    ->title('Customer Reviews')
    ->threaded()
    ->voting()
    ->dataUrl('/api/products/{id}/reviews')
    ->onDataLoaded(function ($reviews) {
        return array_filter($reviews, fn ($r) => $r['approved']);
    })
    ->onDataError(function ($e) {
        logger()->error('Failed to load reviews: '.$e->getMessage());
    });
$layout = $builder->build()->toArray();

// Export layout for version control
file_put_contents(
    'layouts/shop-product-detail.json',
    $layout->export('json')
);

// API response includes all features
$response = [
    'layout' => $layout->toArray(),
    'cached' => $layout->isCached(),
    'debug' => config('app.debug') ? $layout->getDebugOutput() : null,
];

return response()->json($response);

<?php

/**
 * Comprehensive Component Examples
 *
 * Quick reference for all 19 component types in Litepie Layout
 */

use Litepie\Layout\LayoutBuilder;

// ============================================================================
// 1. LIST SECTION - For bullet points, numbered lists, checklists
// ============================================================================

$layout = LayoutBuilder::create('content', 'view')
    ->listSection('features')
    ->title('Key Features')
    ->bullet() // or ->numbered(), ->definition(), ->checklist()
    ->dataUrl('/api/features')
    ->addItem('feature1', null, ['icon' => 'check'])
    ->addItem('feature2', null, ['icon' => 'check'])
    ->build();

// ============================================================================
// 2. TIMELINE SECTION - For chronological events, activity feeds
// ============================================================================

$layout = LayoutBuilder::create('order', 'tracking')
    ->timelineSection('order_timeline')
    ->title('Order Timeline')
    ->vertical() // or ->horizontal()
    ->alternate() // Alternating position
    ->showDates()
    ->dateFormat('relative')
    ->dataUrl('/api/orders/{id}/timeline')
    ->addEvent('placed', ['icon' => 'shopping-cart', 'color' => 'blue'])
    ->addEvent('confirmed', ['icon' => 'check', 'color' => 'green', 'variant' => 'success'])
    ->addEvent('shipped', ['icon' => 'truck', 'color' => 'orange'])
    ->addEvent('delivered', ['icon' => 'home', 'color' => 'green'])
    ->build();

// ============================================================================
// 3. ALERT SECTION - For alerts, warnings, notifications
// ============================================================================

$layout = LayoutBuilder::create('dashboard', 'view')
    ->alertSection('system_alert')
    ->warning() // or ->info(), ->success(), ->error()
    ->title('System Maintenance')
    ->message('Scheduled maintenance on Dec 15, 2025')
    ->icon('alert-triangle')
    ->dismissible()
    ->bordered()

    ->alertSection('success_message')
    ->success()
    ->message('Profile updated successfully!')
    ->dismissible()
    ->filled()
    ->build();

// ============================================================================
// 4. MODAL SECTION - For modal/dialog content
// ============================================================================

$layout = LayoutBuilder::create('user', 'profile')
    ->modalSection('edit_profile_modal')
    ->title('Edit Profile')
    ->subtitle('Update your personal information')
    ->size('lg') // xs, sm, md, lg, xl, full
    ->closable()
    ->closeOnBackdrop()
    ->trigger('#edit-profile-btn')
    ->addFooterButton('Save', 'save', ['style' => 'primary'])
    ->addFooterButton('Cancel', 'cancel', ['style' => 'secondary'])
    ->build();

// ============================================================================
// 5. WIZARD SECTION - For multi-step processes
// ============================================================================

$layout = LayoutBuilder::create('registration', 'form')
    ->wizardSection('signup_wizard')
    ->title('Account Registration')
    ->linear() // Must complete steps in order
    ->showStepNumbers()
    ->vertical() // or horizontal()
    ->addStep('account', 'Account Details', [], [
        'icon' => 'user',
        'description' => 'Basic account information',
    ])
    ->addStep('profile', 'Profile', [], [
        'icon' => 'id-card',
        'optional' => true,
    ])
    ->addStep('preferences', 'Preferences', [], [
        'icon' => 'settings',
    ])
    ->addStep('review', 'Review', [], [
        'icon' => 'check-circle',
    ])
    ->currentStep(0)
    ->build();

// ============================================================================
// 6. CHART SECTION - For data visualization
// ============================================================================

$layout = LayoutBuilder::create('analytics', 'dashboard')
    ->chartSection('sales_chart')
    ->title('Monthly Sales')
    ->subtitle('Revenue trends')
    ->line() // or ->bar(), ->pie(), ->doughnut(), ->area()
    ->height(400)
    ->responsive()
    ->animated()
    ->library('apexcharts')
    ->dataUrl('/api/analytics/sales')
    ->addSeries('revenue', 'Revenue', ['color' => '#3b82f6'])
    ->addSeries('profit', 'Profit', ['color' => '#10b981'])
    ->chartOptions([
        'xaxis' => ['type' => 'datetime'],
        'stroke' => ['curve' => 'smooth'],
    ])

    ->chartSection('distribution')
    ->title('Sales by Category')
    ->pie()
    ->dataUrl('/api/analytics/distribution')
    ->build();

// ============================================================================
// 7. MEDIA SECTION - For images, videos, galleries
// ============================================================================

$layout = LayoutBuilder::create('product', 'view')
    ->mediaSection('product_gallery')
    ->title('Product Images')
    ->gallery()
    ->grid() // or ->masonry(), ->carousel()
    ->columns(4)
    ->aspectRatio('4:3')
    ->lightbox()
    ->captions()
    ->dataUrl('/api/products/{id}/images')
    ->addItem('image1', ['alt' => 'Product view 1', 'caption' => 'Front view'])
    ->addItem('image2', ['alt' => 'Product view 2'])

    ->mediaSection('product_video')
    ->title('Product Demo')
    ->video()
    ->dataUrl('/api/products/{id}/video')
    ->build();

// ============================================================================
// 8. COMMENT SECTION - For comment threads
// ============================================================================

$layout = LayoutBuilder::create('blog', 'post')
    ->commentSection('comments')
    ->title('Comments')
    ->subtitle('Join the discussion')
    ->threaded() // Nested replies
    ->maxDepth(3)
    ->voting() // Upvote/downvote
    ->editing()
    ->deleting()
    ->sortOrder('newest') // newest, oldest, popular
    ->mentioning() // @username
    ->markdown()
    ->dataUrl('/api/posts/{id}/comments')
    ->loadOnMount(false) // Lazy load
    ->build();

// ============================================================================
// 9. BADGE SECTION - For tags, labels, status indicators
// ============================================================================

$layout = LayoutBuilder::create('product', 'view')
    ->badgeSection('product_tags')
    ->title('Tags')
    ->pill()
    ->small()
    ->primary()
    ->removable()
    ->dataUrl('/api/products/{id}/tags')
    ->addBadge('tag1', ['color' => 'blue'])
    ->addBadge('tag2', ['color' => 'green', 'variant' => 'success'])

    ->badgeSection('status')
    ->success()
    ->outlined()
    ->dataKey('product.status')
    ->build();

// ============================================================================
// REAL-WORLD EXAMPLE: E-commerce Product Page
// ============================================================================

$productLayout = LayoutBuilder::create('product', 'detail')
    ->sharedDataUrl('/api/products/{id}')

    // Product status alerts
    ->alertSection('stock_alert')
    ->warning()
    ->message('Only 3 items left in stock!')
    ->icon('alert-circle')
    ->dismissible()

    // Product images
    ->mediaSection('gallery')
    ->title('Product Gallery')
    ->gallery()
    ->grid()
    ->columns(4)
    ->lightbox()
    ->useSharedData(true, 'images')

    // Product stats/metrics
    ->statsSection('product_stats')
    ->columns(4)
    ->useSharedData(true, 'stats')
    ->addMetric('views', 'Views', ['icon' => 'eye'])
    ->addMetric('favorites', 'Favorites', ['icon' => 'heart'])
    ->addMetric('rating', 'Rating', ['icon' => 'star', 'format' => 'number'])
    ->addMetric('reviews', 'Reviews', ['icon' => 'message-square'])

    // Product features list
    ->listSection('features')
    ->title('Features')
    ->bullet()
    ->useSharedData(true, 'features')

    // Product tags/categories
    ->badgeSection('tags')
    ->title('Categories')
    ->pill()
    ->primary()
    ->useSharedData(true, 'categories')

    // Related products chart
    ->chartSection('sales_trend')
    ->title('Sales Trend')
    ->line()
    ->height(300)
    ->dataUrl('/api/products/{id}/sales-trend')

    // Customer reviews timeline
    ->timelineSection('recent_reviews')
    ->title('Recent Reviews')
    ->vertical()
    ->showDates()
    ->dataUrl('/api/products/{id}/recent-reviews')
    ->loadOnMount(false)

    // Comments section
    ->commentSection('comments')
    ->title('Customer Questions')
    ->threaded()
    ->voting()
    ->dataUrl('/api/products/{id}/comments')
    ->loadOnMount(false)

    // Purchase wizard modal
    ->modalSection('purchase_modal')
    ->title('Complete Purchase')
    ->size('lg')
    ->trigger('#buy-now-btn')
    ->addFooterButton('Checkout', 'checkout', ['style' => 'primary'])
    ->addFooterButton('Cancel', 'cancel')

    ->build();

// ============================================================================
// REAL-WORLD EXAMPLE: Admin Dashboard
// ============================================================================

$dashboardLayout = LayoutBuilder::create('admin', 'dashboard')
    ->sharedDataUrl('/api/admin/dashboard')

    // System alerts
    ->alertSection('system_status')
    ->info()
    ->title('System Status')
    ->message('All systems operational')
    ->icon('check-circle')

    // Key metrics
    ->statsSection('metrics')
    ->title('Key Metrics')
    ->columns(4)
    ->useSharedData(true, 'metrics')
    ->addMetric('users', 'Total Users', ['icon' => 'users', 'show_trend' => true])
    ->addMetric('revenue', 'Revenue', ['icon' => 'dollar-sign', 'format' => 'currency'])
    ->addMetric('orders', 'Orders', ['icon' => 'shopping-cart'])
    ->addMetric('growth', 'Growth', ['icon' => 'trending-up', 'format' => 'percentage'])

    // Sales chart
    ->chartSection('sales_chart')
    ->title('Sales Overview')
    ->area()
    ->height(350)
    ->dataUrl('/api/admin/sales-chart')
    ->addSeries('sales', 'Sales')
    ->addSeries('profit', 'Profit')

    // Activity timeline
    ->timelineSection('recent_activity')
    ->title('Recent Activity')
    ->vertical()
    ->alternate()
    ->showDates()
    ->dataUrl('/api/admin/activity')
    ->loadOnMount(false)

    // Top products list
    ->listSection('top_products')
    ->title('Top Products')
    ->numbered()
    ->dataUrl('/api/admin/top-products')

    // Distribution pie chart
    ->chartSection('category_distribution')
    ->title('Sales by Category')
    ->doughnut()
    ->height(300)
    ->dataUrl('/api/admin/category-distribution')

    ->build();

<?php

/**
 * Examples of using action buttons with sections and subsections
 */

require_once __DIR__.'/../vendor/autoload.php';

use Litepie\Layout\Facades\Layout;

// ==========================================
// Example 1: Section with Action Buttons
// ==========================================

Layout::register('user', 'management', function ($builder) {
    $builder
        ->section('users')
        ->label('User Management')
        ->description('Manage system users')
        ->action('Add New User', '/users/create', [
            'icon' => 'fas fa-plus',
            'class' => 'btn btn-primary',
        ])
        ->action('Import Users', '/users/import', [
            'icon' => 'fas fa-upload',
            'class' => 'btn btn-secondary',
        ])
        ->action('Export Users', '/users/export', [
            'icon' => 'fas fa-download',
            'class' => 'btn btn-secondary',
        ])
        ->subsection('user_list')
        ->label('Active Users')
        ->field('search')->type('text')->placeholder('Search users...')->end()
        ->endSubsection()
        ->endSection();
});

// ==========================================
// Example 2: Subsection with Actions
// ==========================================

Layout::register('product', 'inventory', function ($builder) {
    $builder
        ->section('inventory')
        ->label('Inventory Management')
        ->subsection('stock_items')
        ->label('Stock Items')
        ->action('Add Item', '/inventory/add', [
            'icon' => 'fas fa-plus',
        ])
        ->action('Bulk Update', '/inventory/bulk-update', [
            'icon' => 'fas fa-edit',
            'class' => 'btn btn-secondary',
        ])
        ->field('sku')->type('text')->label('SKU')->end()
        ->field('quantity')->type('number')->label('Quantity')->end()
        ->endSubsection()
        ->endSection();
});

// ==========================================
// Example 3: Multiple Actions with Different Methods
// ==========================================

Layout::register('settings', 'system', function ($builder) {
    $builder
        ->section('backup')
        ->label('Backup & Restore')
        ->action('Create Backup', '/backup/create', [
            'icon' => 'fas fa-save',
            'class' => 'btn btn-primary',
            'method' => 'POST',
        ])
        ->action('Download Backup', '/backup/download', [
            'icon' => 'fas fa-download',
            'class' => 'btn btn-secondary',
            'method' => 'GET',
        ])
        ->action('Restore', '/backup/restore', [
            'icon' => 'fas fa-undo',
            'class' => 'btn btn-warning',
            'method' => 'POST',
        ])
        ->subsection('settings')
        ->field('auto_backup')->type('checkbox')->label('Enable Auto Backup')->end()
        ->endSubsection()
        ->endSection();
});

// ==========================================
// Example 4: Actions with Custom Options
// ==========================================

Layout::register('content', 'pages', function ($builder) {
    $builder
        ->section('pages')
        ->label('Pages')
        ->action('New Page', '/pages/create', [
            'icon' => 'fas fa-file-alt',
            'class' => 'btn btn-primary',
            'data-modal' => 'create-page',
        ])
        ->subsection('published')
        ->label('Published Pages')
        ->action('Bulk Edit', '/pages/bulk-edit', [
            'icon' => 'fas fa-edit',
            'class' => 'btn btn-secondary',
        ])
        ->action('Bulk Delete', '/pages/bulk-delete', [
            'icon' => 'fas fa-trash',
            'class' => 'btn btn-danger',
            'method' => 'DELETE',
            'data-confirm' => 'Are you sure?',
        ])
        ->field('title')->type('text')->label('Page Title')->end()
        ->endSubsection()
        ->endSection();
});

// ==========================================
// Example 5: Combining Section and Subsection Actions
// ==========================================

Layout::register('ecommerce', 'orders', function ($builder) {
    $builder
        ->section('orders')
        ->label('Order Management')
        ->action('Export Orders', '/orders/export', [
            'icon' => 'fas fa-file-excel',
            'class' => 'btn btn-success',
        ])
        ->subsection('pending')
        ->label('Pending Orders')
        ->action('Process All', '/orders/process-pending', [
            'icon' => 'fas fa-check-circle',
            'method' => 'POST',
        ])
        ->field('order_id')->type('text')->label('Order ID')->end()
        ->endSubsection()
        ->subsection('completed')
        ->label('Completed Orders')
        ->action('Archive', '/orders/archive-completed', [
            'icon' => 'fas fa-archive',
            'method' => 'POST',
        ])
        ->field('completion_date')->type('date')->label('Completion Date')->end()
        ->endSubsection()
        ->endSection();
});

// ==========================================
// Example 6: Using addAction Method
// ==========================================

$layout = Layout::for('custom', 'form')
    ->section('data')
    ->label('Data Management');

// Add actions programmatically
$layout->getSection('data')->addAction([
    'label' => 'Refresh',
    'url' => '/data/refresh',
    'icon' => 'fas fa-sync',
    'class' => 'btn btn-info',
    'method' => 'POST',
]);

$layout->getSection('data')->addAction([
    'label' => 'Clear Cache',
    'url' => '/cache/clear',
    'icon' => 'fas fa-broom',
    'class' => 'btn btn-warning',
    'method' => 'DELETE',
]);

// ==========================================
// Example 7: Accessing Actions
// ==========================================

$layout = Layout::get('user', 'management');

if ($layout) {
    $section = $layout->getSection('users');

    // Get all actions
    $actions = $section->getActions();

    echo 'Section has '.count($actions)." actions:\n";
    foreach ($actions as $action) {
        echo "- {$action['label']} ({$action['method']}) -> {$action['url']}\n";
    }
}

// ==========================================
// Example 8: Conditional Actions Based on Permissions
// ==========================================

$userCanCreate = true; // Example permission check
$userCanDelete = false;

Layout::register('blog', 'posts', function ($builder) use ($userCanCreate, $userCanDelete) {
    $section = $builder->section('posts')->label('Blog Posts');

    if ($userCanCreate) {
        $section->action('New Post', '/posts/create', [
            'icon' => 'fas fa-plus',
            'class' => 'btn btn-primary',
        ]);
    }

    $subsection = $section->subsection('all_posts')->label('All Posts');

    if ($userCanDelete) {
        $subsection->action('Delete Selected', '/posts/delete-selected', [
            'icon' => 'fas fa-trash',
            'class' => 'btn btn-danger',
            'method' => 'DELETE',
        ]);
    }
});

// ==========================================
// Example 9: Actions in View
// ==========================================

// In your controller:
// $layout = Layout::get('user', 'management', auth()->id());
// return view('users.index', compact('layout'));

// In your Blade view:
// @include('layout::render', ['layout' => $layout, 'data' => []])

// Actions will be automatically rendered in the section/subsection headers

echo "\nAction button examples completed successfully!\n";
echo "See the section and subsection headers for action buttons.\n";

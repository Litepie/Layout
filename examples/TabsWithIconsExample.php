<?php

/**
 * Tabs with Icons Example
 *
 * Demonstrates creating tabbed interfaces with icons, badges, and various options.
 */

use Litepie\Layout\Facades\Layout;

// Create tabbed layout with icons
$layout = Layout::create('tabs-with-icons')
    ->title('Tabbed Interface Examples')

    ->section('main', function ($section) {
        // ===========================
        // Example 1: Simple Tabs with Icons (Basic)
        // ===========================
        $section->tabs('simple-tabs')
            ->title('Simple Example')

            ->addTab('overview', 'Overview', function ($tab) {
                $tab->stats('users')->value(1234);
            }, [
                'icon' => 'home',
            ])
            ->addTab('analytics', 'Analytics', function ($tab) {
                $tab->chart('sales')->chartType('line');
            }, [
                'icon' => 'bar-chart',
            ])
            ->addTab('messages', 'Messages', function ($tab) {
                $tab->table('messages');
            }, [
                'icon' => 'mail',
                'badge' => '12',  // Show unread count
            ])
            ->addTab('settings', 'Settings', function ($tab) {
                $tab->form('settings');
            }, [
                'icon' => 'settings',
                'permissions' => ['manage-settings'],
            ])
            ->activeTab('overview');

        // ===========================
        // Example 2: Complete Tab Options Demo
        // ===========================
        $section->tabs('all-options-tabs')
            ->title('All Tab Options')

            ->addTab('tab1', 'Tab with Icon', function ($tab) {
                $tab->card('content')->title('Tab 1 Content');
            }, [
                'icon' => 'star',
            ])
            ->addTab('tab2', 'Tab with Badge', function ($tab) {
                $tab->card('content')->title('Tab 2 Content');
            }, [
                'icon' => 'bell',
                'badge' => '5',  // Badge (e.g., notification count)
            ])
            ->addTab('tab3', 'Disabled Tab', function ($tab) {
                $tab->card('content')->title('Tab 3 Content');
            }, [
                'icon' => 'lock',
                'disabled' => true,  // Tab is disabled
            ])
            ->addTab('tab4', 'Admin Only', function ($tab) {
                $tab->card('content')->title('Admin Content');
            }, [
                'icon' => 'shield',
                'permissions' => ['admin'],  // Requires permission
            ])
            ->addTab('tab5', 'Role Restricted', function ($tab) {
                $tab->card('content')->title('Manager Content');
            }, [
                'icon' => 'users',
                'roles' => ['manager', 'admin'],  // Requires role
            ])
            ->activeTab('tab1');

        // ===========================
        // Example 3: User Profile Tabs with Icons
        // ===========================
        $section->tabs('user-profile-tabs')
            ->title('User Profile')

            // Personal Info tab with user icon
            ->addTab('personal', 'Personal Info', function ($tab) {
                $tab->card('personal-info')
                    ->title('Personal Information')
                    ->addField('name', 'Full Name', 'John Doe')
                    ->addField('email', 'Email', 'john@example.com')
                    ->addField('phone', 'Phone', '+1 234 567 8900')
                    ->addField('location', 'Location', 'San Francisco, CA')
                    ->addAction('edit', 'Edit Profile', ['url' => '/profile/edit']);
            }, [
                'icon' => 'user',
            ])

            // Account Settings tab with settings icon
            ->addTab('account', 'Account', function ($tab) {
                $tab->form('account-settings')
                    ->action('/profile/account')
                    ->method('POST')
                    ->addField('username', 'text', 'Username')
                    ->addField('email_verified', 'checkbox', 'Email Verified')
                    ->addField('two_factor', 'checkbox', 'Enable Two-Factor Authentication')
                    ->addButton('save', 'Save Changes', 'submit');
            }, [
                'icon' => 'settings',
            ])

            // Security tab with shield icon
            ->addTab('security', 'Security', function ($tab) {
                $tab->form('security-settings')
                    ->action('/profile/security')
                    ->method('POST')
                    ->addField('current_password', 'password', 'Current Password')
                    ->addField('new_password', 'password', 'New Password')
                    ->addField('confirm_password', 'password', 'Confirm Password')
                    ->addButton('change', 'Change Password', 'submit');

                $tab->card('sessions')
                    ->title('Active Sessions')
                    ->dataUrl('/api/profile/sessions')
                    ->addAction('revoke_all', 'Revoke All Sessions', ['variant' => 'danger']);
            }, [
                'icon' => 'shield',
            ])

            // Notifications tab with bell icon and badge
            ->addTab('notifications', 'Notifications', function ($tab) {
                $tab->form('notification-settings')
                    ->action('/profile/notifications')
                    ->method('POST')
                    ->addField('email_notifications', 'checkbox', 'Email Notifications', ['default' => true])
                    ->addField('push_notifications', 'checkbox', 'Push Notifications', ['default' => false])
                    ->addField('sms_notifications', 'checkbox', 'SMS Notifications', ['default' => false])
                    ->addField('marketing_emails', 'checkbox', 'Marketing Emails', ['default' => false])
                    ->addButton('save', 'Save Preferences', 'submit');

                $tab->table('notification-history')
                    ->title('Recent Notifications')
                    ->dataUrl('/api/profile/notifications')
                    ->addColumn('message', 'Message')
                    ->addColumn('type', 'Type')
                    ->addColumn('date', 'Date')
                    ->paginate(10);
            }, [
                'icon' => 'bell',
                'badge' => '5',  // Show unread count
            ])

            // Privacy tab with lock icon
            ->addTab('privacy', 'Privacy', function ($tab) {
                $tab->form('privacy-settings')
                    ->action('/profile/privacy')
                    ->method('POST')
                    ->addField('profile_visibility', 'select', 'Profile Visibility', [
                        'options' => [
                            'public' => 'Public',
                            'friends' => 'Friends Only',
                            'private' => 'Private',
                        ],
                    ])
                    ->addField('show_email', 'checkbox', 'Show Email on Profile')
                    ->addField('show_phone', 'checkbox', 'Show Phone on Profile')
                    ->addField('allow_messages', 'checkbox', 'Allow Messages from Others')
                    ->addButton('save', 'Save Settings', 'submit');
            }, [
                'icon' => 'lock',
            ])

            ->activeTab('personal')
            ->position('top');

        // ===========================
        // Example 4: Dashboard Tabs with Icons and Permissions
        // ===========================
        $section->tabs('dashboard-tabs')
            ->title('Dashboard')

            // Overview tab
            ->addTab('overview', 'Overview', function ($tab) {
                $tab->grid('stats')
                    ->columns(3)
                    ->addComponent(
                        $tab->stats('users')
                            ->title('Total Users')
                            ->value(1234)
                            ->icon('users')
                    )
                    ->addComponent(
                        $tab->stats('orders')
                            ->title('Orders')
                            ->value(567)
                            ->icon('shopping-cart')
                    )
                    ->addComponent(
                        $tab->stats('revenue')
                            ->title('Revenue')
                            ->value(98765)
                            ->prefix('$')
                            ->icon('dollar-sign')
                    );
            }, [
                'icon' => 'home',
            ])

            // Analytics tab (restricted to certain roles)
            ->addTab('analytics', 'Analytics', function ($tab) {
                $tab->chart('sales-chart')
                    ->title('Sales Trends')
                    ->chartType('line')
                    ->dataUrl('/api/analytics/sales');

                $tab->chart('revenue-chart')
                    ->title('Revenue by Category')
                    ->chartType('doughnut')
                    ->dataUrl('/api/analytics/revenue');
            }, [
                'icon' => 'bar-chart',
                'permissions' => ['view-analytics'],
            ])

            // Messages tab with badge
            ->addTab('messages', 'Messages', function ($tab) {
                $tab->table('messages-table')
                    ->dataUrl('/api/messages')
                    ->addColumn('from', 'From')
                    ->addColumn('subject', 'Subject')
                    ->addColumn('date', 'Date')
                    ->addAction('view', 'View', ['icon' => 'eye'])
                    ->addAction('delete', 'Delete', ['icon' => 'trash'])
                    ->paginate(20);
            }, [
                'icon' => 'mail',
                'badge' => '12',  // Unread messages count
            ])

            // Reports tab (admin only)
            ->addTab('reports', 'Reports', function ($tab) {
                $tab->form('report-generator')
                    ->method('GET')
                    ->action('/reports/generate')
                    ->addField('report_type', 'select', 'Report Type', [
                        'options' => [
                            'sales' => 'Sales Report',
                            'users' => 'Users Report',
                            'revenue' => 'Revenue Report',
                            'activity' => 'Activity Report',
                        ],
                    ])
                    ->addField('date_from', 'date', 'From Date')
                    ->addField('date_to', 'date', 'To Date')
                    ->addButton('generate', 'Generate Report', 'submit');

                $tab->card('recent-reports')
                    ->title('Recent Reports')
                    ->dataUrl('/api/reports/recent');
            }, [
                'icon' => 'file-text',
                'roles' => ['admin', 'manager'],
            ])

            // Settings tab (disabled for demo)
            ->addTab('settings', 'Settings', function ($tab) {
                $tab->text('coming-soon')
                    ->content('Settings coming soon...')
                    ->align('center');
            }, [
                'icon' => 'settings',
                'disabled' => true,  // Tab is disabled
            ])

            ->activeTab('overview');

        // ===========================
        // Example 5: Project Management Tabs
        // ===========================
        $section->tabs('project-tabs')
            ->title('Project Management')

            // Tasks tab
            ->addTab('tasks', 'Tasks', function ($tab) {
                $tab->table('tasks-table')
                    ->dataUrl('/api/projects/1/tasks')
                    ->addColumn('title', 'Task', ['sortable' => true])
                    ->addColumn('assignee', 'Assignee')
                    ->addColumn('status', 'Status', ['filterable' => true])
                    ->addColumn('priority', 'Priority', ['filterable' => true])
                    ->addColumn('due_date', 'Due Date', ['sortable' => true])
                    ->addAction('edit', 'Edit', ['icon' => 'pencil'])
                    ->searchable(true)
                    ->paginate(25);
            }, [
                'icon' => 'check-square',
                'badge' => '8',  // Pending tasks
            ])

            // Team tab
            ->addTab('team', 'Team', function ($tab) {
                $tab->grid('team-members')
                    ->columns(3)
                    ->dataUrl('/api/projects/1/team');

                $tab->card('add-member')
                    ->title('Invite Team Member')
                    ->addAction('invite', 'Invite', ['icon' => 'user-plus']);
            }, [
                'icon' => 'users',
            ])

            // Files tab
            ->addTab('files', 'Files', function ($tab) {
                $tab->document('project-files')
                    ->title('Project Files')
                    ->uploadUrl('/api/projects/1/files/upload')
                    ->listUrl('/api/projects/1/files')
                    ->maxSize(50)
                    ->allowedTypes(['pdf', 'doc', 'docx', 'xls', 'xlsx', 'png', 'jpg'])
                    ->showPreview(true);
            }, [
                'icon' => 'folder',
            ])

            // Timeline tab
            ->addTab('timeline', 'Timeline', function ($tab) {
                $tab->timeline('project-timeline')
                    ->title('Project Activity')
                    ->dataUrl('/api/projects/1/timeline')
                    ->orientation('vertical');
            }, [
                'icon' => 'clock',
            ])

            // Discussion tab
            ->addTab('discussion', 'Discussion', function ($tab) {
                $tab->card('discussion-board')
                    ->title('Team Discussion')
                    ->dataUrl('/api/projects/1/discussions');
            }, [
                'icon' => 'message-circle',
                'badge' => '3',  // Unread messages
            ])

            ->activeTab('tasks')
            ->position('left');  // Tabs on the left side

        // ===========================
        // Example 6: E-commerce Product Tabs
        // ===========================
        $section->tabs('product-tabs')
            ->title('Product Details')

            // Description tab
            ->addTab('description', 'Description', function ($tab) {
                $tab->text('product-description')
                    ->dataUrl('/api/products/1/description')
                    ->format('markdown');
            }, [
                'icon' => 'file-text',
            ])

            // Specifications tab
            ->addTab('specs', 'Specifications', function ($tab) {
                $tab->table('specifications')
                    ->dataUrl('/api/products/1/specs')
                    ->addColumn('attribute', 'Attribute')
                    ->addColumn('value', 'Value');
            }, [
                'icon' => 'list',
            ])

            // Reviews tab
            ->addTab('reviews', 'Reviews', function ($tab) {
                $tab->stats('rating')
                    ->title('Average Rating')
                    ->value(4.5)
                    ->suffix('/ 5.0')
                    ->icon('star');

                $tab->card('reviews-list')
                    ->title('Customer Reviews')
                    ->dataUrl('/api/products/1/reviews');
            }, [
                'icon' => 'star',
                'badge' => '127',  // Review count
            ])

            // Q&A tab
            ->addTab('qa', 'Questions & Answers', function ($tab) {
                $tab->accordion('qa-list')
                    ->dataUrl('/api/products/1/qa')
                    ->allowMultiple(false);
            }, [
                'icon' => 'help-circle',
            ])

            ->activeTab('description')
            ->lazy(true);  // Lazy load tab content
    });

// Resolve authorization
$layout->resolveAuthorization(auth()->user());

// Render the layout
return $layout->render();

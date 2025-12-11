<?php

/**
 * ============================================================================
 * ALL COMPONENTS REFERENCE WITH JSON OUTPUT
 * ============================================================================
 * 
 * Complete reference for all 19 component types in Litepie Layout v3.0+
 * Each example includes:
 * - PHP Builder Code
 * - Sample JSON Output for API Mode
 * 
 * @package Litepie\Layout
 * @version 3.0.0
 */

use Litepie\Layout\LayoutBuilder;

// ============================================================================
// EXAMPLE 1: LIST SECTION
// ============================================================================
// Use for: Bullet points, numbered lists, checklists, feature lists

$listLayout = LayoutBuilder::create('features', 'view')
    ->listSection('feature_list')
    ->title('Premium Features')
    ->description('Everything you need to succeed')
    ->bullet() // Options: bullet(), numbered(), definition(), checklist()
    ->dataUrl('/api/features')
    ->addItem('Fast Performance', 'Lightning-fast load times', ['icon' => 'zap', 'checked' => true])
    ->addItem('Secure', '256-bit encryption', ['icon' => 'shield', 'checked' => true])
    ->addItem('Scalable', 'Grows with your business', ['icon' => 'trending-up'])
    ->build();

/* JSON OUTPUT:
{
  "id": "features",
  "view": "view",
  "sections": [
    {
      "type": "list",
      "name": "feature_list",
      "title": "Premium Features",
      "description": "Everything you need to succeed",
      "style": "bullet",
      "dataUrl": "/api/features",
      "items": [
        {
          "id": "Fast Performance",
          "content": "Lightning-fast load times",
          "meta": {"icon": "zap", "checked": true}
        },
        {
          "id": "Secure",
          "content": "256-bit encryption",
          "meta": {"icon": "shield", "checked": true}
        },
        {
          "id": "Scalable",
          "content": "Grows with your business",
          "meta": {"icon": "trending-up"}
        }
      ]
    }
  ]
}
*/

// ============================================================================
// EXAMPLE 2: TIMELINE SECTION
// ============================================================================
// Use for: Order tracking, activity feeds, project milestones

$timelineLayout = LayoutBuilder::create('order', 'tracking')
    ->timelineSection('delivery_timeline')
    ->title('Order #12345 Timeline')
    ->vertical()
    ->alternate()
    ->showDates()
    ->dateFormat('relative')
    ->dataUrl('/api/orders/12345/timeline')
    ->addEvent('ordered', ['icon' => 'shopping-cart', 'color' => 'blue', 'timestamp' => '2025-12-10 10:00:00'])
    ->addEvent('confirmed', ['icon' => 'check-circle', 'color' => 'green', 'timestamp' => '2025-12-10 10:05:00'])
    ->addEvent('shipped', ['icon' => 'truck', 'color' => 'orange', 'timestamp' => '2025-12-10 14:00:00'])
    ->addEvent('delivered', ['icon' => 'home', 'color' => 'green', 'timestamp' => '2025-12-11 09:30:00'])
    ->build();

/* JSON OUTPUT:
{
  "id": "order",
  "view": "tracking",
  "sections": [
    {
      "type": "timeline",
      "name": "delivery_timeline",
      "title": "Order #12345 Timeline",
      "orientation": "vertical",
      "alternate": true,
      "showDates": true,
      "dateFormat": "relative",
      "dataUrl": "/api/orders/12345/timeline",
      "events": [
        {
          "id": "ordered",
          "meta": {
            "icon": "shopping-cart",
            "color": "blue",
            "timestamp": "2025-12-10 10:00:00"
          }
        },
        {
          "id": "confirmed",
          "meta": {
            "icon": "check-circle",
            "color": "green",
            "timestamp": "2025-12-10 10:05:00"
          }
        },
        {
          "id": "shipped",
          "meta": {
            "icon": "truck",
            "color": "orange",
            "timestamp": "2025-12-10 14:00:00"
          }
        },
        {
          "id": "delivered",
          "meta": {
            "icon": "home",
            "color": "green",
            "timestamp": "2025-12-11 09:30:00"
          }
        }
      ]
    }
  ]
}
*/

// ============================================================================
// EXAMPLE 3: ALERT SECTION
// ============================================================================
// Use for: System alerts, notifications, warnings, error messages

$alertLayout = LayoutBuilder::create('dashboard', 'view')
    ->alertSection('maintenance_alert')
    ->warning() // Options: info(), success(), warning(), error()
    ->title('Scheduled Maintenance')
    ->message('System will be down for maintenance on Dec 15, 2025 from 2-4 AM EST')
    ->icon('alert-triangle')
    ->dismissible()
    ->bordered()
    ->build();

/* JSON OUTPUT:
{
  "id": "dashboard",
  "view": "view",
  "sections": [
    {
      "type": "alert",
      "name": "maintenance_alert",
      "variant": "warning",
      "title": "Scheduled Maintenance",
      "message": "System will be down for maintenance on Dec 15, 2025 from 2-4 AM EST",
      "icon": "alert-triangle",
      "dismissible": true,
      "bordered": true,
      "filled": false
    }
  ]
}
*/

// ============================================================================
// EXAMPLE 4: MODAL SECTION
// ============================================================================
// Use for: Dialogs, popups, confirmation prompts

$modalLayout = LayoutBuilder::create('user', 'profile')
    ->modalSection('edit_profile_modal')
    ->title('Edit Profile')
    ->subtitle('Update your personal information')
    ->size('lg') // Options: xs, sm, md, lg, xl, full
    ->closable()
    ->closeOnBackdrop()
    ->trigger('#edit-profile-btn')
    ->addFooterButton('Save Changes', 'save', ['style' => 'primary'])
    ->addFooterButton('Cancel', 'cancel', ['style' => 'secondary'])
    ->build();

/* JSON OUTPUT:
{
  "id": "user",
  "view": "profile",
  "sections": [
    {
      "type": "modal",
      "name": "edit_profile_modal",
      "title": "Edit Profile",
      "subtitle": "Update your personal information",
      "size": "lg",
      "closable": true,
      "closeOnBackdrop": true,
      "trigger": "#edit-profile-btn",
      "footerButtons": [
        {"label": "Save Changes", "action": "save", "meta": {"style": "primary"}},
        {"label": "Cancel", "action": "cancel", "meta": {"style": "secondary"}}
      ]
    }
  ]
}
*/

*/

// ============================================================================
// EXAMPLE 5: WIZARD SECTION
// ============================================================================
// Use for: Multi-step forms, onboarding, checkout processes

$wizardLayout = LayoutBuilder::create('registration', 'form')
    ->wizardSection('signup_wizard')
    ->title('Account Registration')
    ->linear() // Must complete steps in order
    ->showStepNumbers()
    ->vertical() // or horizontal()
    ->addStep('account', 'Account Details', [], ['icon' => 'user', 'description' => 'Basic account information'])
    ->addStep('profile', 'Profile Info', [], ['icon' => 'id-card', 'optional' => true])
    ->addStep('preferences', 'Preferences', [], ['icon' => 'settings'])
    ->addStep('review', 'Review & Submit', [], ['icon' => 'check-circle'])
    ->currentStep(0)
    ->build();

/* JSON OUTPUT:
{
  "id": "registration",
  "view": "form",
  "sections": [
    {
      "type": "wizard",
      "name": "signup_wizard",
      "title": "Account Registration",
      "linear": true,
      "showStepNumbers": true,
      "orientation": "vertical",
      "currentStep": 0,
      "steps": [
        {
          "id": "account",
          "label": "Account Details",
          "meta": {"icon": "user", "description": "Basic account information"}
        },
        {
          "id": "profile",
          "label": "Profile Info",
          "meta": {"icon": "id-card", "optional": true}
        },
        {
          "id": "preferences",
          "label": "Preferences",
          "meta": {"icon": "settings"}
        },
        {
          "id": "review",
          "label": "Review & Submit",
          "meta": {"icon": "check-circle"}
        }
      ]
    }
  ]
}
*/

// ============================================================================
// EXAMPLE 6: CHART SECTION
// ============================================================================
// Use for: Data visualization, analytics, reports

$chartLayout = LayoutBuilder::create('analytics', 'dashboard')
    ->chartSection('sales_chart')
    ->title('Monthly Sales Report')
    ->subtitle('Revenue and profit trends')
    ->line() // Options: line(), bar(), pie(), doughnut(), area()
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
        'dataLabels' => ['enabled' => false],
    ])
    ->build();

/* JSON OUTPUT:
{
  "id": "analytics",
  "view": "dashboard",
  "sections": [
    {
      "type": "chart",
      "name": "sales_chart",
      "title": "Monthly Sales Report",
      "subtitle": "Revenue and profit trends",
      "chartType": "line",
      "height": 400,
      "responsive": true,
      "animated": true,
      "library": "apexcharts",
      "dataUrl": "/api/analytics/sales",
      "series": [
        {"id": "revenue", "label": "Revenue", "meta": {"color": "#3b82f6"}},
        {"id": "profit", "label": "Profit", "meta": {"color": "#10b981"}}
      ],
      "options": {
        "xaxis": {"type": "datetime"},
        "stroke": {"curve": "smooth"},
        "dataLabels": {"enabled": false}
      }
    }
  ]
}
*/

// ============================================================================
// EXAMPLE 7: MEDIA SECTION
// ============================================================================
// Use for: Image galleries, video players, audio files

$mediaLayout = LayoutBuilder::create('product', 'view')
    ->mediaSection('product_gallery')
    ->title('Product Images')
    ->gallery()
    ->grid() // Options: grid(), masonry(), carousel()
    ->columns(4)
    ->aspectRatio('4:3')
    ->lightbox()
    ->captions()
    ->dataUrl('/api/products/123/images')
    ->addItem('/images/product-1.jpg', ['alt' => 'Front view', 'caption' => 'Product front view'])
    ->addItem('/images/product-2.jpg', ['alt' => 'Side view', 'caption' => 'Product side view'])
    ->addItem('/images/product-3.jpg', ['alt' => 'Detail', 'caption' => 'Close-up detail'])
    ->build();

/* JSON OUTPUT:
{
  "id": "product",
  "view": "view",
  "sections": [
    {
      "type": "media",
      "name": "product_gallery",
      "title": "Product Images",
      "mediaType": "gallery",
      "layout": "grid",
      "columns": 4,
      "aspectRatio": "4:3",
      "lightbox": true,
      "captions": true,
      "dataUrl": "/api/products/123/images",
      "items": [
        {
          "url": "/images/product-1.jpg",
          "meta": {"alt": "Front view", "caption": "Product front view"}
        },
        {
          "url": "/images/product-2.jpg",
          "meta": {"alt": "Side view", "caption": "Product side view"}
        },
        {
          "url": "/images/product-3.jpg",
          "meta": {"alt": "Detail", "caption": "Close-up detail"}
        }
      ]
    }
  ]
}
*/

// ============================================================================
// EXAMPLE 8: COMMENT SECTION
// ============================================================================
// Use for: Blog comments, discussion forums, reviews

$commentLayout = LayoutBuilder::create('blog', 'post')
    ->commentSection('post_comments')
    ->title('Comments')
    ->subtitle('Join the discussion')
    ->threaded() // Nested replies
    ->maxDepth(3)
    ->voting() // Upvote/downvote
    ->editing()
    ->deleting()
    ->sortOrder('newest') // Options: newest, oldest, popular
    ->mentioning() // @username support
    ->markdown()
    ->dataUrl('/api/posts/456/comments')
    ->loadOnMount(false) // Lazy load
    ->build();

/* JSON OUTPUT:
{
  "id": "blog",
  "view": "post",
  "sections": [
    {
      "type": "comment",
      "name": "post_comments",
      "title": "Comments",
      "subtitle": "Join the discussion",
      "threaded": true,
      "maxDepth": 3,
      "voting": true,
      "editing": true,
      "deleting": true,
      "sortOrder": "newest",
      "mentioning": true,
      "markdown": true,
      "dataUrl": "/api/posts/456/comments",
      "loadOnMount": false
    }
  ]
}
*/

// ============================================================================
// EXAMPLE 9: BADGE SECTION
// ============================================================================
// Use for: Tags, labels, status indicators, chips

$badgeLayout = LayoutBuilder::create('product', 'view')
    ->badgeSection('product_tags')
    ->title('Tags')
    ->pill()
    ->small()
    ->primary()
    ->removable()
    ->dataUrl('/api/products/123/tags')
    ->addBadge('New Arrival', ['color' => 'blue', 'icon' => 'star'])
    ->addBadge('Best Seller', ['color' => 'green', 'icon' => 'trending-up'])
    ->addBadge('Limited Edition', ['color' => 'purple', 'icon' => 'zap'])
    ->build();

/* JSON OUTPUT:
{
  "id": "product",
  "view": "view",
  "sections": [
    {
      "type": "badge",
      "name": "product_tags",
      "title": "Tags",
      "pill": true,
      "small": true,
      "variant": "primary",
      "removable": true,
      "dataUrl": "/api/products/123/tags",
      "badges": [
        {"label": "New Arrival", "meta": {"color": "blue", "icon": "star"}},
        {"label": "Best Seller", "meta": {"color": "green", "icon": "trending-up"}},
        {"label": "Limited Edition", "meta": {"color": "purple", "icon": "zap"}}
      ]
    }
  ]
}
*/

// ============================================================================
// EXAMPLE 10: TABLE SECTION
// ============================================================================
// Use for: Data tables, grids, listings

$tableLayout = LayoutBuilder::create('users', 'list')
    ->tableSection('users_table')
    ->title('User Management')
    ->striped()
    ->bordered()
    ->hoverable()
    ->responsive()
    ->sortable()
    ->filterable()
    ->searchable()
    ->paginated(25) // Items per page
    ->selectable()
    ->dataUrl('/api/users')
    ->addColumn('id', 'ID', ['width' => 80, 'sortable' => true])
    ->addColumn('name', 'Full Name', ['sortable' => true, 'searchable' => true])
    ->addColumn('email', 'Email', ['sortable' => true])
    ->addColumn('role', 'Role', ['filterable' => true])
    ->addColumn('status', 'Status', ['badge' => true])
    ->addColumn('actions', 'Actions', ['type' => 'actions'])
    ->build();

/* JSON OUTPUT:
{
  "id": "users",
  "view": "list",
  "sections": [
    {
      "type": "table",
      "name": "users_table",
      "title": "User Management",
      "striped": true,
      "bordered": true,
      "hoverable": true,
      "responsive": true,
      "sortable": true,
      "filterable": true,
      "searchable": true,
      "pagination": 25,
      "selectable": true,
      "dataUrl": "/api/users",
      "columns": [
        {"key": "id", "label": "ID", "meta": {"width": 80, "sortable": true}},
        {"key": "name", "label": "Full Name", "meta": {"sortable": true, "searchable": true}},
        {"key": "email", "label": "Email", "meta": {"sortable": true}},
        {"key": "role", "label": "Role", "meta": {"filterable": true}},
        {"key": "status", "label": "Status", "meta": {"badge": true}},
        {"key": "actions", "label": "Actions", "meta": {"type": "actions"}}
      ]
    }
  ]
}
*/

// ============================================================================
// EXAMPLE 11: FORM SECTION
// ============================================================================
// Use for: Forms, data entry, user input

$formLayout = LayoutBuilder::create('user', 'edit')
    ->formSection('profile_form')
    ->title('Edit Profile')
    ->description('Update your personal information')
    ->columns(2) // Form layout
    ->method('PUT')
    ->action('/api/users/123')
    ->validateOnChange()
    ->showRequiredIndicator()
    ->addField('text', 'first_name', 'First Name', ['required' => true, 'placeholder' => 'John'])
    ->addField('text', 'last_name', 'Last Name', ['required' => true, 'placeholder' => 'Doe'])
    ->addField('email', 'email', 'Email Address', ['required' => true, 'validation' => 'email'])
    ->addField('tel', 'phone', 'Phone Number', ['mask' => '(999) 999-9999'])
    ->addField('textarea', 'bio', 'Bio', ['rows' => 4, 'maxlength' => 500])
    ->addField('select', 'role', 'Role', ['options' => ['admin', 'editor', 'viewer']])
    ->addSubmitButton('Save Changes', ['loading' => true])
    ->addResetButton('Reset')
    ->build();

/* JSON OUTPUT:
{
  "id": "user",
  "view": "edit",
  "sections": [
    {
      "type": "form",
      "name": "profile_form",
      "title": "Edit Profile",
      "description": "Update your personal information",
      "columns": 2,
      "method": "PUT",
      "action": "/api/users/123",
      "validateOnChange": true,
      "showRequiredIndicator": true,
      "fields": [
        {
          "type": "text",
          "name": "first_name",
          "label": "First Name",
          "meta": {"required": true, "placeholder": "John"}
        },
        {
          "type": "text",
          "name": "last_name",
          "label": "Last Name",
          "meta": {"required": true, "placeholder": "Doe"}
        },
        {
          "type": "email",
          "name": "email",
          "label": "Email Address",
          "meta": {"required": true, "validation": "email"}
        },
        {
          "type": "tel",
          "name": "phone",
          "label": "Phone Number",
          "meta": {"mask": "(999) 999-9999"}
        },
        {
          "type": "textarea",
          "name": "bio",
          "label": "Bio",
          "meta": {"rows": 4, "maxlength": 500}
        },
        {
          "type": "select",
          "name": "role",
          "label": "Role",
          "meta": {"options": ["admin", "editor", "viewer"]}
        }
      ],
      "buttons": [
        {"type": "submit", "label": "Save Changes", "meta": {"loading": true}},
        {"type": "reset", "label": "Reset"}
      ]
    }
  ]
}
*/

// ============================================================================
// EXAMPLE 12: GRID SECTION
// ============================================================================
// Use for: Card grids, product listings, portfolios

$gridLayout = LayoutBuilder::create('products', 'catalog')
    ->gridSection('product_grid')
    ->title('Featured Products')
    ->columns(3) // Columns
    ->gap(4) // Spacing
    ->responsive() // Auto-adjust columns
    ->masonry() // Masonry layout
    ->dataUrl('/api/products/featured')
    ->addItem('product_1', ['title' => 'Product 1', 'price' => '$99', 'image' => '/img/p1.jpg'])
    ->addItem('product_2', ['title' => 'Product 2', 'price' => '$149', 'image' => '/img/p2.jpg'])
    ->addItem('product_3', ['title' => 'Product 3', 'price' => '$199', 'image' => '/img/p3.jpg'])
    ->build();

/* JSON OUTPUT:
{
  "id": "products",
  "view": "catalog",
  "sections": [
    {
      "type": "grid",
      "name": "product_grid",
      "title": "Featured Products",
      "columns": 3,
      "gap": 4,
      "responsive": true,
      "masonry": true,
      "dataUrl": "/api/products/featured",
      "items": [
        {
          "id": "product_1",
          "meta": {"title": "Product 1", "price": "$99", "image": "/img/p1.jpg"}
        },
        {
          "id": "product_2",
          "meta": {"title": "Product 2", "price": "$149", "image": "/img/p2.jpg"}
        },
        {
          "id": "product_3",
          "meta": {"title": "Product 3", "price": "$199", "image": "/img/p3.jpg"}
        }
      ]
    }
  ]
}
*/

// ============================================================================
// EXAMPLE 13: STATS SECTION
// ============================================================================
// Use for: Metrics, KPIs, dashboards, statistics

$statsLayout = LayoutBuilder::create('dashboard', 'overview')
    ->statsSection('key_metrics')
    ->title('Business Overview')
    ->columns(4)
    ->animated()
    ->colorful()
    ->dataUrl('/api/dashboard/stats')
    ->addMetric('revenue', 'Total Revenue', [
        'icon' => 'dollar-sign',
        'value' => '$124,500',
        'change' => '+12.5%',
        'trend' => 'up',
        'color' => 'green'
    ])
    ->addMetric('users', 'Active Users', [
        'icon' => 'users',
        'value' => '8,429',
        'change' => '+5.2%',
        'trend' => 'up',
        'color' => 'blue'
    ])
    ->addMetric('orders', 'Orders', [
        'icon' => 'shopping-cart',
        'value' => '1,234',
        'change' => '-2.4%',
        'trend' => 'down',
        'color' => 'orange'
    ])
    ->addMetric('satisfaction', 'Satisfaction', [
        'icon' => 'smile',
        'value' => '98.5%',
        'change' => '+0.8%',
        'trend' => 'up',
        'color' => 'purple'
    ])
    ->build();

/* JSON OUTPUT:
{
  "id": "dashboard",
  "view": "overview",
  "sections": [
    {
      "type": "stats",
      "name": "key_metrics",
      "title": "Business Overview",
      "columns": 4,
      "animated": true,
      "colorful": true,
      "dataUrl": "/api/dashboard/stats",
      "metrics": [
        {
          "key": "revenue",
          "label": "Total Revenue",
          "meta": {
            "icon": "dollar-sign",
            "value": "$124,500",
            "change": "+12.5%",
            "trend": "up",
            "color": "green"
          }
        },
        {
          "key": "users",
          "label": "Active Users",
          "meta": {
            "icon": "users",
            "value": "8,429",
            "change": "+5.2%",
            "trend": "up",
            "color": "blue"
          }
        },
        {
          "key": "orders",
          "label": "Orders",
          "meta": {
            "icon": "shopping-cart",
            "value": "1,234",
            "change": "-2.4%",
            "trend": "down",
            "color": "orange"
          }
        },
        {
          "key": "satisfaction",
          "label": "Satisfaction",
          "meta": {
            "icon": "smile",
            "value": "98.5%",
            "change": "+0.8%",
            "trend": "up",
            "color": "purple"
          }
        }
      ]
    }
  ]
}
*/

// ============================================================================
// EXAMPLE 14: CARD SECTION
// ============================================================================
// Use for: Content cards, info boxes, feature highlights

$cardLayout = LayoutBuilder::create('homepage', 'view')
    ->cardSection('welcome_card')
    ->title('Welcome Back!')
    ->subtitle('You have 5 new notifications')
    ->image('/images/welcome-banner.jpg')
    ->imagePosition('top') // top, bottom, left, right
    ->elevated() // Shadow
    ->bordered()
    ->hoverable()
    ->clickable('/dashboard')
    ->addAction('View All', 'view_all', ['icon' => 'arrow-right'])
    ->addAction('Dismiss', 'dismiss', ['icon' => 'x', 'variant' => 'ghost'])
    ->build();

/* JSON OUTPUT:
{
  "id": "homepage",
  "view": "view",
  "sections": [
    {
      "type": "card",
      "name": "welcome_card",
      "title": "Welcome Back!",
      "subtitle": "You have 5 new notifications",
      "image": "/images/welcome-banner.jpg",
      "imagePosition": "top",
      "elevated": true,
      "bordered": true,
      "hoverable": true,
      "clickable": "/dashboard",
      "actions": [
        {"label": "View All", "action": "view_all", "meta": {"icon": "arrow-right"}},
        {"label": "Dismiss", "action": "dismiss", "meta": {"icon": "x", "variant": "ghost"}}
      ]
    }
  ]
}
*/

// ============================================================================
// EXAMPLE 15: TABS SECTION
// ============================================================================
// Use for: Tabbed content, switching views

$tabsLayout = LayoutBuilder::create('settings', 'view')
    ->tabsSection('settings_tabs')
    ->title('Settings')
    ->variant('underline') // Options: underline, pills, bordered
    ->fullWidth()
    ->lazy() // Load content on demand
    ->defaultTab('general')
    ->addTab('general', 'General', [], ['icon' => 'settings'])
    ->addTab('security', 'Security', [], ['icon' => 'shield', 'badge' => '2'])
    ->addTab('notifications', 'Notifications', [], ['icon' => 'bell'])
    ->addTab('billing', 'Billing', [], ['icon' => 'credit-card'])
    ->build();

/* JSON OUTPUT:
{
  "id": "settings",
  "view": "view",
  "sections": [
    {
      "type": "tabs",
      "name": "settings_tabs",
      "title": "Settings",
      "variant": "underline",
      "fullWidth": true,
      "lazy": true,
      "defaultTab": "general",
      "tabs": [
        {"key": "general", "label": "General", "meta": {"icon": "settings"}},
        {"key": "security", "label": "Security", "meta": {"icon": "shield", "badge": "2"}},
        {"key": "notifications", "label": "Notifications", "meta": {"icon": "bell"}},
        {"key": "billing", "label": "Billing", "meta": {"icon": "credit-card"}}
      ]
    }
  ]
}
*/

// ============================================================================
// EXAMPLE 16: ACCORDION SECTION
// ============================================================================
// Use for: FAQs, collapsible content

$accordionLayout = LayoutBuilder::create('help', 'faq')
    ->accordionSection('faq_accordion')
    ->title('Frequently Asked Questions')
    ->allowMultiple() // Multiple panels open
    ->bordered()
    ->addPanel('shipping', 'What are the shipping options?', [], [
        'content' => 'We offer standard, express, and overnight shipping.',
        'icon' => 'truck'
    ])
    ->addPanel('returns', 'What is your return policy?', [], [
        'content' => '30-day money-back guarantee on all products.',
        'icon' => 'rotate-ccw'
    ])
    ->addPanel('warranty', 'Do products come with a warranty?', [], [
        'content' => 'All products include a 1-year manufacturer warranty.',
        'icon' => 'shield'
    ])
    ->defaultOpen(['shipping'])
    ->build();

/* JSON OUTPUT:
{
  "id": "help",
  "view": "faq",
  "sections": [
    {
      "type": "accordion",
      "name": "faq_accordion",
      "title": "Frequently Asked Questions",
      "allowMultiple": true,
      "bordered": true,
      "defaultOpen": ["shipping"],
      "panels": [
        {
          "id": "shipping",
          "title": "What are the shipping options?",
          "meta": {
            "content": "We offer standard, express, and overnight shipping.",
            "icon": "truck"
          }
        },
        {
          "id": "returns",
          "title": "What is your return policy?",
          "meta": {
            "content": "30-day money-back guarantee on all products.",
            "icon": "rotate-ccw"
          }
        },
        {
          "id": "warranty",
          "title": "Do products come with a warranty?",
          "meta": {
            "content": "All products include a 1-year manufacturer warranty.",
            "icon": "shield"
          }
        }
      ]
    }
  ]
}
*/

// ============================================================================
// EXAMPLE 17: TEXT SECTION
// ============================================================================
// Use for: Rich text, articles, documentation

$textLayout = LayoutBuilder::create('blog', 'post')
    ->textSection('article_content')
    ->title('Getting Started with Litepie Layout')
    ->subtitle('A comprehensive guide')
    ->markdown() // Supports Markdown
    ->dataUrl('/api/posts/789/content')
    ->content('## Introduction\n\nLitepie Layout is a powerful...')
    ->author('John Doe')
    ->publishedAt('2025-12-11')
    ->readingTime(5) // minutes
    ->build();

/* JSON OUTPUT:
{
  "id": "blog",
  "view": "post",
  "sections": [
    {
      "type": "text",
      "name": "article_content",
      "title": "Getting Started with Litepie Layout",
      "subtitle": "A comprehensive guide",
      "markdown": true,
      "dataUrl": "/api/posts/789/content",
      "content": "## Introduction\\n\\nLitepie Layout is a powerful...",
      "author": "John Doe",
      "publishedAt": "2025-12-11",
      "readingTime": 5
    }
  ]
}
*/

// ============================================================================
// EXAMPLE 18: SCROLL SPY SECTION
// ============================================================================
// Use for: Table of contents, navigation anchors

$scrollSpyLayout = LayoutBuilder::create('docs', 'view')
    ->scrollSpySection('toc')
    ->title('Table of Contents')
    ->sticky()
    ->offset(100)
    ->smooth()
    ->addAnchor('introduction', 'Introduction', ['level' => 1])
    ->addAnchor('installation', 'Installation', ['level' => 1])
    ->addAnchor('requirements', 'Requirements', ['level' => 2])
    ->addAnchor('setup', 'Setup', ['level' => 2])
    ->addAnchor('usage', 'Usage', ['level' => 1])
    ->addAnchor('examples', 'Examples', ['level' => 2])
    ->build();

/* JSON OUTPUT:
{
  "id": "docs",
  "view": "view",
  "sections": [
    {
      "type": "scrollspy",
      "name": "toc",
      "title": "Table of Contents",
      "sticky": true,
      "offset": 100,
      "smooth": true,
      "anchors": [
        {"id": "introduction", "label": "Introduction", "meta": {"level": 1}},
        {"id": "installation", "label": "Installation", "meta": {"level": 1}},
        {"id": "requirements", "label": "Requirements", "meta": {"level": 2}},
        {"id": "setup", "label": "Setup", "meta": {"level": 2}},
        {"id": "usage", "label": "Usage", "meta": {"level": 1}},
        {"id": "examples", "label": "Examples", "meta": {"level": 2}}
      ]
    }
  ]
}
*/

// ============================================================================
// EXAMPLE 19: CUSTOM SECTION
// ============================================================================
// Use for: Custom components, special layouts

$customLayout = LayoutBuilder::create('app', 'custom')
    ->customSection('pricing_calculator')
    ->title('Pricing Calculator')
    ->component('PricingCalculator') // Custom Vue/React component
    ->dataUrl('/api/pricing/calculate')
    ->props([
        'currency' => 'USD',
        'basePrice' => 99,
        'features' => ['feature1', 'feature2', 'feature3'],
        'discount' => 10
    ])
    ->events([
        'onCalculate' => '/api/pricing/calculate',
        'onSave' => '/api/pricing/save'
    ])
    ->build();

/* JSON OUTPUT:
{
  "id": "app",
  "view": "custom",
  "sections": [
    {
      "type": "custom",
      "name": "pricing_calculator",
      "title": "Pricing Calculator",
      "component": "PricingCalculator",
      "dataUrl": "/api/pricing/calculate",
      "props": {
        "currency": "USD",
        "basePrice": 99,
        "features": ["feature1", "feature2", "feature3"],
        "discount": 10
      },
      "events": {
        "onCalculate": "/api/pricing/calculate",
        "onSave": "/api/pricing/save"
      }
    }
  ]
}
*/

// ============================================================================
// COMPLETE EXAMPLE: E-commerce Product Page
// ============================================================================
// Demonstrates multiple components working together

$completeProductPage = LayoutBuilder::create('product', 'detail')
    ->sharedDataUrl('/api/products/12345')

    // Product status alert
    ->alertSection('stock_alert')
    ->warning()
    ->message('Only 3 items left in stock - Order soon!')
    ->icon('alert-triangle')
    ->dismissible()

    // Product image gallery
    ->mediaSection('product_gallery')
    ->title('Product Images')
    ->gallery()
    ->grid()
    ->columns(4)
    ->lightbox()
    ->captions()
    ->useSharedData(true, 'images')

    // Product statistics
    ->statsSection('product_metrics')
    ->columns(4)
    ->colorful()
    ->useSharedData(true, 'metrics')
    ->addMetric('views', 'Views', ['icon' => 'eye'])
    ->addMetric('favorites', 'Favorites', ['icon' => 'heart'])
    ->addMetric('rating', 'Rating', ['icon' => 'star'])
    ->addMetric('reviews', 'Reviews', ['icon' => 'message-square'])

    // Feature list
    ->listSection('features')
    ->title('Key Features')
    ->bullet()
    ->useSharedData(true, 'features')

    // Product tabs (description, specs, reviews)
    ->tabsSection('product_tabs')
    ->variant('underline')
    ->fullWidth()
    ->addTab('description', 'Description', [], ['icon' => 'file-text'])
    ->addTab('specifications', 'Specifications', [], ['icon' => 'list'])
    ->addTab('reviews', 'Reviews', [], ['icon' => 'star', 'badge' => '24'])

    // Customer reviews/comments
    ->commentSection('reviews')
    ->title('Customer Reviews')
    ->voting()
    ->dataUrl('/api/products/12345/reviews')
    ->sortOrder('popular')

    // Related products grid
    ->gridSection('related_products')
    ->title('Related Products')
    ->columns(4)
    ->responsive()
    ->dataUrl('/api/products/12345/related')

    // Add to cart form
    ->formSection('add_to_cart')
    ->title('Purchase Options')
    ->method('POST')
    ->action('/api/cart/add')
    ->addField('select', 'quantity', 'Quantity', [
        'options' => [1, 2, 3, 4, 5],
        'default' => 1
    ])
    ->addField('select', 'size', 'Size', [
        'options' => ['S', 'M', 'L', 'XL'],
        'required' => true
    ])
    ->addField('select', 'color', 'Color', [
        'options' => ['Black', 'White', 'Blue'],
        'required' => true
    ])
    ->addSubmitButton('Add to Cart', ['icon' => 'shopping-cart'])

    ->build();

/* COMPLETE JSON OUTPUT:
{
  "id": "product",
  "view": "detail",
  "sharedDataUrl": "/api/products/12345",
  "sections": [
    {
      "type": "alert",
      "name": "stock_alert",
      "variant": "warning",
      "message": "Only 3 items left in stock - Order soon!",
      "icon": "alert-triangle",
      "dismissible": true
    },
    {
      "type": "media",
      "name": "product_gallery",
      "title": "Product Images",
      "mediaType": "gallery",
      "layout": "grid",
      "columns": 4,
      "lightbox": true,
      "captions": true,
      "useSharedData": true,
      "sharedDataKey": "images"
    },
    {
      "type": "stats",
      "name": "product_metrics",
      "columns": 4,
      "colorful": true,
      "useSharedData": true,
      "sharedDataKey": "metrics",
      "metrics": [
        {"key": "views", "label": "Views", "meta": {"icon": "eye"}},
        {"key": "favorites", "label": "Favorites", "meta": {"icon": "heart"}},
        {"key": "rating", "label": "Rating", "meta": {"icon": "star"}},
        {"key": "reviews", "label": "Reviews", "meta": {"icon": "message-square"}}
      ]
    },
    {
      "type": "list",
      "name": "features",
      "title": "Key Features",
      "style": "bullet",
      "useSharedData": true,
      "sharedDataKey": "features"
    },
    {
      "type": "tabs",
      "name": "product_tabs",
      "variant": "underline",
      "fullWidth": true,
      "tabs": [
        {"key": "description", "label": "Description", "meta": {"icon": "file-text"}},
        {"key": "specifications", "label": "Specifications", "meta": {"icon": "list"}},
        {"key": "reviews", "label": "Reviews", "meta": {"icon": "star", "badge": "24"}}
      ]
    },
    {
      "type": "comment",
      "name": "reviews",
      "title": "Customer Reviews",
      "voting": true,
      "dataUrl": "/api/products/12345/reviews",
      "sortOrder": "popular"
    },
    {
      "type": "grid",
      "name": "related_products",
      "title": "Related Products",
      "columns": 4,
      "responsive": true,
      "dataUrl": "/api/products/12345/related"
    },
    {
      "type": "form",
      "name": "add_to_cart",
      "title": "Purchase Options",
      "method": "POST",
      "action": "/api/cart/add",
      "fields": [
        {
          "type": "select",
          "name": "quantity",
          "label": "Quantity",
          "meta": {"options": [1, 2, 3, 4, 5], "default": 1}
        },
        {
          "type": "select",
          "name": "size",
          "label": "Size",
          "meta": {"options": ["S", "M", "L", "XL"], "required": true}
        },
        {
          "type": "select",
          "name": "color",
          "label": "Color",
          "meta": {"options": ["Black", "White", "Blue"], "required": true}
        }
      ],
      "buttons": [
        {"type": "submit", "label": "Add to Cart", "meta": {"icon": "shopping-cart"}}
      ]
    }
  ]
}
*/

// ============================================================================
// ADVANCED FEATURES EXAMPLE
// ============================================================================
// Using Phase 1, 2, and 3 features (Caching, Events, Validation, i18n, etc.)

$advancedLayout = LayoutBuilder::create('dashboard', 'admin')
    // Enable caching for better performance
    ->cache(3600) // Cache for 1 hour
    ->cacheKey('admin-dashboard-{user}')
    
    // Add event listeners
    ->on('beforeRender', function ($layout) {
        // Log page view
    })
    ->on('afterRender', function ($layout) {
        // Track performance
    })
    
    // Stats with responsive columns
    ->statsSection('kpi_metrics')
    ->title('Key Performance Indicators')
    ->columns(4)
    ->responsiveColumns(['sm' => 1, 'md' => 2, 'lg' => 4]) // Auto-adjust
    ->visibleOn(['md', 'lg', 'xl']) // Hide on small screens
    ->dataUrl('/api/dashboard/kpis')
    
    // Chart with conditional display
    ->chartSection('revenue_chart')
    ->title('Revenue Chart')
    ->line()
    ->showWhen('user.role', '==', 'admin') // Only for admins
    ->dataUrl('/api/dashboard/revenue')
    
    // Table with validation and export
    ->tableSection('recent_orders')
    ->title('Recent Orders')
    ->dataUrl('/api/orders/recent')
    ->validate([
        'columns' => 'required|array',
        'dataUrl' => 'required|url'
    ])
    
    ->build();

/* ADVANCED JSON OUTPUT WITH FEATURES:
{
  "id": "dashboard",
  "view": "admin",
  "cache": {
    "enabled": true,
    "ttl": 3600,
    "key": "admin-dashboard-{user}"
  },
  "events": {
    "beforeRender": true,
    "afterRender": true
  },
  "sections": [
    {
      "type": "stats",
      "name": "kpi_metrics",
      "title": "Key Performance Indicators",
      "columns": 4,
      "responsive": {
        "columns": {"sm": 1, "md": 2, "lg": 4},
        "visibility": {"visibleOn": ["md", "lg", "xl"]}
      },
      "dataUrl": "/api/dashboard/kpis"
    },
    {
      "type": "chart",
      "name": "revenue_chart",
      "title": "Revenue Chart",
      "chartType": "line",
      "dataUrl": "/api/dashboard/revenue",
      "conditions": {
        "showWhen": {"field": "user.role", "operator": "==", "value": "admin"}
      }
    },
    {
      "type": "table",
      "name": "recent_orders",
      "title": "Recent Orders",
      "dataUrl": "/api/orders/recent",
      "validation": {
        "rules": {
          "columns": "required|array",
          "dataUrl": "required|url"
        }
      }
    }
  ]
}
*/

// ============================================================================
// USAGE IN LARAVEL CONTROLLER
// ============================================================================

/*
namespace App\Http\Controllers;

use Litepie\Layout\LayoutBuilder;

class ProductController extends Controller
{
    public function show($id)
    {
        $layout = LayoutBuilder::create('product', 'detail')
            ->sharedDataUrl("/api/products/{$id}")
            ->mediaSection('gallery')
                ->gallery()
                ->useSharedData(true, 'images')
            ->statsSection('metrics')
                ->useSharedData(true, 'metrics')
            ->build();
        
        // Return as JSON for API mode
        return response()->json($layout->toArray());
        
        // Or return as view for traditional mode
        // return view('layouts.product', compact('layout'));
    }
}
*/

// ============================================================================
// END OF ALL COMPONENTS REFERENCE
// ============================================================================
// 
// For more information:
// - Documentation: /docs/GUIDE.md
// - Advanced Features: /docs/ADVANCED_FEATURES.md
// - GitHub: https://github.com/Litepie/Layout
//
// All 19 component types demonstrated with complete JSON output examples!
// ============================================================================

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

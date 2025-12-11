<?php
// phpcs:ignoreFile

/**
 * Litepie Layout + Litepie/Form Integration Examples
 *
 * This file demonstrates how to use Litepie Layout with Litepie/Form
 * using the component-based structure (FormSection, TextSection, etc.)
 *
 * FormSection is designed to hold Litepie Form Field objects.
 * Create fields using Field::make('type', 'name', ['options'])
 * Then add them with addFormField() or addFormFields([])
 */

use Litepie\Layout\Field;
use Litepie\Layout\LayoutBuilder;

// ============================================================================
// Example 0: Nested Sections - Form with List Inside
// ============================================================================

// phpcs:disable Generic.WhiteSpace.ScopeIndent.IncorrectExact
$nestedLayout = LayoutBuilder::create('company', 'profile')
    ->sharedDataUrl('/api/companies/{id}')
    // Main company form section
    ->formSection('company_info')
        ->title('Company Information')
        ->description('Basic company details')
        ->columns(2)
        ->addFormFields([
            Field::make('text', 'company_name')
                ->label('Company Name')
                ->required()
                ->placeholder('Acme Corporation'),
            Field::make('text', 'registration_number')
                ->label('Registration Number')
                ->required(),
            Field::make('email', 'contact_email')
                ->label('Contact Email')
                ->required(),
            Field::make('tel', 'phone')
                ->label('Phone Number')
                ->placeholder('+1 (555) 000-0000'),
            Field::make('textarea', 'description')
                ->label('Company Description')
                ->attribute('rows', 3),
        ])
        // Nested list section inside the form
        ->listSection('office_locations')
            ->title('Office Locations')
            ->description('All company office locations')
            ->numbered()
            ->dataUrl('/api/companies/{id}/locations')
            ->addItem('headquarters', 'New York, NY - Headquarters', [
                'icon' => 'building',
                'meta' => [
                    'address'   => '123 Main St, New York, NY 10001',
                    'employees' => 150,
                ],
            ])
            ->addItem('branch_sf', 'San Francisco, CA - Branch Office', [
                'icon' => 'map-pin',
                'meta' => [
                    'address'   => '456 Market St, San Francisco, CA 94102',
                    'employees' => 75,
                ],
            ])
            ->addItem('branch_austin', 'Austin, TX - Branch Office', [
                'icon' => 'map-pin',
                'meta' => [
                    'address'   => '789 Congress Ave, Austin, TX 78701',
                    'employees' => 50,
                ],
            ])
        ->endSection() // End list section, return to main form
        // Another nested form section for settings inside the main form
        ->formSection('company_settings')
            ->title('Company Settings')
            ->columns(1)
            ->addFormFields([
                Field::make('select', 'timezone')
                    ->label('Timezone')
                    ->required()
                    ->options([
                        'America/New_York'    => 'Eastern Time (ET)',
                        'America/Chicago'     => 'Central Time (CT)',
                        'America/Los_Angeles' => 'Pacific Time (PT)',
                    ])
                    ->value('America/New_York'),
                Field::make('select', 'fiscal_year_start')
                    ->label('Fiscal Year Start')
                    ->options([
                        'january' => 'January',
                        'april'   => 'April',
                        'july'    => 'July',
                        'october' => 'October',
                    ])
                    ->value('january'),
                Field::make('checkbox', 'public_profile')
                    ->label('Make company profile public')
                    ->value(false)
                    ->help('Allow others to find your company'),
            ])
        ->endSection() // End settings form, return to main form
    ->endSection() // End main form, return to builder
    ->build();
// phpcs:enable Generic.WhiteSpace.ScopeIndent.IncorrectExact

// ============================================================================
// Example 1: User Profile Form
// ============================================================================

$profileLayout = LayoutBuilder::create('user', 'profile')
    ->sharedDataUrl('/api/users/{id}');

// Personal Information Form Section
$profileLayout->formSection('personal_info')
    ->title('Personal Information')
    ->description('Basic details about the user')
    ->columns(2) // Two-column form layout
    ->addFormFields([
        Field::make('text', 'first_name')
            ->label('First Name')
            ->required()
            ->placeholder('John'),
        Field::make('text', 'last_name')
            ->label('Last Name')
            ->required()
            ->placeholder('Doe'),
        Field::make('email', 'email')
            ->label('Email Address')
            ->required()
            ->help('We will never share your email'),
        Field::make('tel', 'phone')
            ->label('Phone Number')
            ->placeholder('+1 (555) 000-0000'),
    ]);

// Address Form Section
$profileLayout->formSection('address_info')
    ->title('Address Information')
    ->columns(2)
    ->addFormFields([
        Field::make('text', 'street')
            ->label('Street Address')
            ->required(),
        Field::make('text', 'city')
            ->label('City')
            ->required(),
        Field::make('select', 'state')
            ->label('State')
            ->required()
            ->options([
                'CA' => 'California',
                'NY' => 'New York',
                'TX' => 'Texas',
            ]),
        Field::make('text', 'zip')
            ->label('ZIP Code')
            ->required(),
    ]);

// Preferences Form Section
$profileLayout->formSection('preferences')
    ->title('User Preferences')
    ->columns(1)
    ->addFormFields([
        Field::make('select', 'theme')
            ->label('Theme')
            ->options([
                'light' => 'Light Mode',
                'dark'  => 'Dark Mode',
                'auto'  => 'Auto (System)',
            ])
            ->value('auto'),
        Field::make('checkbox', 'email_notifications')
            ->label('Email Notifications')
            ->help('Receive updates via email')
            ->value(true),
        Field::make('checkbox', 'sms_notifications')
            ->label('SMS Notifications')
            ->help('Receive updates via SMS')
            ->value(false),
    ]);

$profileLayout = $profileLayout->build();

// ============================================================================
// Example 2: Product Management Form
// ============================================================================

$productLayout = LayoutBuilder::create('product', 'edit')
    ->sharedDataUrl('/api/products/{id}');

// Product Details Form
$productLayout->formSection('basic_info')
    ->title('Product Details')
    ->description('Basic product information')
    ->columns(2)
    ->addFormFields([
        Field::make('text', 'name')
            ->label('Product Name')
            ->required(),
        Field::make('text', 'sku')
            ->label('SKU')
            ->required()
            ->help('Stock Keeping Unit'),
        Field::make('number', 'price')
            ->label('Price')
            ->required()
            ->attribute('min', 0)
            ->attribute('step', 0.01),
        Field::make('number', 'stock')
            ->label('Stock Quantity')
            ->required()
            ->attribute('min', 0),
        Field::make('select', 'category')
            ->label('Category')
            ->required()
            ->options([
                'electronics' => 'Electronics',
                'books'       => 'Books',
                'clothing'    => 'Clothing',
                'home'        => 'Home & Garden',
            ]),
        Field::make('select', 'status')
            ->label('Status')
            ->options([
                'draft'    => 'Draft',
                'active'   => 'Active',
                'inactive' => 'Inactive',
            ])
            ->value('draft'),
    ]);

// Product Description
$productLayout->formSection('description')
    ->title('Product Description')
    ->columns(1)
    ->addFormFields([
        Field::make('textarea', 'short_description')
            ->label('Short Description')
            ->attribute('rows', 3)
            ->attribute('maxlength', 255),
        Field::make('richtext', 'full_description')
            ->label('Full Description')
            ->help('Use the editor to format your description'),
    ]);

// Product Images
$productLayout->formSection('media')
    ->title('Product Media')
    ->columns(1)
    ->addFormFields([
        Field::make('image', 'primary_image')
            ->label('Primary Image')
            ->required()
            ->attribute('accept', 'image/*'),
        Field::make('gallery', 'additional_images')
            ->label('Additional Images')
            ->help('Upload up to 5 additional images'),
    ]);

// SEO Settings
$productLayout->formSection('seo')
    ->title('SEO Settings')
    ->description('Search engine optimization')
    ->columns(1)
    ->showWhen('user.role', '==', 'admin') // Conditional display
    ->addFormFields([
        Field::make('text', 'meta_title')
            ->label('Meta Title')
            ->attribute('maxlength', 60),
        Field::make('textarea', 'meta_description')
            ->label('Meta Description')
            ->attribute('rows', 3)
            ->attribute('maxlength', 160),
        Field::make('tags', 'keywords')
            ->label('Keywords')
            ->help('Add relevant keywords for SEO'),
    ]);

$productLayout = $productLayout->build();

// ============================================================================
// Example 3: Checkout Form with Conditional Visibility
// ============================================================================

$checkoutLayout = LayoutBuilder::create('checkout', 'form');

// Shipping Address Form
$checkoutLayout->formSection('shipping')
    ->title('Shipping Information')
    ->columns(2)
    ->addFormFields([
        Field::make('checkbox', 'different_billing')
            ->label('Use different billing address')
            ->value(false),
        Field::make('text', 'shipping_street')
            ->label('Street Address')
            ->required(),
        Field::make('text', 'shipping_city')
            ->label('City')
            ->required(),
        Field::make('select', 'shipping_state')
            ->label('State')
            ->required()
            ->options([
                'CA' => 'California',
                'NY' => 'New York',
                'TX' => 'Texas',
            ]),
        Field::make('text', 'shipping_zip')
            ->label('ZIP Code')
            ->required(),
    ]);

// Billing Address Form (conditional)
$checkoutLayout->formSection('billing')
    ->title('Billing Address')
    ->columns(2)
    ->showWhen('different_billing', '==', true) // Only show if checkbox is checked
    ->addFormFields([
        Field::make('text', 'billing_street')
            ->label('Street Address')
            ->required(),
        Field::make('text', 'billing_city')
            ->label('City')
            ->required(),
        Field::make('select', 'billing_state')
            ->label('State')
            ->required()
            ->options([
                'CA' => 'California',
                'NY' => 'New York',
                'TX' => 'Texas',
            ]),
        Field::make('text', 'billing_zip')
            ->label('ZIP Code')
            ->required(),
    ]);

// Payment Information
$checkoutLayout->formSection('payment')
    ->title('Payment Details')
    ->columns(1)
    ->addFormFields([
        Field::make('select', 'payment_method')
            ->label('Payment Method')
            ->required()
            ->options([
                'credit_card'   => 'Credit Card',
                'paypal'        => 'PayPal',
                'bank_transfer' => 'Bank Transfer',
            ]),
    ]);

$checkoutLayout = $checkoutLayout->build();

// ============================================================================
// Example 4: Multi-Section Dashboard
// ============================================================================

$dashboardLayout = LayoutBuilder::create('dashboard', 'overview')
    ->sharedDataUrl('/api/dashboard');

// Statistics Cards
$dashboardLayout->statsSection('key_metrics')
    ->title('Key Performance Indicators')
    ->columns(4)
    ->colorful()
    ->animated()
    ->useSharedData(true, 'metrics')
    ->addMetric('revenue', 'Total Revenue', [
        'icon'   => 'dollar-sign',
        'format' => 'currency',
    ])
    ->addMetric('users', 'Active Users', [
        'icon'   => 'users',
        'format' => 'number',
    ])
    ->addMetric('orders', 'Orders', [
        'icon' => 'shopping-cart',
    ])
    ->addMetric('satisfaction', 'Satisfaction', [
        'icon'   => 'smile',
        'format' => 'percentage',
    ]);

// Chart Section
$dashboardLayout->chartSection('revenue_chart')
    ->title('Revenue Trends')
    ->line()
    ->height(400)
    ->responsive()
    ->dataUrl('/api/dashboard/revenue')
    ->addSeries('revenue', 'Revenue', ['color' => '#3b82f6'])
    ->addSeries('profit', 'Profit', ['color' => '#10b981']);

// Recent Activity Table
$dashboardLayout->tableSection('recent_orders')
    ->title('Recent Orders')
    ->striped()
    ->hoverable()
    ->paginated(10)
    ->dataUrl('/api/orders/recent')
    ->addColumn('id', 'Order ID', ['width' => 100])
    ->addColumn('customer', 'Customer', ['sortable' => true])
    ->addColumn('total', 'Total', ['format' => 'currency'])
    ->addColumn('status', 'Status', ['badge' => true])
    ->addColumn('date', 'Date', ['sortable' => true]);

$dashboardLayout = $dashboardLayout->build();

// ============================================================================
// Example 5: Blog Post Editor with Rich Content
// ============================================================================

$postLayout = LayoutBuilder::create('blog', 'post-edit')
    ->sharedDataUrl('/api/posts/{id}');

// Alert Section
$postLayout->alertSection('draft_notice')
    ->info()
    ->title('Draft Post')
    ->message('This post is currently in draft mode and not visible to the public.')
    ->icon('info')
    ->dismissible();

// Post Content Form
$postLayout->formSection('content')
    ->title('Post Content')
    ->columns(1)
    ->addFormFields([
        Field::make('text', 'title')
            ->label('Post Title')
            ->required()
            ->attribute('maxlength', 200),
        Field::make('text', 'slug')
            ->label('URL Slug')
            ->required()
            ->help('Used in the post URL'),
        Field::make('richtext', 'content')
            ->label('Post Content')
            ->required()
            ->attribute('height', 500),
        Field::make('select', 'category')
            ->label('Category')
            ->required()
            ->options([
                'technology' => 'Technology',
                'business'   => 'Business',
                'lifestyle'  => 'Lifestyle',
            ]),
        Field::make('tags', 'tags')
            ->label('Tags')
            ->help('Add relevant tags'),
    ]);

// Featured Image
$postLayout->formSection('featured_image')
    ->title('Featured Image')
    ->columns(1)
    ->addFormFields([
        Field::make('image', 'image')
            ->label('Featured Image')
            ->attribute('accept', 'image/*')
            ->help('Recommended size: 1200x630px'),
    ]);

// Publishing Options
$postLayout->formSection('publishing')
    ->title('Publishing Options')
    ->columns(2)
    ->addFormFields([
        Field::make('select', 'status')
            ->label('Status')
            ->options([
                'draft'     => 'Draft',
                'published' => 'Published',
                'scheduled' => 'Scheduled',
            ])
            ->value('draft'),
        Field::make('datetime-local', 'publish_at')
            ->label('Publish Date')
            ->help('Leave empty to publish immediately'),
        Field::make('checkbox', 'featured')
            ->label('Featured Post')
            ->value(false),
        Field::make('checkbox', 'allow_comments')
            ->label('Allow Comments')
            ->value(true),
    ]);

$postLayout = $postLayout->build();

// ============================================================================
// Example 6: User Registration with Wizard/Tabs
// ============================================================================

$registrationLayout = LayoutBuilder::create('auth', 'register');

// Tabs for multi-step registration
$registrationLayout->tabsSection('registration_steps')
    ->title('Create Your Account')
    ->variant('pills')
    ->defaultTab('account')
    ->addTab('account', 'Account', [], ['icon' => 'user'])
    ->addTab('profile', 'Profile', [], ['icon' => 'id-card'])
    ->addTab('preferences', 'Preferences', [], ['icon' => 'settings']);

// Step 1: Account Credentials
$registrationLayout->formSection('account_form')
    ->title('Account Credentials')
    ->columns(2)
    ->addFormFields([
        Field::make('text', 'username')
            ->label('Username')
            ->required()
            ->attribute('minlength', 3)
            ->attribute('maxlength', 20)
            ->help('Choose a unique username'),
        Field::make('email', 'email')
            ->label('Email Address')
            ->required()
            ->help('We will send verification email'),
        Field::make('password', 'password')
            ->label('Password')
            ->required()
            ->attribute('minlength', 8)
            ->help('Use at least 8 characters with letters and numbers'),
        Field::make('password', 'password_confirmation')
            ->label('Confirm Password')
            ->required(),
    ]);

// Step 2: Profile Information
$registrationLayout->formSection('profile_form')
    ->title('Profile Information')
    ->columns(2)
    ->addFormFields([
        Field::make('text', 'first_name')
            ->label('First Name')
            ->required(),
        Field::make('text', 'last_name')
            ->label('Last Name')
            ->required(),
        Field::make('tel', 'phone')
            ->label('Phone Number')
            ->placeholder('+1 (555) 000-0000'),
        Field::make('date', 'birth_date')
            ->label('Date of Birth'),
        Field::make('image', 'avatar')
            ->label('Profile Picture')
            ->help('Upload a profile picture'),
    ]);

// Step 3: Preferences & Terms
$registrationLayout->formSection('preferences_form')
    ->title('Preferences')
    ->columns(1)
    ->addFormFields([
        Field::make('select', 'language')
            ->label('Preferred Language')
            ->options([
                'en' => 'English',
                'es' => 'Spanish',
                'fr' => 'French',
            ])
            ->value('en'),
        Field::make('checkbox', 'agree_terms')
            ->label('I agree to the Terms of Service and Privacy Policy')
            ->required(),
        Field::make('checkbox', 'subscribe_newsletter')
            ->label('Subscribe to newsletter')
            ->value(false),
    ]);

$registrationLayout = $registrationLayout->build();

// ============================================================================
// Example 7: Settings Page with Multiple Sections
// ============================================================================

$settingsLayout = LayoutBuilder::create('settings', 'view');

// Display Settings Card
$settingsLayout->cardSection('display_card')
    ->title('Display Settings')
    ->subtitle('Customize the look and feel')
    ->elevated();

// Color Preferences Form
$settingsLayout->formSection('colors')
    ->title('Color Scheme')
    ->columns(3)
    ->addFormFields([
        Field::make('color', 'primary_color')
            ->label('Primary Color')
            ->value('#3490dc'),
        Field::make('color', 'secondary_color')
            ->label('Secondary Color')
            ->value('#ffed4e'),
        Field::make('color', 'accent_color')
            ->label('Accent Color')
            ->value('#38c172'),
    ]);

// Typography Form
$settingsLayout->formSection('typography')
    ->title('Typography')
    ->columns(2)
    ->addFormFields([
        Field::make('select', 'font_family')
            ->label('Font Family')
            ->options([
                'inter'    => 'Inter',
                'roboto'   => 'Roboto',
                'openSans' => 'Open Sans',
                'lato'     => 'Lato',
            ])
            ->value('inter'),
        Field::make('select', 'font_size')
            ->label('Base Font Size')
            ->options([
                'small'  => 'Small',
                'medium' => 'Medium',
                'large'  => 'Large',
            ])
            ->value('medium'),
    ]);

// Layout Spacing
$settingsLayout->formSection('spacing')
    ->title('Layout Spacing')
    ->columns(1)
    ->addFormFields([
        Field::make('select', 'spacing_scale')
            ->label('Spacing Scale')
            ->options([
                'compact'     => 'Compact',
                'normal'      => 'Normal',
                'comfortable' => 'Comfortable',
            ])
            ->value('normal'),
    ]);

// Notification Preferences
$settingsLayout->formSection('notifications')
    ->title('Notification Preferences')
    ->description('Manage how you receive notifications')
    ->columns(1)
    ->addFormFields([
        Field::make('checkbox', 'email_notifications')
            ->label('Email Notifications')
            ->value(true)
            ->help('Receive notifications via email'),
        Field::make('checkbox', 'push_notifications')
            ->label('Push Notifications')
            ->value(true)
            ->help('Receive browser push notifications'),
        Field::make('checkbox', 'sms_notifications')
            ->label('SMS Notifications')
            ->value(false)
            ->help('Receive notifications via SMS'),
        Field::make('select', 'notification_frequency')
            ->label('Notification Frequency')
            ->options([
                'realtime' => 'Real-time',
                'hourly'   => 'Hourly Digest',
                'daily'    => 'Daily Digest',
                'weekly'   => 'Weekly Digest',
            ])
            ->value('realtime'),
    ]);

$settingsLayout = $settingsLayout->build();

// ============================================================================
// Example 8: Complete API Response Example
// ============================================================================

$apiLayout = LayoutBuilder::create('api-demo', 'showcase')
    ->sharedDataUrl('/api/showcase/data');

// Hero Card
$apiLayout->cardSection('hero')
    ->title('Welcome to Our Platform')
    ->subtitle('Everything you need in one place')
    ->image('/images/hero-banner.jpg')
    ->imagePosition('top')
    ->elevated()
    ->hoverable();

// Feature List
$apiLayout->listSection('features')
    ->title('Key Features')
    ->description('What makes us different')
    ->bullet()
    ->addItem('Fast', 'Lightning-fast performance', ['icon' => 'zap', 'checked' => true])
    ->addItem('Secure', '256-bit encryption', ['icon' => 'shield', 'checked' => true])
    ->addItem('Scalable', 'Grows with your business', ['icon' => 'trending-up', 'checked' => true]);

// Statistics Grid
$apiLayout->gridSection('stats_grid')
    ->title('Our Impact')
    ->columns(3)
    ->responsive()
    ->useSharedData(true, 'stats');

// Contact Form
$apiLayout->formSection('contact')
    ->title('Get in Touch')
    ->description('We would love to hear from you')
    ->columns(2)
    ->addFormFields([
        Field::make('text', 'name')
            ->label('Full Name')
            ->required(),
        Field::make('email', 'email')
            ->label('Email Address')
            ->required(),
        Field::make('tel', 'phone')
            ->label('Phone Number'),
        Field::make('select', 'subject')
            ->label('Subject')
            ->required()
            ->options([
                'general' => 'General Inquiry',
                'support' => 'Technical Support',
                'sales'   => 'Sales Question',
            ]),
        Field::make('textarea', 'message')
            ->label('Message')
            ->required()
            ->attribute('rows', 5)
            ->placeholder('Enter your message...'),
    ]);

$apiLayout = $apiLayout->build();

// Output as JSON for API
$jsonOutput = json_encode($apiLayout->toArray(), JSON_PRETTY_PRINT);

// Example JSON structure:
/*
{
  "id": "api-demo",
  "view": "showcase",
  "sharedDataUrl": "/api/showcase/data",
  "sections": [
    {
      "type": "card",
      "name": "hero",
      "title": "Welcome to Our Platform",
      "subtitle": "Everything you need in one place",
      "image": "/images/hero-banner.jpg",
      "imagePosition": "top",
      "elevated": true,
      "hoverable": true
    },
    {
      "type": "list",
      "name": "features",
      "title": "Key Features",
      "description": "What makes us different",
      "style": "bullet",
      "items": [...]
    },
    {
      "type": "grid",
      "name": "stats_grid",
      "title": "Our Impact",
      "columns": 3,
      "responsive": true,
      "useSharedData": true,
      "sharedDataKey": "stats"
    },
    {
      "type": "form",
      "name": "contact",
      "title": "Get in Touch",
      "description": "We would love to hear from you",
      "columns": 2,
      "method": "POST",
      "action": "/api/contact",
      "fields": [...]
    }
  ]
}
*/

// ============================================================================
// Example 9: Using with Laravel Controllers
// ============================================================================

/*
namespace App\Http\Controllers;

use Litepie\Layout\Field;
use Litepie\Layout\LayoutBuilder;

class UserController extends Controller
{
    public function edit($id)
    {
        $layout = LayoutBuilder::create('user', 'edit')
            ->sharedDataUrl("/api/users/{$id}");

        $layout->formSection('profile')
            ->title('Edit Profile')
            ->addFormFields([
                Field::make('text', 'name')->label('Name')->required(),
                Field::make('email', 'email')->label('Email')->required(),
            ]);

        $layout = $layout->build();

        // Return as JSON for API mode
        return response()->json($layout->toArray());
    }

    public function update(Request $request, $id)
    {
        // Validate using form structure
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        // Update user...

        return response()->json(['message' => 'Profile updated successfully']);
    }
}
*/

// ============================================================================
// Tips for Integration
// ============================================================================

/*
1. Use sharedDataUrl for data fetching:
   - Single endpoint for all section data
   - Reduces API calls
   - Sections can reference shared data with useSharedData()

2. Component Types Available:
   - formSection: Forms with fields and validation
   - textSection: Rich text content
   - cardSection: Content cards
   - listSection: Lists (bullet, numbered, checklist)
   - tableSection: Data tables
   - gridSection: Card grids
   - statsSection: Statistics/metrics
   - chartSection: Data visualization
   - mediaSection: Images/videos
   - timelineSection: Activity timelines
   - tabsSection: Tabbed content
   - accordionSection: Collapsible panels
   - alertSection: Notifications
   - badgeSection: Tags/labels
   - And more...

3. Conditional Display:
   - Use showWhen() / hideWhen() on any section
   - Based on user data or permissions
   - Client-side reactivity

4. Responsive Design:
   - Use responsiveColumns() for adaptive layouts
   - visibleOn() / hiddenOn() for device-specific display
   - Breakpoints: xs, sm, md, lg, xl, 2xl

5. Caching:
   - Enable with ->cache(true, ttl)
   - Invalidate with ->invalidateCache()
   - Per-user caching with dynamic cache keys

6. Events:
   - Listen to lifecycle events (beforeRender, afterRender)
   - Track user interactions
   - Log performance metrics
*/

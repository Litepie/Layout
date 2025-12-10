<?php

/**
 * Litepie Layout + Litepie/Form Integration Examples
 *
 * This file demonstrates how to use Litepie Layout with Litepie/Form
 * to create structured forms with sections and subsections.
 */

use Litepie\Form\Field;
use Litepie\Layout\Facades\Layout;
use Litepie\Layout\LayoutBuilder;

// ============================================================================
// Example 1: User Profile Form
// ============================================================================

$profileLayout = LayoutBuilder::create('user', 'profile');

$profileLayout->section('personal_information')
    ->label('Personal Information')
    ->description('Basic details about the user')
    ->icon('user')
    ->columns(2) // Two-column layout for subsections
    ->subsection('basic_details')
    ->label('Basic Details')
    ->columns(2) // Two-column layout for fields
    ->addFormFields([
        Field::make('text', 'first_name')
            ->label('First Name')
            ->required()
            ->maxLength(50),

        Field::make('text', 'last_name')
            ->label('Last Name')
            ->required()
            ->maxLength(50),

        Field::make('email', 'email')
            ->label('Email Address')
            ->required()
            ->help('We will never share your email'),

        Field::make('phone', 'phone')
            ->label('Phone Number')
            ->placeholder('+1 (555) 000-0000'),
    ])
    ->endSubsection()
    ->subsection('address')
    ->label('Address Information')
    ->addFormFields([
        Field::make('text', 'street')
            ->label('Street Address')
            ->required(),

        Field::make('text', 'city')
            ->label('City')
            ->required(),

        Field::make('select', 'state')
            ->label('State')
            ->options([
                'CA' => 'California',
                'NY' => 'New York',
                'TX' => 'Texas',
            ])
            ->required(),

        Field::make('text', 'zip')
            ->label('ZIP Code')
            ->required()
            ->maxLength(10),
    ])
    ->endSubsection()
    ->endSection();

$profileLayout->section('preferences')
    ->label('User Preferences')
    ->subsection('settings')
    ->label('Settings')
    ->addFormFields([
        Field::make('select', 'theme')
            ->label('Theme')
            ->options([
                'light' => 'Light Mode',
                'dark' => 'Dark Mode',
                'auto' => 'Auto (System)',
            ])
            ->default('auto'),

        Field::make('checkbox', 'email_notifications')
            ->label('Email Notifications')
            ->help('Receive updates via email')
            ->default(true),

        Field::make('checkbox', 'sms_notifications')
            ->label('SMS Notifications')
            ->help('Receive updates via SMS')
            ->default(false),
    ])
    ->endSubsection()
    ->endSection();

$layout = $profileLayout->build();

// ============================================================================
// Example 2: Product Management Form with Authorization
// ============================================================================

$productLayout = Layout::for('product', 'edit')
    ->section('product_details')
    ->label('Product Details')
    ->permissions(['manage-products']) // Only users with permission
    ->subsection('basic_info')
    ->label('Basic Information')
    ->columns(2)
    ->addFormFields([
        Field::make('text', 'name')
            ->label('Product Name')
            ->required(),

        Field::make('text', 'sku')
            ->label('SKU')
            ->required()
            ->help('Stock Keeping Unit'),

        Field::make('currency', 'price')
            ->label('Price')
            ->required()
            ->min(0),

        Field::make('number', 'stock')
            ->label('Stock Quantity')
            ->required()
            ->min(0),

        Field::make('select', 'category')
            ->label('Category')
            ->options([
                'electronics' => 'Electronics',
                'books' => 'Books',
                'clothing' => 'Clothing',
                'home' => 'Home & Garden',
            ])
            ->required(),

        Field::make('select', 'status')
            ->label('Status')
            ->options([
                'draft' => 'Draft',
                'active' => 'Active',
                'inactive' => 'Inactive',
            ])
            ->default('draft'),
    ])
    ->endSubsection()
    ->subsection('description')
    ->label('Description')
    ->addFormFields([
        Field::make('textarea', 'short_description')
            ->label('Short Description')
            ->rows(3)
            ->maxLength(255),

        Field::make('richtext', 'full_description')
            ->label('Full Description')
            ->help('Use the editor to format your description'),
    ])
    ->endSubsection()
    ->endSection()
    ->section('media')
    ->label('Product Media')
    ->subsection('images')
    ->label('Images')
    ->addFormFields([
        Field::make('image', 'primary_image')
            ->label('Primary Image')
            ->required()
            ->maxSize(5120), // 5MB

        Field::make('gallery', 'additional_images')
            ->label('Additional Images')
            ->help('Upload up to 5 additional images')
            ->maxFiles(5),
    ])
    ->endSubsection()
    ->endSection()
    ->section('seo')
    ->label('SEO Settings')
    ->roles(['admin', 'seo-manager']) // Only specific roles
    ->subsection('meta')
    ->label('Meta Information')
    ->addFormFields([
        Field::make('text', 'meta_title')
            ->label('Meta Title')
            ->maxLength(60),

        Field::make('textarea', 'meta_description')
            ->label('Meta Description')
            ->rows(3)
            ->maxLength(160),

        Field::make('tags', 'keywords')
            ->label('Keywords')
            ->help('Add relevant keywords for SEO'),
    ])
    ->endSubsection()
    ->endSection()
    ->build();

// Resolve authorization for current user
$productLayout->resolveAuthorization(auth()->user());

// Get only authorized sections
$authorizedSections = $productLayout->getAuthorizedSections();

// ============================================================================
// Example 3: Conditional Visibility
// ============================================================================

$checkoutLayout = LayoutBuilder::create('checkout', 'form');

$checkoutLayout->section('shipping')
    ->label('Shipping Information')
    ->subsection('shipping_address')
    ->label('Shipping Address')
    ->addFormFields([
        Field::make('checkbox', 'different_billing')
            ->label('Billing address is different from shipping address')
            ->default(false),

        Field::make('text', 'shipping_street')
            ->label('Street Address')
            ->required(),

        Field::make('text', 'shipping_city')
            ->label('City')
            ->required(),
    ])
    ->endSubsection()
    ->subsection('billing_address')
    ->label('Billing Address')
    ->visibleWhen('different_billing', '==', true) // Conditional visibility
    ->addFormFields([
        Field::make('text', 'billing_street')
            ->label('Street Address')
            ->required(),

        Field::make('text', 'billing_city')
            ->label('City')
            ->required(),
    ])
    ->endSubsection()
    ->endSection();

$layout = $checkoutLayout->build();

// ============================================================================
// Example 4: Using LayoutFormAdapter
// ============================================================================

use Litepie\Layout\Adapters\LayoutFormAdapter;

// Get all form fields from the layout
$allFields = $layout->getAllFormFields();

// Extract validation rules
$validationRules = LayoutFormAdapter::extractValidationRules($allFields);

// Example: ['first_name' => ['required', 'max:50'], 'email' => ['required', 'email'], ...]

// Generate default form data
$defaultData = LayoutFormAdapter::generateDefaultData($allFields);

// Example: ['theme' => 'auto', 'email_notifications' => true, ...]

// Validate user input
$validator = validator($request->all(), $validationRules);

if ($validator->fails()) {
    return back()->withErrors($validator)->withInput();
}

// ============================================================================
// Example 5: Action Modals with Form Fields
// ============================================================================

use Litepie\Layout\ActionModal;

$moderationLayout = LayoutBuilder::create('post', 'moderation');

$moderationLayout->section('post_actions')
    ->label('Post Moderation')
    ->action('Approve', '/posts/{id}/approve', [
        'class' => 'btn btn-success',
        'method' => 'POST',
    ])
    ->action('Reject', '/posts/{id}/reject', [
        'class' => 'btn btn-danger',
        'modal' => 'reject-modal',
    ])
    ->addModal(
        ActionModal::make('reject-modal')
            ->title('Reject Post')
            ->description('Please provide a reason for rejecting this post.')
            ->addFormFields([
                Field::make('select', 'reason')
                    ->label('Rejection Reason')
                    ->options([
                        'spam' => 'Spam Content',
                        'inappropriate' => 'Inappropriate Content',
                        'copyright' => 'Copyright Violation',
                        'other' => 'Other',
                    ])
                    ->required(),

                Field::make('textarea', 'details')
                    ->label('Additional Details')
                    ->rows(4)
                    ->help('Provide more context about the rejection'),

                Field::make('checkbox', 'notify_user')
                    ->label('Notify user about rejection')
                    ->default(true),
            ])
            ->submitLabel('Reject Post')
            ->submitClass('btn btn-danger')
            ->cancelLabel('Cancel')
    )
    ->subsection('post_content')
    ->label('Post Content')
    ->addFormField(
        Field::make('textarea', 'content')
            ->label('Content')
            ->readonly()
    )
    ->endSubsection()
    ->endSection();

$layout = $moderationLayout->build();

// ============================================================================
// Example 6: Working with Layout Data
// ============================================================================

// Convert layout to array
$layoutArray = $layout->toArray();

// Get authorized data only
$authorizedData = $layout->toAuthorizedArray();

// Get specific field
$emailField = $layout->getFormFieldByName('email');

// Get field from specific location
$streetField = $layout->getFormField('personal_information', 'address', 'street');

// Cache the layout
Layout::clearCache('user', 'profile');

// ============================================================================
// Example 7: Multi-Column Layouts
// ============================================================================

$dashboardLayout = LayoutBuilder::create('dashboard', 'settings');

$dashboardLayout->section('display_settings')
    ->label('Display Settings')
    ->columns(3) // Three columns for subsections
    ->gap('lg') // Large gap between columns
    ->subsection('colors')
    ->label('Colors')
    ->addFormFields([
        Field::make('color', 'primary_color')
            ->label('Primary Color')
            ->default('#3490dc'),

        Field::make('color', 'secondary_color')
            ->label('Secondary Color')
            ->default('#ffed4e'),
    ])
    ->endSubsection()
    ->subsection('typography')
    ->label('Typography')
    ->addFormFields([
        Field::make('select', 'font_family')
            ->label('Font Family')
            ->options([
                'inter' => 'Inter',
                'roboto' => 'Roboto',
                'openSans' => 'Open Sans',
            ]),
    ])
    ->endSubsection()
    ->subsection('spacing')
    ->label('Spacing')
    ->addFormFields([
        Field::make('select', 'spacing_scale')
            ->label('Spacing Scale')
            ->options([
                'compact' => 'Compact',
                'normal' => 'Normal',
                'comfortable' => 'Comfortable',
            ]),
    ])
    ->endSubsection()
    ->endSection();

$layout = $dashboardLayout->build();

// ============================================================================
// Example 8: Complete Registration Form
// ============================================================================

$registrationLayout = Layout::for('auth', 'register')
    ->section('account_creation')
    ->label('Create Your Account')
    ->subsection('credentials')
    ->label('Account Credentials')
    ->columns(2)
    ->addFormFields([
        Field::make('text', 'username')
            ->label('Username')
            ->required()
            ->minLength(3)
            ->maxLength(20)
            ->help('Choose a unique username'),

        Field::make('email', 'email')
            ->label('Email Address')
            ->required()
            ->help('We will send verification email'),

        Field::make('password', 'password')
            ->label('Password')
            ->required()
            ->minLength(8)
            ->help('Use at least 8 characters with letters and numbers'),

        Field::make('password', 'password_confirmation')
            ->label('Confirm Password')
            ->required(),
    ])
    ->endSubsection()
    ->subsection('terms')
    ->label('Terms & Conditions')
    ->addFormFields([
        Field::make('checkbox', 'agree_terms')
            ->label('I agree to the Terms of Service and Privacy Policy')
            ->required(),

        Field::make('checkbox', 'subscribe_newsletter')
            ->label('Subscribe to newsletter')
            ->default(false),
    ])
    ->endSubsection()
    ->endSection()
    ->build();

// Extract validation rules for form submission
$fields = $registrationLayout->getAllFormFields();
$rules = LayoutFormAdapter::extractValidationRules($fields);

// Use in controller
// $validated = $request->validate($rules);

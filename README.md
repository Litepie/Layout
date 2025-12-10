# Litepie Layout

A flexible layout structure package for Laravel that provides a fluent API for organizing content into sections and subsections. Designed to work seamlessly with [Litepie/Form](https://github.com/Litepie/Form) for building structured forms and content layouts.

[![Latest Version](https://img.shields.io/packagist/v/litepie/layout.svg?style=flat-square)](https://packagist.org/packages/litepie/layout)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/litepie/layout/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/litepie/layout/actions?query=workflow%3Atests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/litepie/layout.svg?style=flat-square)](https://packagist.org/packages/litepie/layout)
[![License](https://img.shields.io/packagist/l/litepie/layout.svg?style=flat-square)](https://packagist.org/packages/litepie/layout)

## Overview

Litepie Layout is a **layout structure organizer** for Laravel applications. It provides a hierarchical way to organize content into **sections** and **subsections**, with built-in support for authorization, caching, actions, and modals. 

This package focuses on **structure and organization**, while delegating form field management to [Litepie/Form](https://github.com/Litepie/Form), creating a clean separation of concerns.

## Table of Contents

- [Overview](#overview)
- [Key Features](#key-features)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Usage](#usage)
  - [Basic Layout Structure](#basic-layout-structure)
  - [Working with Litepie/Form](#working-with-litepieform)
  - [Authorization & Permissions](#authorization--permissions)
  - [Action Buttons & Modals](#action-buttons--modals)
  - [Multi-Column Layouts](#multi-column-layouts)
  - [Conditional Visibility](#conditional-visibility)
- [Integration with Litepie/Form](#integration-with-litepieform)
- [API Reference](#api-reference)
- [Advanced Usage](#advanced-usage)
- [Migration from v1.x](#migration-from-v1x)
- [Examples](#examples)
- [Documentation](#documentation)

## Quick Start

New to Litepie Layout? Check out the [QUICKSTART.md](QUICKSTART.md) guide for a 5-minute introduction.

## Key Features

- 🏗️ **Hierarchical Structure** - Organize content into sections → subsections → fields
- 🔐 **Authorization** - Permission and role-based visibility control
- ⚡ **User-based Caching** - Fast retrieval with no database required
- 🎨 **Column Layouts** - Multi-column layouts for sections and subsections
- 👁️ **Conditional Visibility** - Show/hide sections based on conditions
- 🔘 **Action Buttons** - Add actions to sections and subsections
- 📋 **Action Modals** - Collect user input before action execution
- 🔗 **Litepie/Form Integration** - Seamlessly works with form field instances
- 💾 **Fluent API** - Chainable, intuitive builder pattern

## Installation

### Requirements

- PHP 8.1 or higher
- Laravel 10.0 or higher
- Litepie/Form package

### Install via Composer

```bash
composer require litepie/layout litepie/form
```

### Publish Configuration (Optional)

```bash
php artisan vendor:publish --provider="Litepie\Layout\LayoutServiceProvider"
```

## Usage

### Basic Layout Structure

```php
use Litepie\Layout\Facades\Layout;
use Litepie\Form\Field;

// Define a layout structure for a user profile module
$layout = Layout::for('user', 'profile')
    ->section('personal_info')
        ->label('Personal Information')
        ->description('Basic personal details')
        ->subsection('basic')
            ->label('Basic Details')
            ->addFormFields([
                Field::make('text', 'first_name')
                    ->label('First Name')
                    ->required()
                    ->maxLength(50),
                Field::make('text', 'last_name')
                    ->label('Last Name')
                    ->required(),
                Field::make('email', 'email')
                    ->label('Email Address')
                    ->required(),
            ])
        ->endSubsection()
        ->subsection('contact')
            ->label('Contact Information')
            ->addFormField(
                Field::make('text', 'phone')
                    ->label('Phone Number')
                    ->placeholder('+1 (555) 000-0000')
            )
        ->endSubsection()
    ->endSection()
    ->section('preferences')
        ->label('User Preferences')
        ->subsection('settings')
            ->addFormFields([
                Field::make('select', 'theme')
                    ->label('Theme')
                    ->options(['light' => 'Light', 'dark' => 'Dark'])
                    ->default('light'),
                Field::make('checkbox', 'notifications')
                    ->label('Enable Notifications')
                    ->default(true),
            ])
        ->endSubsection()
    ->endSection()
    ->build();

// Get the layout from cache
$userLayout = Layout::get('user', 'profile');

// Get all form fields from the layout
$formFields = $userLayout->getAllFormFields();

// Clear cache for specific user
Layout::clearCache('user', 'profile', $userId);
```

### Working with Litepie/Form

```php
use Litepie\Layout\LayoutBuilder;
use Litepie\Form\Field;

$builder = LayoutBuilder::create('product', 'edit');

$builder->section('product_details')
    ->label('Product Information')
    ->subsection('basic')
        ->label('Basic Information')
        ->columns(2) // Two-column layout
        ->addFormFields([
            Field::make('text', 'name')
                ->label('Product Name')
                ->required(),
            Field::make('text', 'sku')
                ->label('SKU')
                ->required(),
            Field::make('currency', 'price')
                ->label('Price')
                ->required(),
            Field::make('select', 'category')
                ->label('Category')
                ->options(['electronics' => 'Electronics', 'books' => 'Books']),
        ])
    ->endSubsection();

$layout = $builder->build();
```

### Authorization & Permissions

```php
use Litepie\Layout\Facades\Layout;
use Litepie\Form\Field;

$layout = Layout::for('admin', 'settings')
    ->section('security')
        ->label('Security Settings')
        ->permissions(['manage-security']) // Only users with this permission
        ->subsection('api_keys')
            ->label('API Keys')
            ->roles(['admin', 'super-admin']) // Only these roles can see
            ->addFormField(
                Field::make('text', 'api_key')
                    ->label('API Key')
                    ->readonly()
            )
        ->endSubsection()
    ->endSection()
    ->build();

// Resolve authorization for a specific user
$layout->resolveAuthorization($user);

// Get only authorized sections
$authorizedSections = $layout->getAuthorizedSections();

// Get layout as array with only authorized content
$authorizedData = $layout->toAuthorizedArray();
```


### Action Buttons & Modals

```php
use Litepie\Layout\LayoutBuilder;
use Litepie\Form\Field;

$layout = LayoutBuilder::create('post', 'moderation')
    ->section('actions')
        ->label('Post Actions')
        ->action('Approve', '/posts/{id}/approve', ['class' => 'btn-success'])
        ->action('Reject', '/posts/{id}/reject', [
            'class' => 'btn-danger',
            'modal' => 'reject-modal'
        ])
        ->addModal(
            \Litepie\Layout\ActionModal::make('reject-modal')
                ->title('Reject Post')
                ->description('Please provide a reason for rejection.')
                ->addFormFields([
                    Field::make('select', 'reason')
                        ->label('Reason')
                        ->options([
                            'spam' => 'Spam',
                            'inappropriate' => 'Inappropriate',
                            'other' => 'Other'
                        ])
                        ->required(),
                    Field::make('textarea', 'details')
                        ->label('Details')
                        ->rows(4)
                ])
                ->submitLabel('Reject Post')
                ->submitClass('btn btn-danger')
        )
        ->subsection('content')
            ->label('Post Content')
            ->addFormField(
                Field::make('textarea', 'content')
                    ->label('Content')
                    ->readonly()
            )
        ->endSubsection()
    ->endSection()
    ->build();
```

### Multi-Column Layouts

```php
use Litepie\Layout\LayoutBuilder;
use Litepie\Form\Field;

$builder = LayoutBuilder::create('settings', 'dashboard');

$builder->section('display_settings')
    ->label('Display Settings')
    ->columns(3) // Three-column layout for subsections
    ->subsection('colors')
        ->label('Colors')
        ->addFormFields([
            Field::make('color', 'primary_color')->label('Primary Color'),
            Field::make('color', 'secondary_color')->label('Secondary Color'),
        ])
    ->endSubsection()
    ->subsection('typography')
        ->label('Typography')
        ->addFormFields([
            Field::make('select', 'font_family')->label('Font Family'),
            Field::make('number', 'font_size')->label('Font Size'),
        ])
    ->endSubsection()
    ->subsection('spacing')
        ->label('Spacing')
        ->addFormFields([
            Field::make('select', 'spacing')->label('Spacing'),
        ])
    ->endSubsection()
->endSection();

$layout = $builder->build();
```

### Conditional Visibility

```php
use Litepie\Layout\Facades\Layout;
use Litepie\Form\Field;

$layout = Layout::for('checkout', 'form')
    ->section('payment')
        ->label('Payment Information')
        ->subsection('billing')
            ->label('Billing Address')
            ->visibleWhen('shipping_address', '!=', 'billing_address') // Show only when different
            ->addFormFields([
                Field::make('text', 'billing_street')->label('Street'),
                Field::make('text', 'billing_city')->label('City'),
                Field::make('text', 'billing_zip')->label('ZIP Code'),
            ])
        ->endSubsection()
    ->endSection()
    ->build();
```

## Integration with Litepie/Form

This package is designed to work seamlessly with [Litepie/Form](https://github.com/Litepie/Form). Use `Field::make()` from Litepie/Form to create fields, and add them to subsections using `addFormField()` or `addFormFields()`.

```php
use Litepie\Form\Field;
use Litepie\Layout\Adapters\LayoutFormAdapter;

// Create fields using Litepie/Form
$nameField = Field::make('text', 'name')->label('Name')->required();
$emailField = Field::make('email', 'email')->label('Email')->required();

// Add to layout subsection
$subsection->addFormFields([$nameField, $emailField]);

// Extract validation rules using the adapter
$fields = $layout->getAllFormFields();
$rules = LayoutFormAdapter::extractValidationRules($fields);

// Generate default data
$defaultData = LayoutFormAdapter::generateDefaultData($fields);
```

## API Reference

### Layout Class

#### Methods
- `getAllFormFields()` - Get all Litepie/Form field instances
- `getFormFieldByName(string $name)` - Get field by name
- `getFormField(string $section, string $subsection, string $field)` - Get specific field
- `resolveAuthorization($user)` - Resolve authorization for user
- `getAuthorizedSections()` - Get only authorized sections
- `toAuthorizedArray()` - Array with only authorized content
- `toArray()` - Convert entire layout to array
- `render()` - Alias for toArray()

### Section Class

#### Methods
- `label(string $label)` - Set section label
- `description(string $description)` - Set section description
- `icon(string $icon)` - Set section icon
- `subsection(string $name)` - Create new subsection
- `columns(int $columns)` - Set multi-column layout
- `gap(string $gap)` - Set gap between columns (xs, sm, md, lg, xl)
- `permissions(array|string $permissions)` - Set required permissions
- `roles(array|string $roles)` - Set required roles
- `canSee(Closure $callback)` - Custom authorization callback
- `visibleWhen(string $field, string $operator, $value)` - Conditional visibility
- `action(string $label, string $url, array $options)` - Add action button
- `modal(string $id)` - Create modal

### Subsection Class

#### Methods
- `label(string $label)` - Set subsection label
- `description(string $description)` - Set description
- `icon(string $icon)` - Set icon
- `addFormField($field)` - Add single Litepie/Form field
- `addFormFields(array $fields)` - Add multiple fields
- `getFormFields()` - Get all form fields
- `getFormField(string $name)` - Get field by name
- `columns(int $columns)` - Set multi-column layout for fields
- `gap(string $gap)` - Set gap between columns
- `collapsible(bool $collapsible)` - Make collapsible
- `collapsed(bool $collapsed)` - Set default collapsed state
- `permissions(array|string $permissions)` - Set required permissions
- `roles(array|string $roles)` - Set required roles
- `visibleWhen(string $field, string $operator, $value)` - Conditional visibility
- `action(string $label, string $url, array $options)` - Add action button
- `modal(string $id)` - Create modal
- `endSubsection()` - Return to section level

### LayoutFormAdapter Class

Static utility methods for form integration:

- `addField(Subsection $subsection, string $type, string $name)` - Create and add field
- `getFieldsFromSection(Section $section)` - Extract all fields from section
- `extractValidationRules(array $fields)` - Get Laravel validation rules
- `extractFieldAttributes($field)` - Get HTML attributes
- `generateDefaultData(array $fields)` - Generate default form data
- `toFormArray($layout)` - Convert layout to form-compatible array

### LayoutHelper Class

Utility methods for layouts:

- `extractValidationRules($layout)` - Extract validation rules from layout
- `extractFieldAttributes($field)` - Extract field attributes
- `attributesToString(array $attributes)` - Convert attributes to HTML string
- `filterVisible(array $items)` - Filter visible items
- `filterAuthorized(array $items)` - Filter authorized items
- `generateDefaultData($layout)` - Generate default data
- `validate($layout, array $data, $validator)` - Validate data against layout
- `toJson($layout, int $options)` - Convert layout to JSON
- `getFieldNames($layout)` - Get field names as array
- `hasField($layout, string $fieldName)` - Check if field exists

## Advanced Usage

### Working with Cache

```php
use Litepie\Layout\Facades\Layout;

// Layouts are automatically cached per user
$layout = Layout::for('user', 'profile');

// Clear cache for specific layout
Layout::clearCache('user', 'profile');

// Clear cache for specific user's layout
Layout::clearCache('user', 'profile', $userId);

// Register a layout in config/layout.php
'layouts' => [
    'user.profile' => function() {
        return LayoutBuilder::create('user', 'profile')
            ->section('info')
                ->subsection('basic')
                    // ... fields
                ->endSubsection()
            ->endSection()
            ->build();
    },
],
```

### Custom Authorization Logic

```php
use Litepie\Layout\LayoutBuilder;

$layout = LayoutBuilder::create('admin', 'dashboard');

$layout->section('sensitive_data')
    ->label('Sensitive Information')
    ->canSee(function($user) {
        // Custom authorization logic
        return $user->isAdmin() && $user->hasVerifiedEmail();
    })
    ->subsection('data')
        ->canSee(function($user) {
            // Subsection-level authorization
            return $user->can('view-sensitive-data');
        })
        // ... fields
    ->endSubsection()
->endSection();

// Resolve for specific user
$layout->build()->resolveAuthorization($currentUser);
```

### Complex Conditional Visibility

```php
$subsection->visibleWhen('payment_method', '==', 'credit_card')
    ->visibleWhen('country', 'in', ['US', 'CA', 'UK']);

// Multiple conditions on section
$section->visibleWhen('user_type', '==', 'premium')
    ->visibleWhen('subscription_active', '==', true);
```

### Using with Controllers

```php
namespace App\Http\Controllers;

use Litepie\Layout\Facades\Layout;
use Litepie\Layout\Adapters\LayoutFormAdapter;

class UserController extends Controller
{
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $layout = Layout::get('user', 'profile');
        
        // Resolve authorization for current user
        $layout->resolveAuthorization(auth()->user());
        
        return view('users.edit', [
            'user' => $user,
            'layout' => $layout->toAuthorizedArray(),
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $layout = Layout::get('user', 'profile');
        
        // Extract validation rules from layout
        $fields = $layout->getAllFormFields();
        $rules = LayoutFormAdapter::extractValidationRules($fields);
        
        // Validate request
        $validated = $request->validate($rules);
        
        // Update user
        User::findOrFail($id)->update($validated);
        
        return redirect()->route('users.index');
    }
}
```

### Action Modals with Dynamic Data

```php
use Litepie\Layout\ActionModal;
use Litepie\Form\Field;

$modal = ActionModal::make('assign-role')
    ->title('Assign Role')
    ->description('Select a role to assign to this user')
    ->addFormFields([
        Field::make('select', 'role_id')
            ->label('Role')
            ->options(Role::pluck('name', 'id')->toArray())
            ->required(),
        
        Field::make('date', 'expires_at')
            ->label('Expires At')
            ->help('Leave empty for permanent assignment'),
        
        Field::make('checkbox', 'send_notification')
            ->label('Send notification to user')
            ->default(true),
    ])
    ->submitLabel('Assign Role')
    ->submitClass('btn btn-primary');

$section->addModal($modal);
```

## Migration from v1.x

If you're upgrading from Litepie Layout v1.x (which had built-in field classes), please see the [MIGRATION.md](MIGRATION.md) guide for detailed instructions.

**Quick Summary:**
- Replace `->field('name')->type('text')` with `->addFormField(Field::make('text', 'name'))`
- Change `getAllFields()` to `getAllFormFields()`
- Change `getFieldByName()` to `getFormFieldByName()`
- Use Litepie/Form's rendering instead of built-in views

## Examples

See [examples/integration_examples.php](examples/integration_examples.php) for comprehensive integration examples including:

1. User Profile Form
2. Product Management with Authorization
3. Conditional Visibility
4. Using LayoutFormAdapter
5. Action Modals with Form Fields
6. Multi-Column Layouts
7. Complete Registration Form

## Documentation

### Package Documentation
- **[QUICKSTART.md](QUICKSTART.md)** - 5-minute quick start guide for new users
- **[MIGRATION.md](MIGRATION.md)** - Complete migration guide from v1.x to v2.0
- **[CHANGELOG.md](CHANGELOG.md)** - Version history and upgrade notes
- **[STRUCTURE.md](STRUCTURE.md)** - Package structure and component details
- **[REFACTORING_SUMMARY.md](REFACTORING_SUMMARY.md)** - Technical details of the v2.0 refactoring

### Related Documentation
- **[Litepie/Form Documentation](https://github.com/Litepie/Form)** - Field types, form building, and rendering
- **[Examples](examples/integration_examples.php)** - 8 comprehensive integration examples

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Credits

- **[Litepie](https://litepie.com)**
- [All Contributors](../../contributors)

## Support

For support, please open an issue on GitHub or contact [info@litepie.com](mailto:info@litepie.com).



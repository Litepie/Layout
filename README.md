# Litepie Layout Builder

A powerful and flexible Laravel package for building dynamic, data-driven layouts with support for nested sections, components, authorization, caching, and responsive behavior.

## Table of Contents

- [Overview](#overview)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Core Concepts](#core-concepts)
- [Architecture](#architecture)
- [Features](#features)
- [Documentation](#documentation)
- [License](#license)

## Overview

Litepie Layout Builder provides a fluent, declarative API for creating complex UI layouts in Laravel applications. It separates layout structure from presentation logic, making it easy to build reusable, maintainable, and testable UI components.

### Key Features

- **Declarative Layout API** - Build layouts using a fluent, chainable interface
- **Sections & Components** - Clear separation between containers (Sections) and content (Components)
- **Named Section Slots** - Organize content in header, body, footer, and custom slots
- **Authorization** - Built-in permission and role-based access control
- **Data Binding** - Automatic data loading from multiple sources (API, database, closures)
- **Responsive Design** - Device-specific layout configurations
- **Caching** - Performance optimization with flexible cache strategies
- **Events** - Lifecycle hooks for before/after rendering
- **Validation** - Input validation with Laravel validator integration
- **Internationalization** - Multi-language support with automatic translation
- **Export/Import** - JSON serialization for layout persistence

## Installation

### Requirements

- PHP 8.1 or higher
- Laravel 10.x or 11.x

### Install via Composer

```bash
composer require litepie/layout
```

### Publish Configuration (Optional)

```bash
php artisan vendor:publish --provider="Litepie\Layout\LayoutServiceProvider"
```

This creates `config/layout.php` where you can customize default behavior.

## Quick Start

### Basic Example

```php
use Litepie\Layout\Facades\Layout;

// Create a simple card layout
$layout = Layout::create('dashboard')
    ->section('main', function ($section) {
        $section->card('user-stats')
            ->title('User Statistics')
            ->dataUrl('/api/stats')
            ->addField('total_users', 'Total Users')
            ->addField('active_users', 'Active Today')
            ->addField('new_registrations', 'New This Month');
    });

// Render to array (for JSON API responses)
return $layout->render();
```

### Creating a Form

```php
use Litepie\Layout\Facades\Layout;

$layout = Layout::create('user-form')
    ->section('main', function ($section) {
        $section->form('user-form')
            ->action('/users')
            ->method('POST')
            ->addField('name', 'text', 'Full Name')
            ->addField('email', 'email', 'Email Address')
            ->addField('role', 'select', 'Role', ['options' => ['admin', 'user', 'guest']])
            ->addButton('submit', 'Save User', 'primary');
    });

return $layout->render();
```

### Multi-Section Layout

```php
use Litepie\Layout\Facades\Layout;

$layout = Layout::create('dashboard')
    ->section('header', function ($section) {
        $section->breadcrumb('navigation')
            ->addItem('Home', '/')
            ->addItem('Dashboard', '/dashboard');
    })
    ->section('main', function ($section) {
        // Grid layout with multiple cards
        $section->grid('stats-grid')
            ->columns(3)
            ->addComponent(
                $section->card('revenue')
                    ->title('Revenue')
                    ->dataUrl('/api/revenue')
            )
            ->addComponent(
                $section->card('orders')
                    ->title('Orders')
                    ->dataUrl('/api/orders')
            )
            ->addComponent(
                $section->card('customers')
                    ->title('Customers')
                    ->dataUrl('/api/customers')
            );
    })
    ->section('footer', function ($section) {
        $section->text('copyright')
            ->content('© 2025 Your Company');
    });

return $layout->render();
```

## Core Concepts

### Sections vs Components

The layout system has two fundamental building blocks:

#### **Sections** (Containers)
Sections are containers that organize other elements using named slots (header, body, footer, sidebar, etc.). They define structure but don't render content themselves.

**Available Sections:**
- `HeaderSection` - Page headers with navigation
- `LayoutSection` - Main layout containers
- `GridSection` - Responsive grid layouts
- `TabsSection` - Tabbed interfaces
- `AccordionSection` - Collapsible panels
- `WizardSection` - Multi-step workflows
- `ScrollSpySection` - Scroll-based navigation

#### **Components** (Content)
Components are leaf nodes that render actual content. They cannot contain other elements.

**Available Components:**
- `FormComponent` - Forms with fields and validation
- `CardComponent` - Content cards
- `TableComponent` - Data tables with sorting/filtering
- `ListComponent` - Lists (ordered, unordered, definitions)
- `AlertComponent` - Notifications and messages
- `BadgeComponent` - Labels and tags
- `ModalComponent` - Dialogs and popups
- `ChartComponent` - Data visualizations
- `TextComponent` - Rich text content
- `MediaComponent` - Images, videos, galleries
- `StatsComponent` - Statistics displays
- `TimelineComponent` - Event timelines
- `CommentComponent` - Comment threads
- `BreadcrumbComponent` - Navigation breadcrumbs
- `DocumentComponent` - Document management
- `CustomComponent` - Custom HTML/JSON content

### Section Slots

Sections organize content using named slots:

```php
$layout->section('main', function ($section) {
    // Add to 'header' slot
    $section->section('header')->text('title')->content('Dashboard');
    
    // Add to 'body' slot (default)
    $section->card('main-content')->title('Content');
    
    // Add to 'footer' slot
    $section->section('footer')->text('info')->content('Last updated: Today');
});
```

### Nesting Rules

- ✅ **Sections can contain Sections** - Create nested layouts
- ✅ **Sections can contain Components** - Add content to containers
- ❌ **Components cannot contain anything** - They are leaf nodes

## Architecture

### High-Level Structure

```
Layout (Root Container)
├── Section (e.g., "header")
│   ├── Component (e.g., Breadcrumb)
│   └── Component (e.g., Alert)
├── Section (e.g., "main")
│   ├── Section (e.g., Grid)
│   │   ├── Component (e.g., Card)
│   │   ├── Component (e.g., Table)
│   │   └── Component (e.g., Chart)
│   └── Section (e.g., Tabs)
│       ├── Tab 1 → Component (Form)
│       └── Tab 2 → Component (List)
└── Section (e.g., "footer")
    └── Component (e.g., Text)
```

### Class Hierarchy

```
BaseSection (Container with slots)
├── HeaderSection
├── LayoutSection
├── GridSection
├── TabsSection
├── AccordionSection
├── WizardSection
└── ScrollSpySection

BaseComponent (Content leaf node)
├── FormComponent
├── CardComponent
├── TableComponent
├── ListComponent
├── AlertComponent
├── BadgeComponent
├── ModalComponent
├── ChartComponent
├── TextComponent
├── MediaComponent
├── StatsComponent
├── TimelineComponent
├── CommentComponent
├── BreadcrumbComponent
├── DocumentComponent
├── AvatarComponent
├── DividerComponent
└── CustomComponent (extensible for custom components)
```

## Features

### 1. Data Binding

Load data from multiple sources:

```php
// From API endpoint
$section->card('api-data')
    ->dataUrl('/api/stats')
    ->dataParams(['filter' => 'active']);

// From database
$section->table('users')
    ->dataSource('users')
    ->dataTransform(function ($query) {
        return $query->where('active', true)->orderBy('created_at', 'desc');
    });

// From closure
$section->card('dynamic')
    ->dataSource(function () {
        return [
            'total' => User::count(),
            'active' => User::where('active', true)->count(),
        ];
    });
```

### 2. Authorization

Control visibility with permissions and roles:

```php
$section->card('admin-panel')
    ->permissions(['manage-users', 'view-logs'])
    ->canSee(function ($user) {
        return $user->isAdmin();
    });

// Resolve authorization for current user
$layout->resolveAuthorization(auth()->user());
```

### 3. Responsive Design

Device-specific configurations:

```php
$section->grid('responsive-grid')
    ->columns(4)
    ->setDeviceConfig('mobile', ['columns' => 1])
    ->setDeviceConfig('tablet', ['columns' => 2]);
```

### 4. Caching

Improve performance with automatic caching:

```php
$layout->cache()
    ->ttl(3600)
    ->key('dashboard-layout')
    ->tags(['layouts', 'dashboard']);
```

### 5. Events

Hook into the rendering lifecycle:

```php
$layout->beforeRender(function ($layout) {
    Log::info('Rendering layout: ' . $layout->getName());
});

$layout->afterRender(function ($layout, $output) {
    Log::info('Rendered layout with ' . count($output) . ' sections');
});
```

### 6. Conditional Logic

Show/hide elements based on conditions:

```php
$section->card('premium-features')
    ->condition('user.subscription.status == "active"')
    ->condition('user.subscription.plan == "premium"');
```

### 7. Validation

Validate form inputs:

```php
$section->form('user-form')
    ->validationRules([
        'name' => 'required|min:3|max:255',
        'email' => 'required|email|unique:users',
        'age' => 'required|integer|min:18',
    ]);
```

### 8. Internationalization

Multi-language support:

```php
$section->card('welcome')
    ->title('layout.welcome.title')  // Translatable key
    ->translate();  // Enable translation

// Or translate specific fields
$section->form('contact')
    ->translateField('submit_button', 'forms.submit');
```

## Documentation

### Backend (PHP/Laravel)

- **[Architecture Guide](ARCHITECTURE.md)** - Detailed architecture and design patterns
- **[API Reference](API_REFERENCE.md)** - Complete API documentation for all sections and components
- **[Examples](EXAMPLES.md)** - Comprehensive usage examples and patterns
- **[Custom Components Guide](docs/CUSTOM_COMPONENTS.md)** - Create project-specific components
- **[Complete Guide](docs/GUIDE.md)** - Comprehensive documentation

### Frontend Implementations

- **[Frontend Overview](frontend/README.md)** - Overview of all frontend implementations
- **[React/Next.js](frontend/react-next/README.md)** - ✅ Complete TypeScript implementation with Tailwind CSS
- **[Vue.js](frontend/vue/README.md)** - 📋 Planned implementation
- **[Flutter](frontend/flutter/README.md)** - 📋 Planned implementation

### Code Examples (PHP)

- **[Basic Usage](examples/usage.php)** - Simple layout examples
- **[Dashboard Example](examples/DashboardExample.php)** - Complete dashboard with stats and charts
- **[Avatar Component](examples/AvatarExample.php)** - User avatar display examples
- **[Divider Component](examples/DividerExample.php)** - Visual separator examples
- **[Custom Components](examples/CustomComponentExample.php)** - Creating custom components

## Testing

Run the test suite:

```bash
composer test
```

Run with coverage:

```bash
composer test:coverage
```

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Security

If you discover any security-related issues, please email security@litepie.com instead of using the issue tracker.

## License

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.

## Credits

- **Litepie Team**
- [All Contributors](../../contributors)

## Support

- **Documentation:** [https://litepie.com/docs/layout](https://litepie.com/docs/layout)
- **Issues:** [GitHub Issues](https://github.com/Litepie/Layout/issues)
- **Email:** support@litepie.com



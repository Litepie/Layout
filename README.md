# Litepie Layout

A flexible, component-based layout organizer for Laravel that provides infinite composition of different section types. Designed to work seamlessly with [Litepie/Form](https://github.com/Litepie/Form) for building structured content layouts.

[![Latest Version](https://img.shields.io/packagist/v/litepie/layout.svg?style=flat-square)](https://packagist.org/packages/litepie/layout)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/litepie/layout/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/litepie/layout/actions?query=workflow%3Atests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/litepie/layout.svg?style=flat-square)](https://packagist.org/packages/litepie/layout)
[![License](https://img.shields.io/packagist/l/litepie/layout.svg?style=flat-square)](https://packagist.org/packages/litepie/layout)

## Overview

Litepie Layout is a **component-based layout organizer** for Laravel applications. It provides a flexible way to compose layouts from different component types including **forms, text, cards, tables, grids, and custom components** — with infinite nesting capabilities and built-in support for authorization, ordering, and visibility control.

This package focuses on **structure and composition**, while delegating form field management to [Litepie/Form](https://github.com/Litepie/Form), creating a clean separation of concerns.

## Table of Contents

- [Overview](#overview)
- [Key Features](#key-features)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Component Types](#component-types)
- [Usage](#usage)
  - [Form Sections](#form-sections)
  - [Text & Content Sections](#text--content-sections)
  - [Card Sections](#card-sections)
  - [Table Sections](#table-sections)
  - [Grid Sections](#grid-sections)
  - [Custom Sections](#custom-sections)
  - [Infinite Nesting](#infinite-nesting)
- [Authorization & Visibility](#authorization--visibility)
- [Integration with Litepie/Form](#integration-with-litepieform)
- [API Reference](#api-reference)
- [Examples](#examples)
- [Migration from v1.x](#migration-from-v1x)

## Quick Start

New to Litepie Layout? Check out the [QUICKSTART.md](QUICKSTART.md) guide for a 5-minute introduction.

## Key Features

- 🧩 **Component-Based** - Compose layouts from FormSection, TextSection, CardSection, TableSection, GridSection, CustomSection
- ∞ **Infinite Nesting** - Nest components to any depth using GridSection containers
- 🔐 **Authorization** - Permission and role-based visibility control for every component
- 🎯 **Type Flexibility** - Mix different component types freely in any layout
- 📊 **Rich Components** - Built-in support for forms, text, cards, tables, and custom types
- 👁️ **Conditional Visibility** - Show/hide components based on conditions
- 🔗 **Litepie/Form Integration** - Seamlessly works with form field instances
- 💾 **Fluent API** - Chainable, intuitive builder pattern
- 🔄 **Backward Compatible** - Legacy Section→Subsection structure still supported

## Installation

### Requirements

- PHP 8.1 or higher
- Laravel 10.0 or higher
- Litepie/Form package (^1.0 or ^2.0)

### Install via Composer

```bash
composer require litepie/layout litepie/form
```

### Publish Configuration (Optional)

```bash
php artisan vendor:publish --provider="Litepie\Layout\LayoutServiceProvider"
```

## Component Types

Litepie Layout provides comprehensive built-in component types:

### Core Components
1. **FormSection** - Contains Litepie/Form field instances
2. **TextSection** - Display text content and headings
3. **CardSection** - Card-based information display
4. **TableSection** - Tabular data with sorting/searching/pagination
5. **StatsSection** - Dashboard metrics and KPIs
6. **GridSection** - Container for organizing components in a grid

### Navigation & Organization
7. **TabsSection** - Tabbed content organization
8. **AccordionSection** - Collapsible panels
9. **ScrollSpySection** - Scrollspy navigation
10. **WizardSection** - Multi-step processes and forms

### Data Visualization
11. **ChartSection** - Charts and graphs (line, bar, pie, etc.)
12. **TimelineSection** - Chronological events and activity feeds
13. **ListSection** - Bullet points, numbered lists, checklists

### UI Components
14. **AlertSection** - Alerts, warnings, info messages
15. **ModalSection** - Modal/dialog content structures
16. **BadgeSection** - Tags, labels, status indicators

### Media & Content
17. **MediaSection** - Images, videos, galleries, audio
18. **CommentSection** - Comment threads and discussions

### Custom
19. **CustomSection** - Custom component types with views/data

## Data Loading Architecture

**Important:** Layouts define **structure only**, not data. The frontend loads data separately via API calls based on the selected record or context.

### How It Works

1. **Layout API** - Returns component structure, configuration, and data endpoints (loaded once)
2. **Data API** - Returns actual data for each component (loaded dynamically based on context)

```php
// Layout Definition (structure only - no data)
$layout = LayoutBuilder::create('user', 'view')
    ->statsSection('overview')
        ->title('Account Overview')
        ->dataSource('user.stats') // Identifier for frontend
        ->dataUrl('/api/users/{id}/stats') // API endpoint with placeholder
        ->dataParams(['include' => 'trends']) // Query parameters
        ->loadOnMount(true) // Load when component mounts
        ->reloadOnChange(true) // Reload when user ID changes
        ->addMetric('posts', 'Total Posts', ['icon' => 'file-text', 'format' => 'number'])
        ->addMetric('followers', 'Followers', ['icon' => 'users', 'show_trend' => true])
        ->addMetric('revenue', 'Revenue', ['icon' => 'dollar', 'format' => 'currency'])
    
    ->tableSection('activity')
        ->title('Recent Activity')
        ->dataSource('user.activity')
        ->dataUrl('/api/users/{id}/activity')
        ->dataParams(['per_page' => 20, 'sort' => '-created_at'])
        ->columns([
            ['key' => 'action', 'label' => 'Action'],
            ['key' => 'created_at', 'label' => 'Date', 'sortable' => true],
        ])
        ->searchable()
        ->sortable()
        ->paginated()
        ->perPage(20)
    
    ->cardSection('profile_card')
        ->title('Profile Summary')
        ->dataSource('user.profile')
        ->dataUrl('/api/users/{id}/profile')
        ->loadOnMount(true)
    
    ->build();

// Frontend receives:
// {
//   "stats_overview": {
//     "type": "stats",
//     "title": "Account Overview",
//     "data_url": "/api/users/{id}/stats",
//     "data_params": {"include": "trends"},
//     "load_on_mount": true,
//     "reload_on_change": true,
//     "metrics": [...]
//   },
//   "activity": {
//     "type": "table",
//     "data_url": "/api/users/{id}/activity",
//     "columns": [...],
//     "paginated": true
//   }
// }

// Then frontend makes separate calls:
// GET /api/users/123/stats?include=trends
// GET /api/users/123/activity?per_page=20&sort=-created_at
```

### Data Loading Options

All components support these data configuration methods:

- `dataSource(string)` - Identifier for the data source (e.g., 'user.stats')
- `dataUrl(string)` - API endpoint URL (supports placeholders like `{id}`)
- `dataParams(array)` - Query parameters for the API call
- `dataTransform(string)` - Optional transform function name for the response
- `loadOnMount(bool)` - Auto-load data when component mounts (default: true)
- `reloadOnChange(bool)` - Reload data when parent context changes (default: false)
- `useSharedData(bool, ?string)` - Use data from shared source (optionally specify key)
- `dataKey(string)` - Key to extract from shared data response (supports dot notation: `'user.profile.header'`)

### Nested Data Keys

The `dataKey` supports dot notation for extracting nested data from JSON responses:

```php
// Shared API returns nested structure:
// {
//   "user": {
//     "profile": {
//       "header": { "name": "John", "avatar": "..." },
//       "stats": { "posts": 42, "followers": 128 }
//     },
//     "settings": { "theme": "dark" }
//   },
//   "content": {
//     "recent": { "posts": [...], "comments": [...] }
//   }
// }

$layout = LayoutBuilder::create('user', 'view')
    ->sharedDataUrl('/api/users/{id}/complete')
    
    // Extract from 'user.profile.header'
    ->cardSection('profile_header')
        ->useSharedData(true, 'user.profile.header')
    
    // Extract from 'user.profile.stats'
    ->statsSection('user_stats')
        ->useSharedData(true, 'user.profile.stats')
    
    // Extract from 'content.recent.posts'
    ->tableSection('recent_posts')
        ->useSharedData(true, 'content.recent.posts')
    
    // Extract from 'user.settings'
    ->formSection('settings')
        ->useSharedData(true, 'user.settings')
    
    ->build();

// Frontend extracts nested data using dot notation:
// component.data_key = 'user.profile.header'
// data = getNestedValue(allData, 'user.profile.header')
// Result: { name: "John", avatar: "..." }
```

### Loading Patterns

**Pattern 1: Single API Call for All Data** (Recommended for simple views)

```php
// Load all data from one endpoint
$layout = LayoutBuilder::create('user', 'view')
    ->sharedDataUrl('/api/users/{id}/complete') // Single endpoint
    ->sharedDataParams(['include' => 'stats,activity,profile'])
    
    ->statsSection('overview')
        ->title('Statistics')
        ->useSharedData(true, 'stats') // Use 'stats' key from shared response
        ->addMetric('posts', 'Posts')
        ->addMetric('followers', 'Followers')
    
    ->cardSection('profile')
        ->title('Profile')
        ->useSharedData(true, 'profile') // Use 'profile' key
    
    ->tableSection('activity')
        ->title('Activity')
        ->useSharedData(true, 'activity') // Use 'activity' key
        ->columns([...])
    
    ->build();

// Frontend makes ONE call:
// GET /api/users/123/complete?include=stats,activity,profile
// Response: {
//   "stats": {"posts": 42, "followers": 128},
//   "profile": {"name": "John", "email": "..."},
//   "activity": [{...}, {...}]
// }
```

**Pattern 2: Separate API Calls per Section** (For heavy/lazy-loaded data)

```php
// Each section loads independently
$layout = LayoutBuilder::create('user', 'view')
    ->statsSection('overview')
        ->title('Statistics')
        ->dataUrl('/api/users/{id}/stats') // Separate endpoint
        ->loadOnMount(true)
    
    ->tableSection('activity')
        ->title('Activity')
        ->dataUrl('/api/users/{id}/activity') // Separate endpoint
        ->loadOnMount(false) // Don't load initially
        ->columns([...])
    
    ->tableSection('orders')
        ->title('Orders')
        ->dataUrl('/api/users/{id}/orders') // Separate endpoint
        ->loadOnMount(false) // Load when tab/section is viewed
        ->paginated()
    
    ->build();

// Frontend makes MULTIPLE calls:
// GET /api/users/123/stats (immediately)
// GET /api/users/123/activity (when section becomes visible)
// GET /api/users/123/orders (when user navigates to it)
```

**Pattern 3: Mixed Approach** (Shared + Separate)

```php
// Common data shared, heavy data separate
$layout = LayoutBuilder::create('user', 'view')
    ->sharedDataUrl('/api/users/{id}/basic') // Light data
    ->sharedDataParams(['include' => 'stats,profile'])
    
    ->statsSection('overview')
        ->useSharedData(true, 'stats') // From shared call
    
    ->cardSection('profile')
        ->useSharedData(true, 'profile') // From shared call
    
    ->tableSection('activity')
        ->title('Recent Activity')
        ->dataUrl('/api/users/{id}/activity') // Separate heavy call
        ->dataParams(['per_page' => 50])
        ->loadOnMount(false) // Lazy load
    
    ->tableSection('transactions')
        ->title('Transactions')
        ->dataUrl('/api/users/{id}/transactions') // Separate heavy call
        ->loadOnMount(false)
        ->reloadOnChange(true) // Reload when user changes
    
    ->build();

// Frontend makes:
// 1. GET /api/users/123/basic?include=stats,profile (immediately)
// 2. GET /api/users/123/activity?per_page=50 (when visible)
// 3. GET /api/users/123/transactions (when visible)
```

## Universal Section Headers

All sections support universal header properties for consistent UI presentation:

### Title, Subtitle, Icon & Actions

Every section type can have a title, subtitle, icon, and action buttons:

```php
use Litepie\Layout\LayoutBuilder;
use Litepie\Form\Fields\Text;

$layout = LayoutBuilder::create('user', 'edit')
    ->formSection('profile')
        ->title('User Profile')
        ->subtitle('Update your personal information and settings')
        ->icon('user-circle')
        ->addAction('Save', '/save', ['style' => 'primary'])
        ->addAction('Cancel', '/cancel', ['style' => 'secondary'])
        ->addFormFields([
            Text::make('name')->label('Full Name'),
            Text::make('email')->label('Email'),
        ])
    
    ->cardSection('stats')
        ->title('Account Statistics')
        ->subtitle('Your activity summary')
        ->icon('chart-bar')
        ->addAction('View Details', '/details')
        ->addItem('Posts', 42)
        ->addItem('Followers', 128)
    
    ->tableSection('users')
        ->title('Team Members')
        ->subtitle('Manage your team')
        ->icon('users')
        ->addAction('Add Member', '/add', ['icon' => 'plus'])
        ->columns([...])
        ->rows($users)
    
    ->build();
```

These properties are available on **all section types**:
- `title(string $title)` - Main heading for the section
- `subtitle(string $subtitle)` - Descriptive text below the title
- `icon(string $icon)` - Icon identifier (e.g., 'user', 'chart-bar', 'cog')
- `addAction(string $label, string $url, array $options = [])` - Add action buttons
- `actions(array $actions)` - Set multiple actions at once

The header properties are automatically included in the `toArray()` output of every section.

## Usage

### Form Sections

Use FormSection to add Litepie/Form fields:

```php
use Litepie\Layout\LayoutBuilder;
use Litepie\Form\Fields\Text;
use Litepie\Form\Fields\Email;

$layout = LayoutBuilder::create('user', 'edit')
    ->formSection('personal_info')
        ->label('Personal Information')
        ->description('Enter your personal details')
        ->icon('user')
        ->columns(2)
        ->addFormFields([
            Text::make('first_name')->label('First Name')->required(),
            Text::make('last_name')->label('Last Name')->required(),
            Email::make('email')->label('Email')->required(),
        ])
    ->build();
```

### Text & Content Sections

Display text content, headings, or documentation:

```php
$layout = LayoutBuilder::create('dashboard', 'view')
    ->textSection('welcome')
        ->title('Welcome to Dashboard')
        ->content('Here you can manage all your activities and view statistics.')
        ->size('lg')
        ->align('center')
        ->order(1)
    ->build();
```
### Card Sections

Display card-based information with optional actions:

```php
$layout = LayoutBuilder::create('profile', 'view')
    ->cardSection('stats')
        ->title('Account Statistics')
        ->description('Your account overview')
        ->image('/images/avatar.png')
        ->addItem('Total Posts', 142)
        ->addItem('Followers', 1534)
        ->addItem('Following', 289)
        ->addAction('View Profile', '/profile/view')
        ->addAction('Edit', '/profile/edit', ['type' => 'button', 'class' => 'btn-primary'])
    ->build();
```

### Table Sections

Display tabular data with search and sorting:

```php
$layout = LayoutBuilder::create('admin', 'users')
    ->tableSection('user_list')
        ->title('User Management')
        ->columns([
            ['key' => 'name', 'label' => 'Name', 'sortable' => true],
            ['key' => 'email', 'label' => 'Email', 'sortable' => true],
            ['key' => 'role', 'label' => 'Role'],
            ['key' => 'created_at', 'label' => 'Joined', 'sortable' => true],
        ])
        ->rows($users->toArray())
        ->searchable()
        ->sortable()
        ->paginated()
        ->permissions(['view-users'])
    ->build();
```

### Grid Sections

Organize components in a grid layout:

```php
use Litepie\Layout\Components\CardSection;
use Litepie\Layout\Components\FormSection;
use Litepie\Form\Fields\Text;

$layout = LayoutBuilder::create('dashboard', 'view')
    ->gridSection('overview')
        ->columns(3)
        ->gap('lg')
        ->addComponents([
            CardSection::make('card1')->title('Sales')->addItem('Total', '$15,234'),
            CardSection::make('card2')->title('Orders')->addItem('Count', 523),
            CardSection::make('card3')->title('Users')->addItem('Active', 1042),
        ])
    ->formSection('quick_search')
        ->addFormField(Text::make('search')->label('Quick Search'))
    ->build();
```

### Custom Sections

Create custom component types for special requirements:

```php
$layout = LayoutBuilder::create('analytics', 'dashboard')
    ->customSection('sales_chart', 'chart')
        ->view('components.chart')
        ->component('ChartComponent')
        ->data([
            'type' => 'line',
            'data' => $chartData,
            'options' => ['responsive' => true],
        ])
        ->with('title', 'Monthly Sales')
    ->build();
```

### Tabs Sections

Organize content into tabs, where each tab can contain any combination of sections and components:

```php
use Litepie\Layout\Components\FormSection;
use Litepie\Layout\Components\TableSection;
use Litepie\Layout\Components\CardSection;
use Litepie\Form\Fields\Text;
use Litepie\Form\Fields\Email;

$layout = LayoutBuilder::create('user', 'profile')
    ->tabsSection('profile_tabs')
        ->title('User Profile')
        
        ->addTab('general', 'General', [
            FormSection::make('personal')
                ->label('Personal Information')
                ->columns(2)
                ->addFormFields([
                    Text::make('first_name')->label('First Name')->required(),
                    Text::make('last_name')->label('Last Name')->required(),
                    Email::make('email')->label('Email')->required(),
                ]),
            CardSection::make('stats')
                ->title('Account Stats')
                ->addItem('Posts', 142)
                ->addItem('Followers', 1534),
        ], ['icon' => 'user'])
        
        ->addTab('security', 'Security', [
            FormSection::make('password')
                ->label('Change Password')
                ->addFormFields([
                    Text::make('current_password')->type('password'),
                    Text::make('new_password')->type('password'),
                ]),
        ], ['icon' => 'lock', 'permissions' => ['edit-security']])
        
        ->addTab('activity', 'Activity', [
            TableSection::make('logs')
                ->columns([...])
                ->rows($activityLog)
                ->paginated(),
        ], ['icon' => 'clock', 'badge' => count($activityLog)])
        
        ->activeTab('general')
        ->position('top')
    ->build();
```

Tabs can be nested infinitely and can contain any component type including other TabsSection instances.

### Infinite Nesting

Nest components to any depth using GridSection:

```php
use Litepie\Layout\Components\GridSection;
use Litepie\Layout\Components\FormSection;
use Litepie\Layout\Components\CardSection;
use Litepie\Form\Fields\Text;

$layout = LayoutBuilder::create('complex', 'view')
    ->addComponent(
        GridSection::make('main_grid')
            ->columns(2)
            ->addComponents([
                FormSection::make('left_form')
                    ->label('Quick Form')
                    ->addFormField(Text::make('field1')),
                
                // Nested grid inside main grid
                GridSection::make('nested_grid')
                    ->columns(2)
                    ->addComponents([
                        CardSection::make('card1')->title('Card 1'),
                        
                        // Even deeper nesting
                        GridSection::make('deep_grid')
                            ->columns(1)
                            ->addComponents([
                                FormSection::make('deep_form')
                                    ->addFormField(Text::make('deep_field')),
                                CardSection::make('deep_card'),
                            ]),
                    ]),
            ])
    )
    ->build();
```

## Authorization & Visibility

All components support authorization and conditional visibility:

```php
use Litepie\Layout\LayoutBuilder;
use Litepie\Form\Fields\Text;

$layout = LayoutBuilder::create('admin', 'panel')
    ->formSection('admin_settings')
        ->permissions(['admin', 'super-admin'])
        ->roles(['administrator'])
        ->canSee(fn($user) => $user->isAdmin())
        ->addFormField(Text::make('secret_config'))
    
    ->cardSection('public_info')
        ->visible(true) // Always visible
        ->title('Public Information')
    
    ->tableSection('sensitive_data')
        ->hidden() // Hidden by default
        ->permissions(['view-sensitive-data'])
    
    ->build();

// Resolve authorization for specific user
$authorizedLayout = $layout->forUser(auth()->user());
$data = $authorizedLayout->toAuthorizedArray();
```

### Component Ordering

Control the display order of components:

```php
$layout = LayoutBuilder::create('page', 'view')
    ->formSection('form')->order(2)
    ->textSection('header')->order(1)  // Shows first
    ->cardSection('footer')->order(3)   // Shows last
    ->build();
```

### Component Metadata

Add custom metadata to any component:

```php
$layout = LayoutBuilder::create('docs', 'view')
    ->formSection('contact_form')
        ->meta([
            'help_url' => 'https://docs.example.com/contact',
            'video_tutorial' => 'https://youtube.com/watch?v=...',
            'category' => 'support',
        ])
    ->build();
```

## Integration with Litepie/Form

Litepie Layout seamlessly integrates with Litepie/Form to extract validation rules, attributes, and default values:

```php
use Litepie\Layout\LayoutBuilder;
use Litepie\Layout\LayoutFormAdapter;
use Litepie\Form\Fields\Text;
use Litepie\Form\Fields\Email;

// Create layout with form fields
$layout = LayoutBuilder::create('user', 'edit')
    ->formSection('profile')
        ->addFormFields([
            Text::make('name')
                ->label('Full Name')
                ->required()
                ->rules(['min:3', 'max:100'])
                ->default('John Doe'),
            
            Email::make('email')
                ->label('Email Address')
                ->required()
                ->rules(['email', 'unique:users,email'])
                ->default('john@example.com'),
        ])
    ->build();

// Use adapter to extract form data
$adapter = new LayoutFormAdapter($layout);

// Get validation rules from all form fields
$rules = $adapter->getValidationRules();
// ['name' => ['required', 'min:3', 'max:100'], 'email' => ['required', 'email', 'unique:users,email']]

// Get field labels for validation messages
$attributes = $adapter->getValidationAttributes();
// ['name' => 'Full Name', 'email' => 'Email Address']

// Get default values
$defaults = $adapter->getDefaultData();
// ['name' => 'John Doe', 'email' => 'john@example.com']

// Create a Litepie Form instance
$form = $adapter->createForm('user.update', $user);

// Validate request
$validated = $form->validate($request);
```

### Extracting Fields from Nested Components

The adapter automatically collects form fields from any depth:

```php
use Litepie\Layout\Components\GridSection;
use Litepie\Layout\Components\FormSection;
use Litepie\Form\Fields\Text;

$layout = LayoutBuilder::create('complex', 'edit')
    ->addComponent(
        GridSection::make('main')
            ->addComponents([
                FormSection::make('section1')
                    ->addFormField(Text::make('field1')->required()),
                GridSection::make('nested')
                    ->addComponent(
                        FormSection::make('section2')
                            ->addFormField(Text::make('field2')->required())
                    ),
            ])
    )
    ->build();

// Get all fields regardless of nesting
$allFields = $layout->getAllFormFields(); // Returns field1 and field2

// Or use adapter
$adapter = new LayoutFormAdapter($layout);
$rules = $adapter->getValidationRules(); // Both field1 and field2 rules
```

## API Reference

### LayoutBuilder

```php
// Create a new layout builder
LayoutBuilder::create(string $module, string $context): LayoutBuilder

// Add components (new API)
->formSection(string $name): FormSection
->textSection(string $name): TextSection
->cardSection(string $name): CardSection
->tableSection(string $name): TableSection
->gridSection(string $name): GridSection
->tabsSection(string $name): TabsSection
->customSection(string $name, string $type = 'custom'): CustomSection
->addComponent(Component $component): LayoutBuilder

// Build the layout
->build(): Layout

// Legacy support
->section(string $name): Section
->addSection(Section $section): LayoutBuilder
```

### FormSection

```php
FormSection::make(string $name): FormSection

->label(string $label): self
->description(string $description): self
->icon(string $icon): self
->columns(int $columns): self
->gap(string $gap): self
->collapsible(bool $collapsible = true): self
->collapsed(bool $collapsed = true): self
->addFormField($field): self
->addFormFields(array $fields): self
->getFormFields(): array
->getFormField(string $name): mixed|null
```

### TextSection

```php
TextSection::make(string $name): TextSection

->title(string $title): self
->content(string $content): self
->size(string $size): self  // 'sm', 'md', 'lg', 'xl'
->align(string $align): self  // 'left', 'center', 'right'
```

### CardSection

```php
CardSection::make(string $name): CardSection

->title(string $title): self
->description(string $description): self
->image(string $image): self
->addItem(string $label, mixed $value): self
->addAction(string $label, string $url, array $options = []): self
```

### TableSection

```php
TableSection::make(string $name): TableSection

->title(string $title): self
->columns(array $columns): self
->rows(array $rows): self
->searchable(bool $searchable = true): self
->sortable(bool $sortable = true): self
->paginated(bool $paginated = true): self
```

### GridSection

```php
GridSection::make(string $name): GridSection

->columns(int $columns): self
->gap(string $gap): self  // 'sm', 'md', 'lg', 'xl'
->addComponent($component): self
->addComponents(array $components): self
->getComponents(): array
```
### CustomSection

```php
CustomSection::make(string $name, string $type = 'custom'): CustomSection

->view(string $view): self
->component(string $component): self
->data(array $data): self
->with(string $key, mixed $value): self
->getData(): array
```

### TabsSection

```php
TabsSection::make(string $name): TabsSection

->title(string $title): self
->addTab(string $id, string $label, array $components, array $options = []): self
->activeTab(string $tabId): self
->position(string $position): self  // 'top', 'left', 'right', 'bottom'
->lazy(bool $lazy = true): self
->getTabs(): array
->getTab(string $id): ?array
```

#### Tab Options
```php
$options = [
    'icon' => 'icon-name',
    'badge' => 'text or count',
    'disabled' => false,
    'visible' => true,
    'permissions' => ['permission-name'],
    'roles' => ['role-name'],
]
```etData(): array
```

### Component Authorization (All Components)

```php
->permissions(array|string $permissions): self
->roles(array|string $roles): self
->canSee(\Closure $callback): self
->visible(bool $visible = true): self
->hidden(): self
->order(int $order): self
->meta(array $meta): self
```

### Layout

```php
$layout->getComponents(): array
$layout->getComponent(string $name): ?Component
$layout->addComponent(Component $component): Layout
$layout->getAllFormFields(): array
$layout->getFormFieldByName(string $name): mixed|null
$layout->resolveAuthorization($user = null): Layout
$layout->forUser($user): Layout
$layout->getAuthorizedComponents(): array
$layout->toArray(): array
$layout->toAuthorizedArray(): array
$layout->render(): array

// Legacy support
$layout->getSections(): array
$layout->getSection(string $name): mixed
$layout->getSubsection(string $sectionName, string $subsectionName): ?Subsection
$layout->getFormField(string $sectionName, string $subsectionName, string $fieldName): mixed
```

### LayoutFormAdapter

```php
new LayoutFormAdapter(Layout $layout)

->getValidationRules(): array
->getValidationAttributes(): array
->getDefaultData(): array
->createForm(string $action, $model = null): \Litepie\Form\Form
```

## Creating Custom Components

You can easily create your own component types by extending `BaseComponent`:

```php
<?php

namespace App\Layout\Components;

use Litepie\Layout\Components\BaseComponent;

class ChartSection extends BaseComponent
{
    protected string $chartType = 'line';
    protected array $chartData = [];

    public function __construct(string $name)
    {
        parent::__construct($name, 'chart');
    }

    public static function make(string $name): self
    {
        return new static($name);
    }

    public function chartType(string $type): self
    {
        $this->chartType = $type;
        return $this;
    }

    public function data(array $data): self
    {
        $this->chartData = $data;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'name' => $this->name,
            'chart_type' => $this->chartType,
            'data' => $this->chartData,
            'order' => $this->order,
            'visible' => $this->visible,
            'permissions' => $this->permissions,
            'roles' => $this->roles,
            'authorized_to_see' => $this->authorizedToSee,
            'meta' => $this->meta,
        ];
    }
}
```

Use your custom component:

```php
use App\Layout\Components\ChartSection;

$layout = LayoutBuilder::create('analytics', 'dashboard')
    ->addComponent(
        ChartSection::make('sales_chart')
            ->chartType('bar')
            ->data($salesData)
            ->permissions(['view-analytics'])
    )
    ->build();
```

See [CUSTOM_COMPONENTS.md](docs/examples/CUSTOM_COMPONENTS.md) for detailed examples.

## Examples

### Complete User Profile Layout

```php
use Litepie\Layout\LayoutBuilder;
use Litepie\Layout\Components\CardSection;
use Litepie\Layout\Components\GridSection;
use Litepie\Form\Fields\Text;
use Litepie\Form\Fields\Email;
use Litepie\Form\Fields\Password;

$layout = LayoutBuilder::create('user', 'profile')
    // Header
    ->textSection('header')
        ->title('User Profile')
        ->content('Manage your account settings')
        ->align('center')
        ->order(1)
    
    // Statistics Grid
    ->gridSection('stats')
        ->columns(3)
        ->gap('lg')
        ->order(2)
        ->addComponents([
            CardSection::make('posts')->title('Posts')->addItem('Total', 142),
            CardSection::make('followers')->title('Followers')->addItem('Count', 1534),
            CardSection::make('following')->title('Following')->addItem('Count', 289),
        ])
    
    // Profile Form
    ->formSection('profile_form')
        ->label('Profile Information')
        ->columns(2)
        ->order(3)
        ->addFormFields([
            Text::make('first_name')->label('First Name')->required(),
            Text::make('last_name')->label('Last Name')->required(),
            Email::make('email')->label('Email')->required(),
            Text::make('phone')->label('Phone'),
        ])
    
    // Password Section (Admin Only)
    ->formSection('password')
        ->label('Change Password')
        ->permissions(['admin'])
        ->order(4)
        ->addFormFields([
            Password::make('current_password')->label('Current Password'),
            Password::make('new_password')->label('New Password'),
            Password::make('confirm_password')->label('Confirm Password'),
        ])
    
    ->build();

// Use with authorization
$authorizedLayout = $layout->forUser(auth()->user());
return response()->json($authorizedLayout->toAuthorizedArray());
```

### Admin Dashboard with Mixed Components

```php
use Litepie\Layout\LayoutBuilder;
use Litepie\Layout\Components\GridSection;
use Litepie\Layout\Components\CardSection;
use Litepie\Layout\Components\TableSection;
use Litepie\Form\Fields\Text;

$layout = LayoutBuilder::create('admin', 'dashboard')
    // Stats Cards
    ->gridSection('stats')
        ->columns(4)
        ->addComponents([
            CardSection::make('users')->title('Users')->addItem('Total', 1042)->addAction('View All', '/users'),
            CardSection::make('orders')->title('Orders')->addItem('Today', 23)->addAction('View Orders', '/orders'),
            CardSection::make('revenue')->title('Revenue')->addItem('This Month', '$45,231'),
            CardSection::make('conversion')->title('Conversion')->addItem('Rate', '3.24%'),
        ])
    
    // Quick Search
    ->formSection('quick_search')
        ->addFormField(
            Text::make('search')->label('Quick Search')->placeholder('Search users, orders...')
        )
    
    // Recent Orders Table
    ->tableSection('recent_orders')
        ->title('Recent Orders')
        ->columns([
            ['key' => 'id', 'label' => 'Order ID'],
            ['key' => 'customer', 'label' => 'Customer', 'sortable' => true],
            ['key' => 'total', 'label' => 'Total'],
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'date', 'label' => 'Date', 'sortable' => true],
        ])
        ->rows($recentOrders)
        ->searchable()
        ->sortable()
        ->paginated()
        ->permissions(['view-orders'])
    
    ->build();
```

More examples available in [`docs/examples/`](docs/examples/):

- [COMPONENT_EXAMPLES.md](docs/examples/COMPONENT_EXAMPLES.md) - Comprehensive component usage examples
- [CUSTOM_COMPONENTS.md](docs/examples/CUSTOM_COMPONENTS.md) - Creating custom component types

## Migration from v1.x

If you're using the old Section→Subsection structure, it's still fully supported:

```php
// Old way (still works)
$layout = LayoutBuilder::create('user', 'edit')
    ->section('profile')
        ->label('Profile')
        ->subsection('basic')
            ->label('Basic Info')
            ->addFormField(Text::make('name'))
        ->end()
    ->end()
    ->build();

// New recommended way
$layout = LayoutBuilder::create('user', 'edit')
    ->formSection('profile')
        ->label('Profile - Basic Info')
        ->addFormField(Text::make('name'))
    ->build();
```

The new component-based approach offers:
- More flexibility with different component types
- Infinite nesting capability
- Cleaner, more intuitive API
- Better separation of concerns

See [MIGRATION.md](MIGRATION.md) for detailed migration guide.


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



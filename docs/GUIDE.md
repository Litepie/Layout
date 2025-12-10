# Litepie Layout Package - Complete Guide

> **Version:** 3.0+ | **PHP:** 8.1+ | **Laravel:** 10.0+/11.0+

The Litepie Layout package provides a flexible, component-based system for organizing UI layouts with infinite nesting capabilities, authorization, and multiple display modes.

## Table of Contents

1. [Quick Start](#quick-start)
2. [Component Architecture](#component-architecture)
3. [Available Components](#available-components)
4. [Infinite Nesting](#infinite-nesting)
5. [Display Modes](#display-modes)
6. [Authorization](#authorization)
7. [Form Integration](#form-integration)
8. [Custom Components](#custom-components)
9. [API Reference](#api-reference)

---

## Quick Start

### Installation

```bash
composer require litepie/layout
```

### Basic Usage

```php
use Litepie\Layout\LayoutBuilder;
use Litepie\Layout\Components\FormSection;
use Litepie\Form\Fields\Text;
use Litepie\Form\Fields\Textarea;

$layout = LayoutBuilder::create('blog', 'post.create')
    ->formSection('post-form')
        ->label('Create Post')
        ->addFormField(Text::make('title')->label('Title'))
        ->addFormField(Textarea::make('content')->label('Content'))
    ->build();

// Get array output for frontend
$data = $layout->toArray();
```

---

## Component Architecture

### Overview

Litepie Layout uses a component-based architecture where every component extends `BaseComponent` and can contain nested sections infinitely.

```
Layout
├── FormSection
│   ├── Form Fields
│   └── Nested Sections
│       └── CardSection
│           └── More nested sections...
├── TabsSection
│   └── Tabs
│       └── Components in each tab
└── GridSection
    └── Components in grid
```

### Component Hierarchy

- **Layout** - Top-level container
- **Components** - Building blocks (FormSection, CardSection, etc.)
- **Sections** - Nested components within components
- **Fields** - Form fields within FormSection

### Base Component Features

Every component inherits from `BaseComponent` which provides:

```php
// Ordering
->order(10)

// Visibility
->visible(true)
->hidden()

// Authorization
->permissions(['edit-posts'])
->roles(['admin', 'editor'])
->canSee(fn($user) => $user->isAdmin())

// Metadata
->meta(['key' => 'value'])

// Nested Sections
->addSection(Component $section)
->addSections(array $sections)
->getSections()
->hasSections()
```

---

## Available Components

### 1. FormSection

Organize form fields with layout options.

```php
->formSection('user-form')
    ->label('User Information')
    ->description('Basic user details')
    ->icon('user')
    ->columns(2)
    ->gap('lg')
    ->collapsible()
    ->addFormField(Text::make('name'))
    ->addFormField(Email::make('email'))
```

**Properties:**
- `label` - Section heading
- `description` - Help text
- `icon` - Icon identifier
- `columns` - Number of columns (1-4)
- `gap` - Gap size (sm/md/lg/xl)
- `collapsible` - Can be collapsed
- `collapsed` - Initially collapsed state

### 2. TextSection

Display static text or markdown content.

```php
->textSection('welcome')
    ->title('Welcome!')
    ->content('# Hello\n\nWelcome to our platform...')
    ->size('lg')
    ->align('center')
```

**Properties:**
- `title` - Section title
- `content` - Text/markdown content
- `size` - Text size (sm/md/lg/xl)
- `align` - Alignment (left/center/right)

### 3. CardSection

Display information in card format.

```php
->cardSection('user-stats')
    ->title('User Statistics')
    ->description('Your account overview')
    ->image('/path/to/image.jpg')
    ->addItem('Posts', '42')
    ->addItem('Views', '1,234')
    ->addAction('View All', '/posts', ['class' => 'primary'])
```

**Properties:**
- `title` - Card title
- `description` - Card description
- `image` - Card image URL
- `items` - Key-value pairs
- `actions` - Action buttons

### 4. TableSection

Display tabular data.

```php
->tableSection('users-table')
    ->title('Users')
    ->columns(['Name', 'Email', 'Role'])
    ->rows([
        ['John Doe', 'john@example.com', 'Admin'],
        ['Jane Smith', 'jane@example.com', 'Editor']
    ])
    ->searchable()
    ->sortable()
    ->paginated()
```

**Properties:**
- `columns` - Column headers
- `rows` - Table data
- `searchable` - Enable search
- `sortable` - Enable sorting
- `paginated` - Enable pagination

### 5. GridSection

Layout container for grid-based layouts.

```php
->gridSection('dashboard')
    ->columns(3)
    ->gap('md')
    ->addSection(CardSection::make('card1')->title('Card 1'))
    ->addSection(CardSection::make('card2')->title('Card 2'))
    ->addSection(CardSection::make('card3')->title('Card 3'))
```

**Properties:**
- `columns` - Number of columns
- `gap` - Gap between items

### 6. TabsSection

Organize content in tabs.

```php
->tabsSection('settings')
    ->addTab('general', 'General', [
        FormSection::make('general-form')
            ->addFormField(Text::make('app_name'))
    ])
    ->addTab('security', 'Security', [
        FormSection::make('security-form')
            ->addFormField(Password::make('password'))
    ])
    ->position('top')
    ->lazy()
```

**Properties:**
- `position` - Tab position (top/left/right/bottom)
- `lazy` - Lazy load tab content
- `activeTab` - Default active tab

### 7. AccordionSection

Collapsible accordion items.

```php
->accordionSection('faq')
    ->title('Frequently Asked Questions')
    ->addItem('getting-started', 'Getting Started', [
        TextSection::make('intro')->content('Welcome...')
    ])
    ->addItem('pricing', 'Pricing', [
        TextSection::make('pricing-info')->content('Our plans...')
    ])
    ->multiple()
    ->expanded('getting-started')
```

**Properties:**
- `multiple` - Allow multiple items expanded
- `collapsible` - Can collapse active item
- `expanded` - Initially expanded item

### 8. ScrollSpySection

Long-form content with scroll navigation.

```php
->scrollSpySection('documentation')
    ->title('User Guide')
    ->addSpySection('intro', 'Introduction', [
        TextSection::make('intro-text')->content('...')
    ])
    ->addSpySection('features', 'Features', [
        TextSection::make('features-text')->content('...')
    ])
    ->position('left')
    ->sticky()
    ->offset(80)
```

**Properties:**
- `position` - Navigation position (left/right)
- `sticky` - Sticky navigation
- `offset` - Scroll offset for highlighting

### 9. CustomSection

Create custom component types.

```php
->customSection('my-widget', 'widget')
    ->view('components.my-widget')
    ->component('MyWidget')
    ->data(['title' => 'Hello'])
    ->with('count', 42)
```

**Properties:**
- `view` - Blade view path
- `component` - Frontend component name
- `data` - Custom data array

---

## Infinite Nesting

Every component can contain nested sections, enabling unlimited nesting depth.

### Basic Nesting

```php
->formSection('article-form')
    ->addFormField(Text::make('title'))
    ->addSection(
        TextSection::make('help')
            ->content('Writing tips...')
    )
    ->addSection(
        CardSection::make('preview')
            ->title('Preview')
            ->addSection(
                TextSection::make('preview-text')
                    ->content('How it will appear...')
            )
    )
```

### Multi-Level Nesting (5 levels)

```php
// Level 1: TabsSection
->tabsSection('main')
    ->addTab('content', 'Content', [
        // Level 2: FormSection
        FormSection::make('article')
            ->addFormField(Text::make('title'))
            ->addSection(
                // Level 3: AccordionSection
                AccordionSection::make('advanced')
                    ->addItem('seo', 'SEO', [
                        // Level 4: FormSection
                        FormSection::make('seo-form')
                            ->addFormField(Text::make('meta_title'))
                            ->addSection(
                                // Level 5: CardSection
                                CardSection::make('preview')
                                    ->title('SEO Preview')
                            )
                    ])
            )
    ])
```

### Benefits

1. **Unlimited Flexibility** - Nest as deeply as needed
2. **Consistent API** - Same methods at every level
3. **Authorization Cascading** - Permissions resolve recursively
4. **Form Field Collection** - Finds fields at any depth
5. **Clean Structure** - Matches UI hierarchy

---

## Display Modes

Choose the right display mode for your content organization.

### Tabs - Click Navigation

Best for: Distinct categories, space-limited UIs

```php
->tabsSection('product-editor')
    ->position('left')
    ->addTab('details', 'Details', [...])
    ->addTab('media', 'Media', [...])
    ->addTab('seo', 'SEO', [...])
```

### Accordion - Vertical Collapsible

Best for: FAQs, settings panels, sequential content

```php
->accordionSection('settings')
    ->multiple()
    ->addItem('general', 'General Settings', [...])
    ->addItem('email', 'Email Configuration', [...])
    ->addItem('security', 'Security', [...])
```

### ScrollSpy - Scroll-Based Navigation

Best for: Documentation, long-form articles, guides

```php
->scrollSpySection('guide')
    ->position('left')
    ->sticky()
    ->addSpySection('intro', 'Introduction', [...])
    ->addSpySection('installation', 'Installation', [...])
    ->addSpySection('usage', 'Usage', [...])
```

### Comparison

| Feature | Tabs | Accordion | ScrollSpy |
|---------|------|-----------|-----------|
| Navigation | Click | Click | Scroll |
| Content Visibility | One at a time | Multiple possible | All visible |
| Best For | Categories | Sequential | Long-form |
| Mobile-Friendly | Yes | Excellent | Good |

---

## Authorization

Control visibility with permissions, roles, and callbacks.

### Permission-Based

```php
->formSection('admin-settings')
    ->permissions(['manage-settings'])
    ->addFormField(...)
```

### Role-Based

```php
->cardSection('admin-panel')
    ->roles(['admin', 'super-admin'])
    ->title('Admin Controls')
```

### Callback-Based

```php
->textSection('premium-content')
    ->canSee(fn($user) => $user->isPremium())
    ->content('Premium content...')
```

### Per-Tab/Item Authorization

```php
->tabsSection('settings')
    ->addTab('billing', 'Billing', [...], [
        'permissions' => ['view-billing'],
        'roles' => ['admin']
    ])
```

### Resolve Authorization

```php
$layout = LayoutBuilder::create('app', 'dashboard')->build();
$layout->resolveAuthorization($user);

// Get only authorized components
$authorized = $layout->toAuthorizedArray();
```

---

## Form Integration

Seamlessly integrates with Litepie/Form.

### Adding Form Fields

```php
use Litepie\Form\Fields\Text;
use Litepie\Form\Fields\Email;
use Litepie\Form\Fields\Select;

->formSection('contact-form')
    ->addFormField(
        Text::make('name')
            ->label('Full Name')
            ->required()
    )
    ->addFormField(
        Email::make('email')
            ->label('Email Address')
            ->required()
    )
    ->addFormField(
        Select::make('subject')
            ->label('Subject')
            ->options([
                'support' => 'Support',
                'sales' => 'Sales',
                'general' => 'General Inquiry'
            ])
    )
```

### Collecting All Fields

```php
// Get all form fields from anywhere in layout
$fields = $layout->getAllFormFields();

// Get specific field by name
$titleField = $layout->getFormFieldByName('title');
```

### Nested Forms

```php
->tabsSection('user-profile')
    ->addTab('personal', 'Personal Info', [
        FormSection::make('personal-form')
            ->addFormField(Text::make('first_name'))
            ->addFormField(Text::make('last_name'))
    ])
    ->addTab('address', 'Address', [
        FormSection::make('address-form')
            ->addFormField(Text::make('street'))
            ->addFormField(Text::make('city'))
    ])
```

---

## Custom Components

Create your own component types.

### Basic Custom Component

```php
<?php

namespace App\Components;

use Litepie\Layout\Components\BaseComponent;

class AlertSection extends BaseComponent
{
    protected string $message;
    protected string $type = 'info';

    public function __construct(string $name)
    {
        parent::__construct($name, 'alert');
    }

    public static function make(string $name): self
    {
        return new static($name);
    }

    public function message(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function type(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'name' => $this->name,
            'message' => $this->message,
            'alert_type' => $this->type,
            'sections' => array_map(
                fn($comp) => method_exists($comp, 'toArray') ? $comp->toArray() : (array) $comp,
                $this->sections
            ),
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

### Usage

```php
use App\Components\AlertSection;

->addSection(
    AlertSection::make('warning')
        ->message('Please save your work')
        ->type('warning')
)
```

---

## API Reference

### LayoutBuilder

```php
LayoutBuilder::create(string $module, string $context): self
->formSection(string $name): FormSection
->textSection(string $name): TextSection
->cardSection(string $name): CardSection
->tableSection(string $name): TableSection
->gridSection(string $name): GridSection
->tabsSection(string $name): TabsSection
->accordionSection(string $name): AccordionSection
->scrollSpySection(string $name): ScrollSpySection
->customSection(string $name, string $type = 'custom'): CustomSection
->addComponent(Component $component): self
->build(): Layout
```

### Layout

```php
->getModule(): string
->getContext(): string
->getComponents(): array
->getComponent(string $name): ?Component
->addComponent(Component $component): self
->getAllFormFields(): array
->getFormFieldByName(string $name): mixed
->resolveAuthorization($user = null): self
->forUser($user): self
->getAuthorizedComponents(): array
->toArray(): array
->toAuthorizedArray(): array
->render(): array
```

### BaseComponent

```php
->getName(): string
->getType(): string
->order(int $order): self
->visible(bool $visible = true): self
->hidden(): self
->meta(array $meta): self
->permissions(array|string $permissions): self
->roles(array|string $roles): self
->canSee(\Closure $callback): self
->addSection(Component $section): self
->addSections(array $sections): self
->getSections(): array
->getSection(string $name): ?Component
->hasSections(): bool
->resolveAuthorization($user = null): self
->isAuthorizedToSee(): bool
->toArray(): array
->render(): array
```

---

## Real-World Examples

### CMS Article Editor

```php
$layout = LayoutBuilder::create('cms', 'article.edit')
    ->tabsSection('article-editor')
        ->position('left')
        ->addTab('content', 'Content', [
            FormSection::make('basic')
                ->label('Article Content')
                ->addFormField(Text::make('title'))
                ->addFormField(RichEditor::make('body'))
                ->addSection(
                    CardSection::make('seo-preview')
                        ->title('SEO Preview')
                        ->addItem('Title', '[Dynamic]')
                        ->addItem('URL', '[Dynamic]')
                )
        ])
        ->addTab('media', 'Media', [
            GridSection::make('media-grid')
                ->columns(2)
                ->addSection(
                    FormSection::make('featured')
                        ->label('Featured Image')
                        ->addFormField(Image::make('featured_image'))
                )
                ->addSection(
                    FormSection::make('gallery')
                        ->label('Gallery')
                        ->addFormField(Gallery::make('images'))
                )
        ])
        ->addTab('settings', 'Settings', [
            AccordionSection::make('advanced')
                ->addItem('seo', 'SEO', [
                    FormSection::make('seo-form')
                        ->addFormField(Text::make('meta_title'))
                        ->addFormField(Textarea::make('meta_description'))
                ])
                ->addItem('publishing', 'Publishing', [
                    FormSection::make('publish-form')
                        ->addFormField(DateTime::make('publish_at'))
                        ->addFormField(Select::make('status'))
                ])
        ])
    ->build();
```

### E-commerce Product Editor

```php
$layout = LayoutBuilder::create('shop', 'product.edit')
    ->formSection('product-details')
        ->label('Product Information')
        ->addFormField(Text::make('name'))
        ->addFormField(Number::make('price'))
        ->addSection(
            TabsSection::make('product-tabs')
                ->addTab('variants', 'Variants', [
                    TextSection::make('intro')
                        ->content('Manage size, color variations...'),
                    GridSection::make('variants-list')
                        ->columns(1)
                        ->addSection(
                            CardSection::make('variant-1')
                                ->title('Small / Red')
                                ->addSection(
                                    FormSection::make('variant-form')
                                        ->columns(3)
                                        ->addFormField(Text::make('sku'))
                                        ->addFormField(Number::make('price'))
                                        ->addFormField(Number::make('stock'))
                                )
                        )
                ])
                ->addTab('shipping', 'Shipping', [
                    AccordionSection::make('shipping-options')
                        ->addItem('domestic', 'Domestic', [
                            FormSection::make('domestic-form')
                                ->addFormField(Number::make('rate'))
                        ])
                        ->addItem('international', 'International', [
                            FormSection::make('intl-form')
                                ->addFormField(Number::make('rate'))
                        ])
                ])
        )
    ->build();
```

### Application Settings

```php
$layout = LayoutBuilder::create('settings', 'app')
    ->accordionSection('settings-groups')
        ->multiple()
        ->addItem('general', 'General', [
            FormSection::make('general-form')
                ->addFormField(Text::make('app_name'))
                ->addFormField(Text::make('app_url'))
                ->addSection(
                    CardSection::make('preview')
                        ->title('Preview')
                        ->addItem('Header', '[App Name]')
                )
        ])
        ->addItem('email', 'Email', [
            TabsSection::make('email-tabs')
                ->addTab('smtp', 'SMTP', [
                    FormSection::make('smtp-form')
                        ->addFormField(Text::make('host'))
                        ->addFormField(Number::make('port'))
                ])
                ->addTab('templates', 'Templates', [
                    GridSection::make('template-grid')
                        ->columns(2)
                        ->addSection(
                            CardSection::make('welcome')
                                ->title('Welcome Email')
                                ->addAction('Edit', '/emails/welcome')
                        )
                ])
        ])
    ->build();
```

---

## Best Practices

1. **Logical Nesting**: Match nesting depth to UI structure
2. **Reasonable Depth**: Keep to 3-4 levels when possible
3. **Authorization**: Use at appropriate levels for security
4. **Component Choice**: Pick the right component for the job
5. **Performance**: Consider frontend rendering with deep nesting
6. **Consistency**: Use consistent naming conventions
7. **Documentation**: Comment complex nested structures

---

## Migration from v2.x

### Key Changes

1. **Section/Subsection → Components**: Old rigid structure replaced with flexible components
2. **Infinite Nesting**: Any component can contain other components
3. **New Components**: Added TabsSection, AccordionSection, ScrollSpySection
4. **Naming**: `addComponent()` → `addSection()` (backwards compatible)

### Migration Example

**v2.x:**
```php
->section('user')
    ->subsection('profile')
        ->addFormField(Text::make('name'))
```

**v3.0:**
```php
->formSection('profile')
    ->label('User Profile')
    ->addFormField(Text::make('name'))
    ->addSection(
        CardSection::make('preview')
            ->title('Profile Preview')
    )
```

---

## Support & Resources

- **Documentation**: https://github.com/litepie/layout/docs
- **Issues**: https://github.com/litepie/layout/issues
- **License**: MIT
- **Version**: 3.0+

---

© 2025 Litepie Layout Package. Released under the MIT License.

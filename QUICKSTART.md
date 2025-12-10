# Quick Start Guide

Get started with Litepie Layout in under 5 minutes.

## Installation

```bash
composer require litepie/layout litepie/form
```

## Basic Usage

### 1. Create Your First Layout

```php
use Litepie\Layout\Facades\Layout;
use Litepie\Form\Field;

$layout = Layout::for('user', 'profile')
    ->section('personal')
        ->label('Personal Information')
        ->subsection('basic')
            ->label('Basic Details')
            ->addFormFields([
                Field::make('text', 'name')->label('Name')->required(),
                Field::make('email', 'email')->label('Email')->required(),
            ])
        ->endSubsection()
    ->endSection()
    ->build();
```

### 2. Get Layout Data

```php
// Convert to array
$data = $layout->toArray();

// Get all form fields
$fields = $layout->getAllFormFields();

// Get specific field
$nameField = $layout->getFormFieldByName('name');
```

### 3. Extract Validation Rules

```php
use Litepie\Layout\Adapters\LayoutFormAdapter;

$fields = $layout->getAllFormFields();
$rules = LayoutFormAdapter::extractValidationRules($fields);

// Use in controller
$validated = $request->validate($rules);
```

### 4. Add Authorization

```php
$layout = Layout::for('admin', 'settings')
    ->section('security')
        ->permissions(['manage-security'])  // Require permission
        ->subsection('api')
            ->roles(['admin'])  // Require role
            ->addFormFields([...])
        ->endSubsection()
    ->endSection()
    ->build();

// Resolve for current user
$layout->resolveAuthorization(auth()->user());

// Get authorized data only
$data = $layout->toAuthorizedArray();
```

### 5. Add Actions & Modals

```php
use Litepie\Layout\ActionModal;

$layout = Layout::for('post', 'edit')
    ->section('content')
        ->action('Delete', '/posts/{id}', [
            'class' => 'btn-danger',
            'modal' => 'delete-confirm'
        ])
        ->addModal(
            ActionModal::make('delete-confirm')
                ->title('Confirm Delete')
                ->addFormField(
                    Field::make('checkbox', 'confirm')
                        ->label('I understand this cannot be undone')
                        ->required()
                )
                ->submitLabel('Delete Post')
        )
        ->subsection('editor')
            ->addFormFields([...])
        ->endSubsection()
    ->endSection()
    ->build();
```

### 6. Multi-Column Layouts

```php
$layout = Layout::for('settings', 'display')
    ->section('theme')
        ->columns(3)  // 3 subsections side-by-side
        ->subsection('colors')
            ->label('Colors')
            ->columns(2)  // 2 fields per row
            ->addFormFields([
                Field::make('color', 'primary')->label('Primary'),
                Field::make('color', 'secondary')->label('Secondary'),
            ])
        ->endSubsection()
        ->subsection('fonts')
            ->label('Fonts')
            ->addFormFields([...])
        ->endSubsection()
    ->endSection()
    ->build();
```

### 7. Conditional Visibility

```php
$layout = Layout::for('checkout', 'payment')
    ->section('billing')
        ->subsection('address')
            ->addFormField(
                Field::make('checkbox', 'different_billing')
                    ->label('Use different billing address')
            )
        ->endSubsection()
        ->subsection('billing_details')
            ->visibleWhen('different_billing', '==', true)
            ->addFormFields([
                Field::make('text', 'billing_street')->label('Street'),
                Field::make('text', 'billing_city')->label('City'),
            ])
        ->endSubsection()
    ->endSection()
    ->build();
```

## Controller Example

```php
namespace App\Http\Controllers;

use Litepie\Layout\Facades\Layout;
use Litepie\Layout\Adapters\LayoutFormAdapter;

class ProfileController extends Controller
{
    public function edit()
    {
        $layout = Layout::get('user', 'profile');
        $layout->resolveAuthorization(auth()->user());
        
        return view('profile.edit', [
            'layout' => $layout->toAuthorizedArray(),
            'user' => auth()->user()
        ]);
    }
    
    public function update(Request $request)
    {
        $layout = Layout::get('user', 'profile');
        $fields = $layout->getAllFormFields();
        $rules = LayoutFormAdapter::extractValidationRules($fields);
        
        $validated = $request->validate($rules);
        auth()->user()->update($validated);
        
        return redirect()->route('profile.edit')
            ->with('success', 'Profile updated!');
    }
}
```

## View Example

```blade
{{-- Using Litepie/Form for rendering --}}
@foreach($layout['sections'] as $section)
    <div class="section">
        <h2>{{ $section['label'] }}</h2>
        
        @foreach($section['subsections'] as $subsection)
            <div class="subsection">
                <h3>{{ $subsection['label'] }}</h3>
                
                @foreach($subsection['fields'] as $field)
                    {{-- Render field using Litepie/Form or your custom logic --}}
                    <div class="field">
                        <label>{{ $field['label'] }}</label>
                        {{-- Field input here --}}
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
@endforeach
```

## Common Patterns

### Register Layout in Config

```php
// config/layout.php
'layouts' => [
    'user.profile' => function() {
        return LayoutBuilder::create('user', 'profile')
            ->section('info')
                ->subsection('basic')
                    ->addFormFields([...])
                ->endSubsection()
            ->endSection()
            ->build();
    },
],
```

### Clear Cache

```php
// Clear specific layout
Layout::clearCache('user', 'profile');

// Clear for specific user
Layout::clearCache('user', 'profile', $userId);
```

### Custom Authorization

```php
$section->canSee(function($user) {
    return $user->isAdmin() && $user->hasVerifiedEmail();
});
```

## Next Steps

- Read the [README.md](README.md) for complete API reference
- Check [examples/integration_examples.php](examples/integration_examples.php) for more examples
- See [MIGRATION.md](MIGRATION.md) if upgrading from v1.x
- Visit [Litepie/Form documentation](https://github.com/Litepie/Form) for field types

## Need Help?

- Open an issue on GitHub
- Email: info@litepie.com
- Check the full documentation in README.md

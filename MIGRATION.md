# Migration Guide: Litepie Layout v2.0

This guide helps you migrate from Litepie Layout v1.x (with custom Field classes) to v2.0 (with Litepie/Form integration).

## Breaking Changes

### 1. Field Classes Removed

**Before (v1.x):**
```php
$subsection->field('name')
    ->type('text')
    ->label('Name')
    ->required();
```

**After (v2.0):**
```php
use Litepie\Form\Field;

$subsection->addFormField(
    Field::make('text', 'name')
        ->label('Name')
        ->required()
);
```

### 2. Method Name Changes

| Old Method (v1.x) | New Method (v2.0) | Notes |
|-------------------|-------------------|-------|
| `getAllFields()` | `getAllFormFields()` | Returns Litepie/Form field instances |
| `getFieldByName($name)` | `getFormFieldByName($name)` | Returns Litepie/Form field instance |
| `getField($section, $subsection, $field)` | `getFormField($section, $subsection, $field)` | Returns Litepie/Form field instance |
| `$subsection->field($name)` | `$subsection->addFormField($field)` | Pass Field instance instead |
| `$subsection->addField($field)` | `$subsection->addFormField($field)` | Works with Litepie/Form fields |
| `$subsection->getFields()` | `$subsection->getFormFields()` | Returns Litepie/Form field instances |
| `$modal->field($name)` | `$modal->addFormField($field)` | Pass Field instance instead |

### 3. Form Rendering Removed

**Before (v1.x):**
```blade
@include('litepie::layout.render', ['layout' => $layout])
```

**After (v2.0):**
Use Litepie/Form's rendering capabilities:
```php
use Litepie\Form\FormBuilder;

$form = FormBuilder::make('user-profile');
foreach ($layout->getAllFormFields() as $field) {
    $form->addField($field);
}

echo $form->render();
```

### 4. Validation Rule Extraction

**Before (v1.x):**
```php
use Litepie\Layout\Helpers\LayoutHelper;

$rules = LayoutHelper::extractValidationRules($layout);
```

**After (v2.0):**
```php
use Litepie\Layout\Adapters\LayoutFormAdapter;

$fields = $layout->getAllFormFields();
$rules = LayoutFormAdapter::extractValidationRules($fields);
```

## Step-by-Step Migration

### Step 1: Install Litepie/Form

```bash
composer require litepie/form
composer update litepie/layout
```

### Step 2: Update Field Creation

Replace all field creation code:

**Before:**
```php
$layout = Layout::for('user', 'profile')
    ->section('personal')
        ->subsection('basic')
            ->field('first_name')
                ->type('text')
                ->label('First Name')
                ->required()
            ->end()
            ->field('email')
                ->type('email')
                ->label('Email')
            ->end()
        ->endSubsection()
    ->endSection()
    ->build();
```

**After:**
```php
use Litepie\Form\Field;

$layout = Layout::for('user', 'profile')
    ->section('personal')
        ->subsection('basic')
            ->addFormFields([
                Field::make('text', 'first_name')
                    ->label('First Name')
                    ->required(),
                Field::make('email', 'email')
                    ->label('Email'),
            ])
        ->endSubsection()
    ->endSection()
    ->build();
```

### Step 3: Update Method Calls

Replace old method names with new ones:

```php
// Before
$allFields = $layout->getAllFields();
$nameField = $layout->getFieldByName('name');

// After
$allFields = $layout->getAllFormFields();
$nameField = $layout->getFormFieldByName('name');
```

### Step 4: Update Modal Fields

**Before:**
```php
$modal = ActionModal::make('confirm')
    ->title('Confirm Action')
    ->field('reason')
        ->type('textarea')
        ->label('Reason')
    ->end();
```

**After:**
```php
use Litepie\Form\Field;

$modal = ActionModal::make('confirm')
    ->title('Confirm Action')
    ->addFormField(
        Field::make('textarea', 'reason')
            ->label('Reason')
    );
```

### Step 5: Update LayoutHelper Usage

Replace LayoutHelper calls with LayoutFormAdapter:

```php
use Litepie\Layout\Adapters\LayoutFormAdapter;

// Extract validation rules
$fields = $layout->getAllFormFields();
$rules = LayoutFormAdapter::extractValidationRules($fields);

// Extract field attributes
$attributes = LayoutFormAdapter::extractFieldAttributes($field);

// Generate default data
$defaultData = LayoutFormAdapter::generateDefaultData($fields);
```

### Step 6: Update View Rendering

Replace layout blade views with Litepie/Form rendering or custom implementation:

```php
// Option 1: Use Litepie/Form's FormBuilder
use Litepie\Form\FormBuilder;

$formBuilder = FormBuilder::make('user-form');
foreach ($layout->getAllFormFields() as $field) {
    $formBuilder->addField($field);
}

return view('your-view', [
    'form' => $formBuilder,
    'layout' => $layout,
]);
```

```blade
{{-- In your blade file --}}
{!! $form->render() !!}
```

## Field Type Mapping

All field types are now provided by Litepie/Form:

| Layout v1.x Type | Litepie/Form Type | Usage |
|------------------|-------------------|-------|
| `'text'` | `'text'` | `Field::make('text', 'name')` |
| `'email'` | `'email'` | `Field::make('email', 'email')` |
| `'password'` | `'password'` | `Field::make('password', 'password')` |
| `'textarea'` | `'textarea'` | `Field::make('textarea', 'bio')` |
| `'select'` | `'select'` | `Field::make('select', 'country')` |
| `'checkbox'` | `'checkbox'` | `Field::make('checkbox', 'agree')` |
| `'radio'` | `'radio'` | `Field::make('radio', 'gender')` |
| `'file'` | `'file'` | `Field::make('file', 'avatar')` |
| `'date'` | `'date'` | `Field::make('date', 'birthday')` |
| `'number'` | `'number'` | `Field::make('number', 'age')` |

Litepie/Form provides 44+ field types. See [Litepie/Form documentation](https://github.com/Litepie/Form) for complete list.

## Adapter Methods

Use `LayoutFormAdapter` for form-specific operations:

```php
use Litepie\Layout\Adapters\LayoutFormAdapter;

// Create and add field to subsection
$field = LayoutFormAdapter::addField($subsection, 'text', 'name');
$field->label('Name')->required();

// Get fields from section
$fields = LayoutFormAdapter::getFieldsFromSection($section);

// Extract validation rules
$rules = LayoutFormAdapter::extractValidationRules($fields);

// Generate default data
$defaultData = LayoutFormAdapter::generateDefaultData($fields);
```

## Backward Compatibility Notes

### Deprecated Methods

These methods still work but are deprecated:

- `LayoutHelper::extractValidationRules()` - Use `LayoutFormAdapter::extractValidationRules()`
- `LayoutHelper::extractFieldAttributes()` - Use `LayoutFormAdapter::extractFieldAttributes()`
- `LayoutHelper::generateDefaultData()` - Use `LayoutFormAdapter::generateDefaultData()`

### Removed Features

These features have been removed:

- All Field type classes (use Litepie/Form fields instead)
- Built-in form rendering views (use Litepie/Form rendering)
- `field()` method on Subsection (use `addFormField()`)
- `field()` method on ActionModal (use `addFormField()`)

## Testing Your Migration

1. **Update Dependencies:**
   ```bash
   composer require litepie/form
   composer update litepie/layout
   ```

2. **Run Tests:**
   ```bash
   php artisan test
   ```

3. **Check for Errors:**
   - Search for `->field(` in your codebase
   - Search for `getAllFields()` calls
   - Search for `getFieldByName()` calls
   - Search for `use Litepie\Layout\Field` imports

4. **Verify Functionality:**
   - Test form rendering
   - Test validation rule extraction
   - Test authorization features
   - Test action modals

## Need Help?

- Check the [updated documentation](../README.md)
- Review [Litepie/Form documentation](https://github.com/Litepie/Form)
- Open an issue on GitHub

## Benefits of v2.0

✅ **Smaller package** - Focus on layout structure, not form rendering  
✅ **Better integration** - Seamless with Litepie/Form's 44+ field types  
✅ **More features** - Access to advanced form features from Litepie/Form  
✅ **Cleaner separation** - Layout structure vs form rendering  
✅ **Maintained compatibility** - Authorization, actions, and modals still work  

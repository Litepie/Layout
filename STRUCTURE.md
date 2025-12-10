# Litepie Layout - Package Structure

## Documentation Files

### Primary Documentation
- **README.md** - Main package documentation with installation, usage, API reference, and examples
- **MIGRATION.md** - Complete migration guide from v1.x to v2.0 with breaking changes and step-by-step instructions
- **CHANGELOG.md** - Version history and upgrade guide
- **REFACTORING_SUMMARY.md** - Technical details of the v2.0 refactoring process

### Examples
- **examples/integration_examples.php** - 8 comprehensive integration examples with Litepie/Form

## Directory Structure

```
litepie/layout/
├── config/
│   └── layout.php                    # Package configuration
├── examples/
│   └── integration_examples.php      # Integration examples with Litepie/Form
├── resources/
│   ├── css/                          # Package styles (if any)
│   └── js/                           # Package scripts (if any)
├── src/
│   ├── Adapters/
│   │   └── LayoutFormAdapter.php     # Bridge between Layout and Litepie/Form
│   ├── Contracts/
│   │   └── Renderable.php            # Renderable interface
│   ├── Facades/
│   │   └── Layout.php                # Layout facade
│   ├── Helpers/
│   │   └── LayoutHelper.php          # Helper utilities
│   ├── Traits/
│   │   ├── HandlesComputedFields.php # Computed field handling
│   │   └── EvaluatesConditions.php   # Conditional logic
│   ├── ActionModal.php               # Modal component
│   ├── Layout.php                    # Main Layout class
│   ├── LayoutBuilder.php             # Fluent builder
│   ├── LayoutManager.php             # Cache management
│   ├── LayoutServiceProvider.php     # Service provider
│   ├── Section.php                   # Section component
│   └── Subsection.php                # Subsection component
├── tests/                            # Test suite
├── CHANGELOG.md                      # Version history
├── composer.json                     # Package dependencies
├── LICENSE                           # MIT License
├── MIGRATION.md                      # v1.x to v2.0 migration guide
├── README.md                         # Main documentation
└── REFACTORING_SUMMARY.md           # Technical refactoring details
```

## Core Components

### Main Classes

1. **Layout** (`src/Layout.php`)
   - Main layout container
   - Holds sections and metadata
   - Manages authorization resolution
   - Provides array/JSON serialization

2. **Section** (`src/Section.php`)
   - Groups related subsections
   - Supports authorization (permissions/roles)
   - Can have actions and modals
   - Supports multi-column layouts

3. **Subsection** (`src/Subsection.php`)
   - Contains Litepie/Form field instances
   - Supports authorization
   - Can have actions and modals
   - Supports multi-column field layouts
   - Can be collapsible

4. **LayoutBuilder** (`src/LayoutBuilder.php`)
   - Fluent API for building layouts
   - Chainable methods
   - Builds Layout instances

5. **LayoutManager** (`src/LayoutManager.php`)
   - Manages layout registration
   - Handles user-based caching
   - Provides facade interface

### Support Classes

6. **ActionModal** (`src/ActionModal.php`)
   - Modal dialogs for user input
   - Contains Litepie/Form fields
   - Customizable labels and styles

7. **LayoutFormAdapter** (`src/Adapters/LayoutFormAdapter.php`)
   - Bridges Layout and Litepie/Form
   - Extracts validation rules
   - Generates default data
   - Extracts field attributes

8. **LayoutHelper** (`src/Helpers/LayoutHelper.php`)
   - Utility methods for layouts
   - Delegates form operations to adapter
   - Filtering and sorting utilities

### Traits

9. **HandlesComputedFields** (`src/Traits/HandlesComputedFields.php`)
   - Processes computed/derived fields
   - Recursive field calculation

10. **EvaluatesConditions** (`src/Traits/EvaluatesConditions.php`)
    - Evaluates conditional visibility
    - Field dependency checking

## Key Features by Component

### Layout
- Section management
- Authorization resolution
- Caching support
- Field extraction (`getAllFormFields()`)
- Serialization (`toArray()`, `toAuthorizedArray()`)

### Section
- Subsection grouping
- Label, description, icon
- Multi-column layouts
- Authorization (permissions, roles, callbacks)
- Conditional visibility
- Actions and modals
- Ordering

### Subsection
- Litepie/Form field storage
- Label, description, icon
- Multi-column field layouts
- Authorization (permissions, roles, callbacks)
- Conditional visibility
- Collapsible state
- Actions and modals
- Ordering

### LayoutFormAdapter
- Field integration (`addField()`)
- Validation rule extraction
- Attribute extraction
- Default data generation
- Form array conversion

## Integration Points

### With Litepie/Form
- Subsections store `Litepie\Form\Field` instances
- Fields created via `Field::make()`
- Full access to Litepie/Form's 44+ field types
- Validation rules from form fields
- Attributes from form fields

### With Laravel
- Service Provider auto-registration
- Facade support (`Layout::for()`)
- Cache integration
- Config publishing
- Validation integration

### With Authorization
- Laravel permissions (`can()`)
- Role checking (`hasRole()`)
- Custom callbacks
- User-based resolution
- Filtered output

## Usage Patterns

### Basic Pattern
```php
LayoutBuilder::create($module, $context)
    ->section($name)
        ->subsection($name)
            ->addFormFields([...])
        ->endSubsection()
    ->endSection()
    ->build()
```

### With Facade
```php
Layout::for($module, $context)
    ->section($name)
        ->subsection($name)
            ->addFormFields([...])
        ->endSubsection()
    ->endSection()
    ->build()
```

### With Authorization
```php
$layout->resolveAuthorization($user)
$layout->getAuthorizedSections()
$layout->toAuthorizedArray()
```

### With Validation
```php
$fields = $layout->getAllFormFields()
$rules = LayoutFormAdapter::extractValidationRules($fields)
$validator = validator($data, $rules)
```

## Testing

The package includes tests for:
- Layout creation and building
- Section and subsection management
- Authorization resolution
- Field management
- Cache operations
- Array serialization

## Configuration

Default configuration in `config/layout.php`:
- Cache driver
- Cache TTL
- Layout registrations
- Default values

## Dependencies

### Required
- PHP >= 8.1
- Laravel >= 10.0
- litepie/form >= 1.0

### Dev Dependencies
- phpunit/phpunit
- orchestra/testbench

## Package Version

**Current Version:** 2.0.0

**Previous Version:** 1.0.0 (with built-in field classes)

## License

MIT License - See LICENSE file for details

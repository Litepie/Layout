# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [3.0.0] - 2025-01-XX

### Changed
- **BREAKING:** Refactored to component-based architecture
- **BREAKING:** Layout now uses `components` array instead of `sections` array
- **BREAKING:** LayoutBuilder methods changed:
  - Added: `formSection()`, `textSection()`, `cardSection()`, `tableSection()`, `gridSection()`, `customSection()`
  - Added: `addComponent()`, `getComponents()`, `getComponent()`
  - Deprecated: `section()`, `addSection()`, `getSections()`, `getSection()`
- Layout methods updated for components:
  - Added: `getComponents()`, `getComponent()`, `addComponent()`, `getAuthorizedComponents()`
  - Maintained: Legacy `getSections()`, `getSection()`, `getSubsection()` for backward compatibility

### Added
- Component system with infinite nesting capability
- Six built-in component types:
  - `FormSection` - Contains Litepie/Form fields
  - `TextSection` - Display text content
  - `CardSection` - Card-based information display
  - `TableSection` - Tabular data with search/sort
  - `GridSection` - Container for grid layouts
  - `TabsSection` - Organize content into tabs with nested components
  - `CustomSection` - Custom component types
- `Component` interface for custom implementations
- `BaseComponent` abstract class for easy extension
- Support for infinite component nesting via GridSection
- Component ordering with `order()` method
- Component metadata with `meta()` method
- Recursive authorization resolution for nested components
- Comprehensive component examples (docs/examples/COMPONENT_EXAMPLES.md)
- Custom component guide (docs/examples/CUSTOM_COMPONENTS.md)
- Updated documentation reflecting new architecture

### Maintained
- Full backward compatibility with v2.0 Section→Subsection→Field structure
- All authorization features (permissions, roles, canSee)
- LayoutFormAdapter for Litepie/Form integration
- getAllFormFields() works recursively with new component types

### Performance
- More efficient recursive field collection
- Better authorization filtering for nested structures

## [2.0.0] - 2025-12-10

### Changed
- **BREAKING:** Removed all built-in field type classes (Field, TextField, EmailField, etc.)
- **BREAKING:** Integrated with Litepie/Form package for field management
- **BREAKING:** Changed method names:
  - `getAllFields()` → `getAllFormFields()`
  - `getFieldByName()` → `getFormFieldByName()`
  - `getField()` → `getFormField()`
  - `Subsection::field()` → `Subsection::addFormField()`
  - `Subsection::getFields()` → `Subsection::getFormFields()`
- Refactored LayoutHelper to delegate form operations to LayoutFormAdapter
- Updated package description to reflect focus on layout structure

### Added
- LayoutFormAdapter class for bridging Layout and Litepie/Form
- `addFormField()` and `addFormFields()` methods on Subsection
- `getFormFields()` method on Subsection
- `endField()` method on Subsection for better chaining
- ActionModal now works with Litepie/Form field instances
- Comprehensive migration guide (MIGRATION.md)
- Integration examples (examples/integration_examples.php)
- Enhanced README with advanced usage examples

### Removed
- All field type classes (26 classes in src/Fields/)
- Form rendering views (resources/views/)
- Built-in field creation methods
- Field-specific validation logic

### Deprecated
- `LayoutHelper::extractValidationRules()` - Use `LayoutFormAdapter::extractValidationRules()`
- `LayoutHelper::extractFieldAttributes()` - Use `LayoutFormAdapter::extractFieldAttributes()`
- `LayoutHelper::generateDefaultData()` - Use `LayoutFormAdapter::generateDefaultData()`

### Fixed
- Improved separation of concerns between layout structure and form rendering
- Cleaner package architecture focused on core purpose

## [1.0.0] - 2024-XX-XX

### Added
- Initial release

- Fluent API for building layouts
- Section and subsection organization
- Built-in field types (Text, Email, Select, Checkbox, etc.)
- User-based caching
- Action buttons and modals
- Authorization support (permissions, roles)
- Column layouts
- Conditional visibility
- Form rendering views

---

## Upgrade Guide

### From v1.x to v2.0

See [MIGRATION.md](MIGRATION.md) for detailed upgrade instructions.

**Quick Steps:**
1. Install Litepie/Form: `composer require litepie/form`
2. Update field creation to use `Field::make()` from Litepie/Form
3. Update method calls (`getAllFields()` → `getAllFormFields()`)
4. Replace form rendering with Litepie/Form's rendering system
5. Test thoroughly

**Breaking Changes Summary:**
- Field classes removed - use Litepie/Form instead
- Method names changed for clarity
- Form rendering views removed
- LayoutHelper methods deprecated

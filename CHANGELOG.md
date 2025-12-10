# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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

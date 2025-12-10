# Contributing to Litepie Layout

Thank you for considering contributing to Litepie Layout! We welcome contributions from the community.

## Code of Conduct

This project adheres to a Code of Conduct. By participating, you are expected to uphold this code. Please be respectful and constructive in all interactions.

## How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check existing issues to avoid duplicates. When creating a bug report, include:

- **Clear title and description**
- **Steps to reproduce** the issue
- **Expected behavior** vs actual behavior
- **Code samples** if applicable
- **Laravel version**, PHP version, and package version
- **Error messages** or stack traces

### Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues. When creating an enhancement suggestion, include:

- **Clear title and description**
- **Use case** for the enhancement
- **Proposed implementation** (if you have ideas)
- **Examples** of how it would work

### Pull Requests

1. **Fork the repository** and create your branch from `main`
2. **Make your changes** following our coding standards
3. **Add tests** for any new functionality
4. **Update documentation** if needed
5. **Ensure tests pass**: `composer test`
6. **Commit with clear messages** describing what and why
7. **Submit a pull request**

## Development Setup

```bash
# Clone your fork
git clone https://github.com/YOUR-USERNAME/layout.git
cd layout

# Install dependencies
composer install

# Run tests
composer test

# Run code style checks
composer format
```

## Coding Standards

- Follow **PSR-12** coding style
- Write **meaningful variable and method names**
- Add **DocBlocks** for classes and public methods
- Keep methods **focused and small**
- Write **tests** for new features and bug fixes
- Maintain **backward compatibility** when possible

## Testing

All contributions should include tests:

```bash
# Run all tests
composer test

# Run specific test
./vendor/bin/phpunit --filter testMethodName

# Run with coverage
composer test-coverage
```

## Documentation

- Update README.md for user-facing changes
- Update CHANGELOG.md following [Keep a Changelog](https://keepachangelog.com/)
- Add code examples for new features
- Update API documentation

## Commit Messages

- Use present tense ("Add feature" not "Added feature")
- Use imperative mood ("Move cursor to..." not "Moves cursor to...")
- Reference issues and pull requests liberally
- First line should be 50 characters or less
- Add detailed description after blank line if needed

Example:
```
Add support for nested subsections

- Implement recursive subsection handling
- Add tests for nested structures
- Update documentation with examples

Fixes #123
```

## Branch Naming

- `feature/description` - New features
- `fix/description` - Bug fixes
- `docs/description` - Documentation updates
- `refactor/description` - Code refactoring

## Review Process

1. Maintainers will review your PR
2. Address any requested changes
3. Once approved, a maintainer will merge it
4. Your contribution will be included in the next release

## Release Process

Releases follow [Semantic Versioning](https://semver.org/):

- **MAJOR** version for incompatible API changes
- **MINOR** version for backwards-compatible functionality
- **PATCH** version for backwards-compatible bug fixes

## Questions?

Feel free to open an issue for questions or reach out to the maintainers at info@litepie.com.

## License

By contributing, you agree that your contributions will be licensed under the MIT License.

---

Thank you for contributing to Litepie Layout! 🎉

# Security Policy

## Supported Versions

We release patches for security vulnerabilities in the following versions:

| Version | Supported          |
| ------- | ------------------ |
| 2.0.x   | :white_check_mark: |
| 1.0.x   | :x:                |

## Reporting a Vulnerability

**Please do not report security vulnerabilities through public GitHub issues.**

If you discover a security vulnerability within Litepie Layout, please send an email to the maintainers at:

**security@litepie.com**

Please include:

- Type of vulnerability
- Full paths of source file(s) related to the vulnerability
- Location of the affected source code (tag/branch/commit or direct URL)
- Step-by-step instructions to reproduce the issue
- Proof-of-concept or exploit code (if possible)
- Impact of the issue, including how an attacker might exploit it

### What to Expect

- **Acknowledgment**: We'll acknowledge your email within 48 hours
- **Investigation**: We'll investigate and validate the vulnerability
- **Fix Development**: We'll work on a fix and keep you informed of progress
- **Disclosure**: Once fixed, we'll coordinate disclosure timing with you
- **Credit**: We'll publicly credit you for the discovery (if you wish)

### Response Timeline

- **Initial Response**: Within 48 hours
- **Status Update**: Within 7 days
- **Fix Timeline**: Varies by severity (critical issues prioritized)

## Security Update Process

1. Vulnerability reported via email
2. Maintainers acknowledge and begin investigation
3. Fix developed in private repository
4. Fix tested thoroughly
5. Security advisory published
6. Patched version released
7. Public disclosure after users have time to update

## Security Best Practices

When using Litepie Layout:

- **Keep updated**: Always use the latest version
- **Review dependencies**: Regularly update Laravel and Litepie/Form
- **Validate input**: Always validate user input before processing
- **Authorization**: Use proper authorization checks for sensitive layouts
- **Cache security**: Ensure cache is properly secured in production
- **Environment**: Never expose sensitive configuration in version control

## Security Considerations

### Authorization

Always resolve authorization before displaying layouts:

```php
$layout->resolveAuthorization($user);
$data = $layout->toAuthorizedArray();
```

### Input Validation

Always validate form data from layouts:

```php
$rules = LayoutFormAdapter::extractValidationRules($fields);
$validated = $request->validate($rules);
```

### Cache Security

Ensure cache keys are properly scoped per user:

```php
Layout::clearCache('module', 'context', $userId);
```

## Disclosure Policy

We follow a **responsible disclosure** policy:

- Security issues are fixed privately
- Fixes are released as soon as possible
- Public disclosure occurs after users have time to update
- Security advisories are published with CVE numbers when applicable

## Bug Bounty

We currently do not have a bug bounty program, but we deeply appreciate security researchers who help keep our users safe.

## Contact

For security concerns: security@litepie.com
For general questions: info@litepie.com

---

Thank you for helping keep Litepie Layout secure! 🔒

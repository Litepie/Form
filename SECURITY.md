# Security Policy

## Supported Versions

We actively support the following versions of Litepie Form Builder:

| Version | Supported          |
| ------- | ------------------ |
| 1.x     | :white_check_mark: |
| < 1.0   | :x:                |

## Reporting a Vulnerability

We take security seriously. If you discover a security vulnerability within Litepie Form Builder, please send an email to security@litepie.com. All security vulnerabilities will be promptly addressed.

### What to Include

When reporting a security vulnerability, please include:

- A description of the vulnerability
- Steps to reproduce the issue
- Possible impact of the vulnerability
- Any suggested fixes (if you have them)

### Response Timeline

- **Initial Response**: Within 24 hours
- **Status Update**: Within 7 days
- **Fix Timeline**: Depends on severity
  - Critical: Within 1-3 days
  - High: Within 1-2 weeks
  - Medium: Within 1 month
  - Low: Next scheduled release

### Disclosure Policy

We follow responsible disclosure practices:

1. Report is received and acknowledged
2. We investigate and develop a fix
3. We coordinate the release of the fix
4. Public disclosure after fix is available

### Security Best Practices

When using Litepie Form Builder:

- Always validate and sanitize user input
- Use CSRF protection (enabled by default)
- Keep your Laravel framework updated
- Use the latest version of this package
- Enable file upload restrictions
- Validate file types and sizes
- Use proper authentication and authorization

### Security Features

This package includes:

- CSRF protection by default
- XSS protection through Laravel's built-in escaping
- File upload validation and restrictions
- Input sanitization
- Rate limiting support
- Secure file handling

### Contact

- **Security Email**: security@litepie.com
- **General Support**: support@litepie.com
- **Issues**: [GitHub Issues](https://github.com/litepie/form/issues) (for non-security issues only)

Thank you for helping keep Litepie Form Builder secure!

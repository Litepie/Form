# Contributing to Litepie Form Builder

We love your input! We want to make contributing to Litepie Form Builder as easy and transparent as possible, whether it's:

- Reporting a bug
- Discussing the current state of the code
- Submitting a fix
- Proposing new features
- Becoming a maintainer

## We Develop with GitHub

We use GitHub to host code, to track issues and feature requests, as well as accept pull requests.

## We Use [GitHub Flow](https://guides.github.com/introduction/flow/index.html)

Pull requests are the best way to propose changes to the codebase. We actively welcome your pull requests:

1. Fork the repo and create your branch from `main`.
2. If you've added code that should be tested, add tests.
3. If you've changed APIs, update the documentation.
4. Ensure the test suite passes.
5. Make sure your code lints.
6. Issue that pull request!

## Development Setup

1. Fork and clone the repository
2. Install dependencies:
   ```bash
   composer install
   npm install
   ```
3. Run tests to ensure everything works:
   ```bash
   composer test
   ```

## Code Style

We use PSR-12 coding standards for PHP code:

```bash
# Check code style
composer format:check

# Fix code style issues
composer format
```

For JavaScript/CSS:
```bash
# Check and fix
npm run lint
npm run format
```

## Testing

We use PHPUnit for testing. Please write tests for any new functionality:

```bash
# Run all tests
composer test

# Run specific test file
composer test tests/FormBuilderTest.php

# Run with coverage
composer test:coverage
```

## Documentation

- Update relevant documentation in the `doc/` folder
- Add examples for new features
- Update the main `readme.md` if needed
- Follow the existing documentation style

## Pull Request Process

1. **Create a feature branch**: `git checkout -b feature/amazing-feature`
2. **Make your changes**: Write code, tests, and documentation
3. **Test thoroughly**: Ensure all tests pass
4. **Commit with clear messages**: Use conventional commit format
5. **Push to your fork**: `git push origin feature/amazing-feature`
6. **Open a Pull Request**: Describe your changes clearly

### Commit Message Format

We follow [Conventional Commits](https://www.conventionalcommits.org/):

```
type(scope): description

[optional body]

[optional footer(s)]
```

Examples:
- `feat(fields): add map field with Google Maps integration`
- `fix(validation): resolve real-time validation timing issue`
- `docs(containers): add examples for single form extraction`
- `test(caching): add comprehensive cache functionality tests`

## Issue Reporting

We use GitHub issues to track public bugs. Report a bug by [opening a new issue](../../issues/new).

**Great Bug Reports** tend to have:

- A quick summary and/or background
- Steps to reproduce
  - Be specific!
  - Give sample code if you can
- What you expected would happen
- What actually happens
- Notes (possibly including why you think this might be happening, or stuff you tried that didn't work)

## Feature Requests

We welcome feature requests! Please:

1. Check if the feature already exists or is planned
2. Open an issue with the `enhancement` label
3. Describe the feature and its benefits
4. Provide examples of how it would be used

## Code of Conduct

### Our Pledge

In the interest of fostering an open and welcoming environment, we pledge to make participation in our project a harassment-free experience for everyone.

### Our Standards

Examples of behavior that contributes to creating a positive environment include:

- Using welcoming and inclusive language
- Being respectful of differing viewpoints and experiences
- Gracefully accepting constructive criticism
- Focusing on what is best for the community
- Showing empathy towards other community members

### Enforcement

Instances of abusive, harassing, or otherwise unacceptable behavior may be reported by contacting the project team at conduct@litepie.com.

## Recognition

Contributors will be recognized in:

- The project's contributors list
- Release notes for significant contributions
- Special mentions for outstanding contributions

## Development Guidelines

### Adding New Field Types

1. Create the field class in `src/Fields/`
2. Add to the appropriate field group (Basic, Advanced, Complex)
3. Write comprehensive tests
4. Add examples to `doc/examples.md`
5. Update the main readme if it's a major addition

### Modifying Container Features

1. Update the `FormContainer` class
2. Add tests to `FormContainerTest.php`
3. Update container examples in `doc/container-examples.md`
4. Consider backward compatibility

### Performance Improvements

1. Benchmark your changes
2. Add performance tests if applicable
3. Update caching documentation if relevant
4. Consider memory usage and execution time

### Documentation Updates

1. Keep examples current and working
2. Add new features to appropriate documentation files
3. Update the table of contents if adding new sections
4. Ensure links work correctly

## Questions?

Don't hesitate to ask questions:

- Open an issue with the `question` label
- Join our [discussions](../../discussions)
- Email us at dev@litepie.com

## License

By contributing, you agree that your contributions will be licensed under the MIT License.

---

Thank you for contributing to Litepie Form Builder! 🎉

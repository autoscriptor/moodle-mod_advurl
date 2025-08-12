# Contributing to Advanced URL

Thank you for your interest in contributing to the Advanced URL Moodle plugin! This document provides guidelines and information for contributors.

## 🤝 How to Contribute

### Reporting Issues

Before creating a new issue, please:

1. **Search existing issues** to see if your problem has already been reported
2. **Check the documentation** to see if there's already a solution
3. **Provide detailed information** including:
   - Moodle version
   - PHP version
   - Plugin version
   - Steps to reproduce
   - Expected vs actual behavior
   - Screenshots (if applicable)

### Feature Requests

We welcome feature requests! Please:

1. **Describe the feature** clearly and concisely
2. **Explain the use case** and why it would be valuable
3. **Consider implementation** - is it feasible within Moodle's architecture?
4. **Check existing issues** to avoid duplicates

### Code Contributions

#### Prerequisites

- **Moodle Development Environment**: Set up a local Moodle development environment
- **PHP Knowledge**: Familiarity with PHP and Moodle's coding standards
- **Git**: Basic understanding of Git and GitHub workflow

#### Development Setup

1. **Fork the repository** on GitHub
2. **Clone your fork** locally:
   ```bash
   git clone https://github.com/autoscriptor/moodle-mod_advurl.git
   cd moodle-mod_advurl
   ```
3. **Install in Moodle**:
   ```bash
   cp -r . /path/to/moodle/mod/advurl/
   ```
4. **Run Moodle upgrade**:
   ```bash
   php /path/to/moodle/admin/cli/upgrade.php
   ```

#### Coding Standards

This project follows **Moodle's coding standards**:

- **Frankenstyle**: All functions must use `mod_advurl_` prefix
- **PHPDoc**: Comprehensive documentation for all functions and classes
- **Security**: Input validation and output escaping
- **Accessibility**: WCAG 2.1 compliance
- **Database**: Use Moodle's `$DB` global for database operations

#### Code Style

- **Indentation**: 4 spaces (no tabs)
- **Line length**: Maximum 132 characters
- **Naming**: Use descriptive names for variables and functions
- **Comments**: Explain complex logic, not obvious code

#### Testing

Before submitting a pull request:

1. **Test your changes** thoroughly in a development environment
2. **Check for errors** in Moodle's error log
3. **Test different scenarios** (different user roles, course contexts)
4. **Verify database upgrades** work correctly
5. **Test backward compatibility** with existing data

#### Pull Request Process

1. **Create a feature branch**:
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. **Make your changes** and commit them:
   ```bash
   git add .
   git commit -m "Add feature: brief description"
   ```

3. **Push to your fork**:
   ```bash
   git push origin feature/your-feature-name
   ```

4. **Create a pull request** on GitHub with:
   - Clear title describing the change
   - Detailed description of what was changed and why
   - Reference to any related issues
   - Screenshots (if UI changes)

5. **Wait for review** and address any feedback

## 📋 Development Guidelines

### Database Changes

When modifying database schema:

1. **Update `db/install.xml`** for new installations
2. **Add upgrade steps** in `db/upgrade.php`
3. **Test upgrades** from previous versions
4. **Document changes** in the changelog

### Language Strings

All user-facing text must be in language files:

1. **Add strings** to `lang/en/advurl.php`
2. **Use placeholders** (`{$a}`) for dynamic content
3. **Provide context** in help text
4. **Consider translations** - keep strings clear and concise

### Security Considerations

- **Validate all input** from users
- **Escape all output** using Moodle's output functions
- **Check capabilities** before allowing actions
- **Use CSRF protection** for forms
- **Follow Moodle's security guidelines**

### Performance

- **Minimize database queries** - use efficient SQL
- **Cache data** when appropriate
- **Optimize file operations**
- **Consider scalability** for large installations

## 🏷️ Issue Labels

We use the following labels to categorize issues:

- **bug**: Something isn't working
- **enhancement**: New feature or request
- **documentation**: Improvements or additions to documentation
- **good first issue**: Good for newcomers
- **help wanted**: Extra attention is needed
- **question**: Further information is requested
- **wontfix**: This will not be worked on

## 📞 Getting Help

If you need help with development:

1. **Check the documentation** in the README
2. **Search existing issues** for similar problems
3. **Ask in the issue** - we're happy to help!
4. **Contact the maintainer** at walid.alieldin@gmail.com

## 🎉 Recognition

Contributors will be:

- **Listed in the README** as contributors
- **Mentioned in release notes** for significant contributions
- **Given credit** in the changelog

## 📄 License

By contributing to this project, you agree that your contributions will be licensed under the same license as the project (GNU General Public License v3.0).

## 🙏 Thank You

Thank you for contributing to the Moodle community! Your contributions help make education technology better for everyone.

---

**Happy coding! 🚀**

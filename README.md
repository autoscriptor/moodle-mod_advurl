# Advanced URL - Moodle Activity Module

[![Moodle Plugin](https://img.shields.io/badge/Moodle-Plugin-orange.svg)](https://moodle.org/plugins/)
[![Moodle Version](https://img.shields.io/badge/Moodle-4.0+-blue.svg)](https://moodle.org/)
[![License](https://img.shields.io/badge/License-GPL%20v3-green.svg)](LICENSE)
[![Version](https://img.shields.io/badge/Version-1.1.6-brightgreen.svg)](version.php)

A powerful Moodle activity module that enhances external link management with advanced features including YouTube embedding, broken link reporting, and course-level configuration.

## 🚀 Features

### ✨ Core Functionality
- **External Link Management**: Safely open external websites in new tabs
- **Dynamic Disclaimer**: Customizable warnings with institution name
- **YouTube Integration**: Automatic detection and embedding of YouTube videos
- **Broken Link Reporting**: Configurable reporting system with status management
- **Backup & Restore**: Full Moodle 2 format backup and restore support
- **Event Tracking**: Comprehensive activity logging and analytics

### 🎯 Teacher Features
- **Course Dashboard**: Centralized management interface
- **Configurable Email**: Set course-wide report email addresses
- **Report Management**: View and manage broken link reports
- **Status Tracking**: Track report status (Open, Resolved, False Positive)

### 🛡️ Security & Privacy
- **GDPR Compliant**: Full privacy provider implementation
- **Capability-based Access**: Granular permission control
- **Session Security**: CSRF protection on all forms

## 📋 Requirements

- **Moodle**: 4.0 or later
- **PHP**: 7.4 or later
- **Database**: MySQL 5.7+ or PostgreSQL 10+

## 🛠️ Installation

### Method 1: Git Clone
```bash
cd /path/to/moodle/mod
git clone https://github.com/autoscriptor/moodle-mod_advurl.git advurl
```

### Method 2: Manual Installation
1. Download the latest release
2. Extract to `/path/to/moodle/mod/advurl/`
3. Visit Site Administration → Notifications
4. Complete the installation

### Method 3: Moodle Plugin Directory (Coming Soon)
1. Go to Site Administration → Plugins → Install plugins
2. Search for "Advanced URL"
3. Install directly from Moodle plugin directory

## ⚙️ Configuration

### Initial Setup
1. **Add to Course**: Add "Advanced URL" activity to your course
2. **Configure Email**: Visit the Advanced URL Dashboard in course navigation
3. **Set Report Email**: Enter the email address for broken link reports

### Activity Settings
- **URL**: The external link to open
- **Detect YouTube Links**: Automatically embed YouTube videos
- **Show Disclaimer**: Display external content warning
- **Description**: Optional activity description

## 📖 Usage

### For Teachers
1. **Create Activity**: Add Advanced URL to your course
2. **Configure Settings**: Set URL and options
3. **Manage Reports**: Use dashboard to handle broken link reports
4. **Monitor Status**: Track report resolution

### For Students
1. **View Activity**: Access the Advanced URL activity
2. **Read Disclaimer**: Review external content warning
3. **Open Link**: Click "Open Link in a New Tab" button
4. **Report Issues**: Use "Report Broken Link" if needed

## 🏗️ Architecture

### Database Tables
- `advurl`: Main activity instances
- `advurl_reports`: Broken link reports
- `advurl_course_settings`: Course-level configuration

### Key Files
- `lib.php`: Core functionality and YouTube detection
- `view.php`: Activity display and user interface
- `dashboard.php`: Teacher management interface
- `report.php`: Broken link reporting system
- `mod_form.php`: Activity configuration form
- `backup/`: Backup functionality for course exports
- `restore/`: Restore functionality for course imports
- `classes/event/`: Event tracking and analytics

### Capabilities
- `mod/advurl:addinstance`: Create activities
- `mod/advurl:view`: View activities
- `mod/advurl:reportbroken`: Report broken links
- `mod/advurl:viewreports`: Access dashboard

## 🔧 Development

### Local Development Setup
```bash
# Clone the repository
git clone https://github.com/autoscriptor/moodle-mod_advurl.git

# Install in Moodle development environment
cp -r moodle-mod_advurl /path/to/moodle/mod/advurl

# Run Moodle upgrade
php /path/to/moodle/admin/cli/upgrade.php
```

### Code Standards
This plugin follows Moodle's coding standards:
- **Frankenstyle**: All functions use `mod_advurl_` prefix
- **PHPDoc**: Comprehensive documentation
- **Security**: Input validation and output escaping
- **Accessibility**: WCAG 2.1 compliant

## 🤝 Contributing

We welcome contributions! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

### Development Process
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📝 Changelog

### Version 1.1.6 (2025-10-01)
- **Fixed**: Course restore functionality - removed incorrect require_once statement in restore task
- **Improved**: Compatibility with Moodle's auto-loading backup/restore framework
- **Note**: Moodle automatically discovers and loads restore classes based on naming conventions

### Version 1.1.5
- **Fixed**: Version number conflict resolution (2025091501)
- **Fixed**: Added FEATURE_BACKUP_MOODLE2 support for plugin review compliance
- **Updated**: Version number for Moodle.org plugin directory resubmission
- **Enhanced**: Backup and restore functionality declaration

### Version 1.1.0
- **Added**: Full backup and restore functionality for Moodle 2 format
- **Added**: Event tracking system for course module views and analytics
- **Added**: Privacy provider updates for new data types
- **Enhanced**: Language strings for new features
- **Improved**: Version management and upgrade path

### Version 1.0.9
- **Fixed**: YouTube video aspect ratio to eliminate black bars
- **Improved**: Custom URL validation with better error messages
- **Enhanced**: User experience with clearer validation feedback

### Version 1.0.8
- **Added**: Custom URL validation error messages
- **Improved**: User guidance for URL format requirements

### Version 1.0.7
- **Fixed**: YouTube responsive embed parameters
- **Enhanced**: Video display with modern CSS aspect-ratio

### Version 1.0.6
- **Fixed**: YouTube video aspect ratio issues
- **Improved**: Video container styling

### Version 1.0.5
- **Fixed**: CSS compatibility for YouTube embed styling
- **Enhanced**: Cross-version Moodle compatibility

### Version 1.0.4
- **Added**: Backward compatibility wrappers for function names
- **Fixed**: Fresh installation compatibility issues

### Version 1.0.3
- **Fixed**: Syntax error in language file
- **Improved**: Code quality

### Version 1.0.2
- **Fixed**: "Show Disclaimer" default value
- **Enhanced**: Form behavior consistency

### Version 1.0.1
- **Fixed**: Function names to comply with Moodle frankenstyle standards
- **Improved**: Code quality and documentation

### Version 1.0.0
- **Initial Release**: Complete Advanced URL functionality
- **Features**: YouTube embedding, broken link reporting, dashboard
- **Security**: GDPR compliance and capability-based access

## 📄 License

This project is licensed under the GNU General Public License v3.0 - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Author

**Walid Alieldin**
- Email: walid.alieldin@gmail.com
- GitHub: [@autoscriptor](https://github.com/autoscriptor)

## 🙏 Acknowledgments

- Moodle Community for the excellent platform
- Contributors and testers
- Educational institutions providing feedback

## 📞 Support

- **Issues**: [GitHub Issues](https://github.com/autoscriptor/moodle-mod_advurl/issues)
- **Documentation**: [Wiki](https://github.com/autoscriptor/moodle-mod_advurl/wiki)
- **Email**: walid.alieldin@gmail.com

## 🔗 Links

- [Moodle.org](https://moodle.org/)
- [Plugin Directory](https://moodle.org/plugins/) (Coming Soon)
- [Developer Documentation](https://docs.moodle.org/dev/)

---

**Made with ❤️ for the Moodle Community**

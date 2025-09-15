# Advanced URL – Enhanced External Link Tool for Moodle

## 👨‍💻 Developer:
**Walid Alieldin** - walid.alieldin@gmail.com

---

## 📌 Purpose and Overview

The **Advanced URL plugin** is a custom Moodle activity module that improves upon Moodle's core "URL" resource. Its goal is to offer:
- Safer and more consistent user experience when accessing **external learning resources**.
- **YouTube video embedding** for seamless viewing experience.
- Administrative tools for **reporting and managing broken links**.
- Better integration into the course page without iframe security issues.
- Built-in tools for instructors and admins to monitor external content health.

---

## ✅ Current Functionality Summary

### 1. Activity Display (Frontend)
- Each Advanced URL item:
  - Shows its **name** and **description** on the course page.
  - Opens the external link in a **new browser tab** upon click (or embeds YouTube videos).
  - Displays a **disclaimer message** alerting users that the link is external and outside institutional control.

### 2. View Page Features
- Two buttons displayed prominently:
  - **"Open Link in a New Tab"** – opens the external resource in a new tab (or YouTube).
  - **"Report Broken Link"** – submits a report to the system (see below).
- A **disclaimer message** is displayed (customizable per institution name).
- **YouTube Detection**: Automatically detects and embeds YouTube videos when enabled.
- **Responsive Video Player**: Modern aspect ratio with no black bars.
- A **footer notice** is present at the bottom of the page.

### 3. YouTube Integration
- **Automatic Detection**: Recognizes YouTube URLs in various formats.
- **Smart Embedding**: Embeds videos directly on the page when enabled.
- **Fallback Support**: Always provides "Open in New Tab" option.
- **Responsive Design**: Mobile-friendly video player with modern aspect ratio.
- **No Letterboxing**: Eliminates black bars for optimal viewing experience.

---

## 🧠 Reporting System

### Report Button Behavior:
- Appears to all authenticated users with appropriate permissions.
- **Always visible** regardless of email configuration.
- If email is configured: Sends a **broken link report** via `email_to_user()` to configured course email.
- If no email is configured: Still logs the report to the dashboard for tracking.
- Report includes:
  - External URL
  - Course name and ID
  - Activity name and ID
  - Reporting user's name and ID
  - Timestamp

### Database Table:
- Custom table `advurl_reports` logs:
  - Reported URL
  - Reporting user ID
  - Course ID
  - Activity ID
  - Timestamp
  - Report status (`open`, `resolved`, `false_positive`)

---

## ⚙️ Admin and Teacher Interfaces

### Dashboard Features:
- **Course Settings**: Configure report email address per course.
- **Reports Management**: View and manage all broken link reports.
- **Status Updates**: Mark reports as resolved, false positive, or reopen.
- **Real-time Updates**: Instant status changes with visual feedback.
- **Email Validation**: Server-side validation for email addresses.
- **Status Tracking**: Color-coded badges for easy identification.

### Teacher View:
- **Advanced URL Dashboard** accessible from course navigation.
- View **all broken link reports** associated with their course.
- Update report status with one-click actions.
- Color-coded status badges for easy identification.

---

## 🔧 Technical Details

### Plugin Type:
- Moodle **Activity Module** (mod)

### Plugin Name:
- `mod_advurl`

### Moodle Version Compatibility:
- Tested on Moodle **4.0+**

### Key Files:
- `view.php`: renders the external link page, buttons, and disclaimer.
- `dashboard.php`: course settings and reports management.
- `report.php`: handles broken link submissions and email notifications.
- `lib.php`: implements plugin logic, hooks, and YouTube detection.
- `mod_form.php`: activity creation/editing form with custom validation.
- `version.php`: defines plugin version and metadata.
- `db/install.xml`: defines database tables.
- `db/upgrade.php`: manages version upgrades.
- `lang/en/advurl.php`: contains plugin language strings.
- `pix/icon.svg`: plugin icon.
- `classes/privacy/provider.php`: GDPR compliance implementation.
- `backup/`: backup functionality for course exports (Moodle 2 format).
- `restore/`: restore functionality for course imports (Moodle 2 format).
- `classes/event/`: event tracking system for analytics and logging.

### Current Version:
- Release: `1.1.0` (Stable)

---

## 🚫 Removed Features

- **iframe embedding** has been completely disabled due to security concerns and inconsistent display behavior.
- The `display` setting was deprecated in code but may still be present in legacy installs for backwards compatibility.
- **Hardcoded email addresses** - now configurable per course.
- **Generic error messages** - replaced with custom, user-friendly validation messages.

---

## 💡 Future Improvement Suggestions

- Bulk broken link reporting dashboard (with CSV export).
- Scheduled task that pings links and flags failures.
- Student comments with reports.
- Feedback loop for instructors.
- Analytics dashboard showing link usage and broken links.
- Support for additional media platforms (Vimeo, audio files, etc.).
- Advanced filtering and sorting in reports table.
- Auto-prepend https:// for URLs without protocol.
- Enhanced YouTube analytics and tracking.

---

## 🧳 Deployment

- Upload the plugin folder to `/mod/advurl`.
- Install via **Site administration → Plugins → Install plugins**, or extract manually and complete the upgrade.
- Requires working Moodle mail configuration (`email_to_user`).
- Configure report email addresses through the Advanced URL Dashboard.

---

## 📋 Requirements

- Moodle 4.0 or later
- Working email configuration
- Teacher permissions to access dashboard

---

## 🔒 Security & Privacy

- All external content warnings are displayed
- User activity is logged for broken link reports
- GDPR compliant with data export/deletion capabilities
- No personal data stored beyond reporting functionality
- CSRF protection on all forms
- Input validation and output escaping
- Capability-based access control

---

## 🆕 Recent Improvements (v1.1.0)

### Backup & Restore System:
- **Full Moodle 2 format support** for course backup and restore
- **Complete data preservation** including all activity settings and reports
- **Seamless migration** between Moodle installations
- **Backward compatibility** with existing course backups

### Event Tracking & Analytics:
- **Course module view tracking** for usage analytics
- **Instance list view events** for comprehensive monitoring
- **Privacy-compliant logging** with GDPR data export/deletion
- **Enhanced reporting capabilities** for administrators

### Privacy & Security Enhancements:
- **Updated privacy provider** with new data type definitions
- **Enhanced data export** functionality for user requests
- **Improved data deletion** processes for GDPR compliance
- **Comprehensive event logging** for audit trails

## Previous Improvements (v1.0.9)

### YouTube Video Enhancement:
- **Fixed aspect ratio** to eliminate black bars (letterboxing)
- **Modern CSS implementation** using aspect-ratio property
- **Responsive design** that works across all devices
- **Enhanced embed parameters** for better performance

### User Experience Improvements:
- **Custom URL validation** with helpful error messages
- **Better form feedback** for URL format requirements
- **Improved error handling** throughout the plugin
- **Enhanced visual design** with modern styling

### Technical Enhancements:
- **Frankenstyle compliance** for all function names
- **Backward compatibility** for fresh installations
- **Cross-version compatibility** across Moodle versions
- **Comprehensive upgrade path** from all previous versions


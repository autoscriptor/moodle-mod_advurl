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
- A **disclaimer message** is displayed (customizable per institution).
- **YouTube Detection**: Automatically detects and embeds YouTube videos when enabled.
- A **footer notice** is present at the bottom of the page.

### 3. YouTube Integration
- **Automatic Detection**: Recognizes YouTube URLs in various formats.
- **Smart Embedding**: Embeds videos directly on the page when enabled.
- **Fallback Support**: Always provides "Open in New Tab" option.
- **Responsive Design**: Mobile-friendly video player.

---

## 🧠 Reporting System

### Report Button Behavior:
- Appears to all authenticated users (students, teachers, etc.)
- Sends a **broken link report** via `email_to_user()` to configured course email.
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
- `mod_form.php`: activity creation/editing form.
- `version.php`: defines plugin version and metadata.
- `db/install.xml`: defines database tables.
- `db/upgrade.php`: manages version upgrades.
- `lang/en/advurl.php`: contains plugin language strings.
- `pix/icon.svg`: plugin icon.

### Current Version:
- **2025072900**
- Release: `1.0.1` (Stable)

---

## 🚫 Removed Features

- **iframe embedding** has been completely disabled due to security concerns and inconsistent display behavior.
- The `display` setting was deprecated in code but may still be present in legacy installs for backwards compatibility.

---

## 💡 Future Improvement Suggestions

- Bulk broken link reporting dashboard (with CSV export).
- Scheduled task that pings links and flags failures.
- Student comments with reports.
- Feedback loop for instructors.
- Analytics dashboard showing link usage and broken links.
- Support for additional media platforms (Vimeo, audio files, etc.).
- Advanced filtering and sorting in reports table.

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


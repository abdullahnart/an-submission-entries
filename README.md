=== Abdullah Naseem Submission Manager for Contact Form 7 ===
Contributor: abdullahnart
Tags: cf7 database, cf7, contact form 7 entries, contact form 7, submissions
Requires at least: 6.5
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.1.7
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires Plugins: contact-form-7

Store and manage Contact Form 7 submissions from your WordPress dashboard.

== Description ==

Abdullah Naseem Submission Manager for Contact Form 7
allows administrators to view and manage saved form submissions
directly from the WordPress dashboard.

Instead of searching through different forms or dealing with raw database records, you can view your **Contact Form 7 forms first**, select a form, and then view the submissions belonging to that specific form.

---

## ✨ Features

* 📋 **List all Contact Form 7 forms**
* 📥 **View submissions by form**
* 🔢 **Submission count for each form**
* 🗃️ **Store submissions in a dedicated database table**
* 🆔 **Associate submissions with the Contact Form 7 form ID**
* 🕒 **Record submission date and time**
* 🎨 **Clean and modern WordPress admin interface**
* 📱 **Responsive admin layout**
* 🔒 **WordPress capability-based admin access**
* ⚡ **Lightweight and simple**
* 🧩 **Works with existing Contact Form 7 forms**
* 🛠️ **No complicated configuration required**

---

## 📸 How It Works

### 1. Open Submission Entries

After installation, you'll find a new menu in your WordPress dashboard:

**AN Submission Entries**

The plugin provides a dedicated interface for managing your form submissions.

### 2. Select a Form

The first screen displays your available Contact Form 7 forms.

Each form shows:

* Form name
* Contact Form 7 form ID
* Number of stored submissions
* View Entries button

### 3. View Form Entries

Click **View Entries** to see submissions belonging to that specific form.

Each submission displays:

* Entry ID
* Submission date and time
* Submitted field names
* Submitted values

This keeps submissions organized and prevents entries from different forms from being mixed together.

---

## 🖥️ Admin Interface

The plugin is designed around a simple workflow:


AN Submission Entries
        │
        ▼
Contact Form 7 Forms
        │
        ├── Contact Us
        │      └── View Entries
        │
        ├── Request a Demo
        │      └── View Entries
        │
        └── Support Form
               └── View Entries


This makes it easy to manage submissions even when your website contains multiple Contact Form 7 forms.

---

## 📦 Installation

### Method 1: WordPress Admin

1. Download the plugin.
2. Log in to your WordPress dashboard.
3. Go to **Plugins → Add New Plugin**.
4. Click **Upload Plugin**.
5. Upload the plugin ZIP file.
6. Click **Install Now**.
7. Activate the plugin.

### Method 2: Manual Installation

1. Download the plugin ZIP file.
2. Extract the ZIP file.
3. Upload the `an-submission-entries` folder to:


/wp-content/plugins/


4. Go to **WordPress Dashboard → Plugins**.
5. Find **AN Submission Entries for Contact Form 7**.
6. Click **Activate**.

---

## 🔌 Requirements

Before using the plugin, make sure your website has:

* WordPress **5.0 or higher**
* PHP **7.4 or higher**
* [Contact Form 7](https://wordpress.org/plugins/contact-form-7/) installed and activated

The plugin is designed to work alongside Contact Form 7 and stores submissions when forms are submitted.

---

## ⚙️ Usage

After activating the plugin:

1. Create or use an existing Contact Form 7 form.
2. Add the form to a page.
3. Submit the form from the frontend.
4. Open:

**WordPress Dashboard → AN Submission Entries**

5. Select the form.
6. Click **View Entries**.
7. Review the submitted information.

No shortcode or additional configuration is required for the WordPress admin submission manager.

---

## 🗄️ Data Storage

The plugin stores submissions in a dedicated WordPress database table:


wp_cf7_submissions


The actual table prefix depends on your WordPress installation.

For example:


wp_cf7_submissions


or:


wp123_cf7_submissions


The table stores information such as:

| Field            | Description               |
| ---------------- | ------------------------- |
| `id`             | Unique submission ID      |
| `form_id`        | Contact Form 7 form ID    |
| `form_title`     | Contact Form 7 form title |
| `submitted_data` | Submitted form data       |
| `submit_time`    | Submission date and time  |

The plugin uses WordPress's database API and `dbDelta()` for database management.

---

## 🔐 Security

AN Submission Entries uses WordPress security practices including:

* WordPress capability checks
* Sanitized form IDs
* Escaped admin output
* Prepared database queries
* WordPress database APIs
* Serialized submission data for structured storage

Only users with the appropriate WordPress administrative capability can access the submission management interface.


## 🎯 Why Use AN Submission Entries?

Contact Form 7 focuses on creating and processing forms, while AN Submission Entries provides a convenient way to keep and review submitted entries inside WordPress.

Instead of building a custom submission management system for every website, you can use one dedicated interface:

**Forms → Select Form → View Entries**

It's intentionally lightweight and focused on making submission management easier.

---

## 🚀 Roadmap

Future versions may introduce additional submission management features, including:

* 🔍 Submission search
* 📄 Pagination
* 👁️ Individual submission view
* 🗑️ Delete submissions
* ☑️ Bulk actions
* 📥 CSV export
* 📅 Date filtering
* 🔔 New/unread submission indicators
* 📊 Submission statistics
* 📎 File attachment handling
* 📧 Submission notifications
* 📤 Advanced export options

---

## ❓ Frequently Asked Questions

### Does this plugin require Contact Form 7?

Yes. Contact Form 7 must be installed and activated for the plugin to capture Contact Form 7 submissions.

### Where can I view submissions?

Go to:

**WordPress Dashboard → AN Submission Entries**

Select a form and click **View Entries**.

### Does the plugin modify my Contact Form 7 forms?

No. The plugin works alongside Contact Form 7 and does not require you to rebuild your existing forms.

### Does the plugin store submissions in WordPress?

Yes. Submitted data is stored in a dedicated database table associated with your WordPress installation.

### Can I use it with multiple Contact Form 7 forms?

Yes. The plugin detects your Contact Form 7 forms and organizes submissions by form.

### What happens if I have many forms?

All available Contact Form 7 forms are displayed in the admin interface, with a submission count for each form.

### Do I need to configure a shortcode?

No. The admin submission manager works without any shortcode configuration.


---

## 🛡️ Privacy

This plugin stores form submission data in your WordPress database.

The type of information stored depends on the fields included in your Contact Form 7 forms.

Website administrators should review their site's privacy policy and ensure that storing submitted information complies with applicable privacy requirements.

If your forms collect personal information, clearly inform visitors about how their submitted data is stored and used.

---

## 🌐 External Services

AN Submission Entries does not require an external API or third-party cloud service to store submissions.

Submission data is stored on your own WordPress installation.

The plugin works with Contact Form 7, which is a separate WordPress plugin.

---

## 📜 License

AN Submission Entries for Contact Form 7 is licensed under the:

**GNU General Public License v2.0 or later**


GPL-2.0-or-later


You are free to use, modify, and distribute the plugin according to the terms of the license.

---

## 👨‍💻 Author

**Abdullah Naseem**

GitHub:

https://github.com/abdullahnart/

---

## 💙 Support & Contributions

Found a bug, have an idea, or want to contribute?

You can open an issue or submit improvements through the project's GitHub repository:

https://github.com/abdullahnart/an-submission-entries

---

## 📋 Changelog

### 1.1.0

* Added dedicated **AN Submission Entries** admin menu.
* Added Contact Form 7 form listing.
* Added submission count for each form.
* Added form-specific submission viewing.
* Added Contact Form 7 form ID to stored submissions.
* Improved database structure.
* Added modern WordPress admin styling.
* Added responsive admin interface.
* Improved submission data presentation.
* Added admin capability checks.
* Improved database queries and escaping.
* Added activation-based database setup.
* Initial submission management functionality.
* Added Contact Form 7 submission storage.
* Added basic submission display.


---

## ⭐ AN Submission Entries for Contact Form 7

A lightweight way to turn Contact Form 7 submissions into an organized, easy-to-manage WordPress dashboard experience.

**Create forms. Receive submissions. Manage entries.**


== Disclaimer ==

This is an independent plugin and is not affiliated with or
endorsed by the Contact Form 7 project or its developers.

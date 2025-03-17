=== Contact Form 7 Registration Addon ===
Contributors: yourusername
Tags: contact form 7, user registration, PayPal, custom post type, WordPress
Requires at least: 5.4
Tested up to: 6.5
Requires PHP: 7.4
Stable tag: 1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A Contact Form 7 addon that registers users after a successful PayPal payment and creates a Custom Post Type (CPT) entry.

== Description ==

This plugin extends **Contact Form 7** by allowing administrators to:

- Select a Contact Form 7 form for user registration.
- Map form fields dynamically to:
  - **Email**
  - **Password**
  - **CPT Title**
  - Additional meta fields.
- Process **PayPal payments** before user registration.
- Create a **new user account** after successful payment.
- Automatically generate a **Custom Post Type (CPT)** entry with the new user as the author.
- Allow users to **log in and modify their listing**.

== Installation ==

1. Download the plugin ZIP file.
2. Upload the plugin to `/wp-content/plugins/` directory.
3. Activate the plugin through the **Plugins** menu in WordPress.
4. Go to **Settings > CF7 Registration Addon** to configure the plugin.

== Usage ==

1. Navigate to **Settings > CF7 Registration Addon**.
2. Select a **Contact Form 7 form** for user registration.
3. The plugin will automatically fetch all fields from the selected form.
4. Map the form fields to **Email, Password, CPT Title, and other fields**.
5. Save the settings.
6. Ensure the selected CF7 form is added to a page.
7. When users submit the form:
   - A **PayPal API** call is triggered.
   - If the **payment is successful**, the user is **registered**.
   - A **CPT entry** is created and assigned to the user.

== Frequently Asked Questions ==

= Do I need Contact Form 7 installed? =
Yes, this plugin requires **Contact Form 7** to function.

= How does PayPal integration work? =
The plugin uses the **PayPal API** to process payments before user registration. You can modify `includes/paypal-api.php` to customize payment handling.

= Can users edit their CPT listing? =
Yes, once a user is registered, they can **log in** and **modify their post**.

= What happens if the payment fails? =
If the **PayPal payment fails**, the user **will not be created**, and no CPT entry will be added.

== Changelog ==

= 1.0 =

- Initial release.
- Integrated Contact Form 7 field mapping.
- Implemented PayPal payment processing.
- Automated WordPress user creation.
- Added CPT entry creation with user as author.

== Upgrade Notice ==

= 1.0 =
Initial version. No upgrade steps needed.

== License ==

This plugin is released under the **GPL v2 or later** license.

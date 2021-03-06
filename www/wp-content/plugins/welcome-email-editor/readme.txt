=== Plugin Name ===
Contributors: seanbarton
Tags: welcome email, wordpress welcome email, welcome email editor, mail, email, new user email, password reminder
Requires at least: 3.0
Tested up to: 3.3

A Plugin to provide an interface for the Wordpress Welcome Email. Allows adding of headers to prevent emails going into spam and changes to the text. Also offers a password reminder service accessable via the quick options on the admin users page.

== Description ==

I thought that the Wordpress Welcome Email to both the Admin and the User were very un-user friendly so I wrote this plugin to allow admin members to change the content and headers.

It simply adds a new admin page that has a few options for the welcome email and gives you a list of hooks to use in the text to make the email a little more personal.

Added support whereby the admin notification can be turned off or a different admin (or admins, support for multiple recipients) can be notified. Plenty of hooks to make the emails as customisable as possible.

A reminder email service has now been added whereby the admin user can send a reminder to any particular user. This can be the original welcome email or a separate template configured on the Welcome Email Editor settings page.

Please email me or use the support forum if you have ideas for extending it or find any issues and I will be back to you as soon as possible.

I would recommend the use of an SMTP service with any Wordpress plugin. A large amount of emails fall needlessly into Spam bins across the world (I get a fair amount of comment approval spam to deal with) because the Wordpress site uses Sendmail to deliver email. I noticed an immediate improvement when using SMTP to send. It's really easy so there's no excuse :) 

Changelog:
<V1.6 - Didn't quite manage to add a changelog until now :)
V1.6 - 25/3/11 - Added user_id and custom_fields as hooks for use
V1.7 - 17/4/11 - Added password reminder service and secondary email template for it's use
V1.8 - 24/8/11 - Added [admin_email] hook to be parsed for both user and admin email templates instead of just the email headers
V1.9 - 24/10/11 - Removed conflict with User Access Manager plugin causing the resend welcome email rows to now show on the user list
V2.0 - 27/10/11 - Moved the user column inline next to the edit and delete user actions to save space
V2.1 - 17/11/11 - Added multisite support so that the welcome email will be edited and sent in the same way as the single site variant
V2.2 - 12/12/11 - Added edit box for the subject line and body text for the reminder email. Added option to turn off the reminder service
V2.3 - 16/12/11 - Broke the reminder service in the last update. This patch sorts it out. Also tested with WP 3.3
V2.4 - 03/01/12 - Minor update to disable the reminder service send button in the user list. Previously only stopped the logging but the button remained

== Installation ==

1. Upload the contents of the ZIP file to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Visit the admin page it creates at the bottom of the left menu
4. Edit the settings as desired and click save.

Once complete, all new user emails will be sent in the new format.

== Screenshots ==

Don't look at screenshots of admin pages... Just give it a go :) If you must then see the following address for more information...

Screenshots available at: http://www.sean-barton.co.uk/wordpress-welcome-email-editor/
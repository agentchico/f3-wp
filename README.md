# F3 Wordpress plugin
Unofficial F3 Plugin for Wordpress that adds F3 BackBlasts and AO functionality. Should be compatible
with most themes and Wordpress installations.

## Primary Features

* Creates a `Workout` custom post type to store workouts and backblasts
* Creates an `AO` taxonomy, to keep track of multiple AOs
* Adds F3 related fields and metaboxes to `Workout` and `AO`
  * Workouts get QIC and PAX fields that autocomplete to registered Wordpress users.
  * AOs get color, map (location), day of week, and time fields.
* Registers two shortcodes: `[f3_ao_list]` and `[f3_calendar]`
* Adds fields to the Registration form (F3 Nickname)
* Enables scheduling of future workouts, and QIC scheduling on an interactive calendar, optionally filtered by AO

### Installation
1) Upload this repo to your `wp-content/plugins` directory as `wp-content/plugins/f3`.
2) [Register for a Google Maps API key for your domain](https://developers.google.com/maps/documentation/javascript/get-api-key)
and replace the `$MAPS_KEY` variable in `f3.php` with your key.
   * Alternatively, comment on this repo with your domain and I can add you as an authorized domain on the key included in the repo.
3) Enable and activate the plugin from the Wordpress Plugins page
4) Configure your AOs by going to the new Workouts menu and clicking AOs

### Configuration
The recommended configuration is to create two pages, one for the calendar view and one for the AO view, and link
to them in your menu. Check out [https://f3austin.com](f3austin.com) for an example of what that looks like.

* On your `schedule` page, add the `[f3_calendar]` shortcode to the body of the page.
* On your `AOs` page, add the `[f3_ao_list]` shortcode to the body of the page.

### Notes
* Make sure your PAX are classified as `authors` if you want them to be able to post backblasts and register as QIC
* This plugin enables authors to edit each others' posts. This makes it easier to schedule and update workouts (eg setting a QIC). 
This might cause problems for some locations. Leave a comment if you have ideas.
* This plugin cleans up some admin panel links for non-admin users. If you use a lot of plugins, you might want to modify these settings 
from the `disable_admin.php` file.

### Potential future features
* Profile page per PAX, to show Qd workouts and PAXed workouts
* More advanced modal view for workouts on the Calendar shortcode
* "Post This Workout" button for PAX to register themselves for workouts
* Additional shortcodes
* Email newsletter to list upcoming workouts and allow members to register as Q for scheduled workouts without a QIC
* Allow entering PAX/QIC manually if users are not registered on the site

### About
This plugin was built by Rubber (Andrew M) in F3 Austin for our site. Pull requests or comments are welcome.

#### Built using: 
* [CMB2](https://cmb2.io/) for custom metaboxes (plus ajax and map extensions)
* [fullcalendar.io](https://fullcalendar.io) for the Calendar interface

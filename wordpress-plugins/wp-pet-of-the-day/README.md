# Pet of the Day WordPress Plugin

A WordPress plugin that automatically selects and displays a "Pet of the Day" from all registered user pets. This plugin works in conjunction with the User Pets Manager plugin.

## Features

- Automatically selects a random pet daily
- Displays pet information with photo
- Customizable shortcode with multiple options
- Admin interface to view current selection and history
- Manual refresh option
- Selection history tracking
- Responsive design

## Requirements

- WordPress 5.0 or higher
- PHP 7.2 or higher
- **User Pets Manager plugin** (must be installed and activated)

## Installation

1. Make sure the User Pets Manager plugin is installed and activated first
2. Upload the `wp-pet-of-the-day` folder to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. The plugin will automatically start selecting a pet daily

## Usage

### Shortcode

Use the following shortcode to display the Pet of the Day:

```
[pet_of_the_day]
```

### Shortcode Options

- `title` - Custom title for the section (default: "Pet of the Day")
- `show_owner` - Show or hide owner name (yes/no, default: yes)
- `show_description` - Show or hide pet description (yes/no, default: yes)

### Examples

```
[pet_of_the_day title="Meet Today's Featured Pet"]
[pet_of_the_day show_owner="no"]
[pet_of_the_day show_description="no"]
[pet_of_the_day title="Featured Friend" show_owner="yes" show_description="yes"]
```

## Admin Features

- **View Current Pet**: See which pet is currently featured
- **Manual Refresh**: Force select a new pet before the daily automatic selection
- **Selection History**: View the last 10 pets that were featured
- **Statistics**: See total number of pets available for selection

## How It Works

1. The plugin automatically selects a random pet at midnight each day
2. The selected pet is cached for 24 hours
3. After 24 hours, a new pet is automatically selected
4. Admins can manually force a new selection at any time

## Support

For issues and questions, please visit the [GitHub repository](https://github.com/kdiggz87/D2Dropper).

## License

This plugin is licensed under the GPL v2 or later.

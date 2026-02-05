# User Pets Manager WordPress Plugin

A WordPress plugin that allows registered users to add and manage their pets on their account. Users can add multiple pets with photos and information.

## Features

- User-friendly interface for managing pets
- Add, edit, and delete pets
- Upload pet photos
- Store pet information (name, type, breed, age, description)
- Admin dashboard to view all pets
- Display pets using shortcodes
- Secure and permission-based access

## Installation

1. Upload the `wp-user-pets` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. The plugin will automatically create the necessary database tables

## Usage

### Shortcodes

The plugin provides the following shortcodes:

- `[user_pets]` - Display user's pets with add/edit/delete form
- `[user_pets show_form="no"]` - Display only the user's pets list without the form
- `[user_pets_form]` - Display only the add pet form

### Example Usage

1. Create a new page (e.g., "My Pets")
2. Add the shortcode `[user_pets]` to the page content
3. Publish the page
4. Registered users can now access this page to manage their pets

## Admin Features

- **User Pets Menu**: View all pets from all users
- **Settings Page**: Instructions and shortcode documentation
- **User Profile**: View pets for individual users in their profile

## Requirements

- WordPress 5.0 or higher
- PHP 7.2 or higher
- MySQL 5.6 or higher

## Support

For issues and questions, please visit the [GitHub repository](https://github.com/kdiggz87/D2Dropper).

## License

This plugin is licensed under the GPL v2 or later.

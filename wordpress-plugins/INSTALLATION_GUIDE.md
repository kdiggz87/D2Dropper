# WordPress Pet Management Plugins - Installation & Usage Guide

This package contains two WordPress plugins for managing user pets and displaying a "Pet of the Day" feature.

## Plugins Included

1. **User Pets Manager** - Allows registered users to add and manage their pets
2. **Pet of the Day** - Displays a randomly selected pet daily on your site

## System Requirements

- WordPress 5.0 or higher
- PHP 7.2 or higher
- MySQL 5.6 or higher

## Installation Instructions

### Step 1: Install User Pets Manager Plugin

1. Copy the `wp-user-pets` folder to your WordPress plugins directory:
   ```
   /wp-content/plugins/wp-user-pets/
   ```

2. Log in to your WordPress admin dashboard

3. Navigate to **Plugins > Installed Plugins**

4. Find "User Pets Manager" and click **Activate**

5. The plugin will automatically create the necessary database tables

### Step 2: Install Pet of the Day Plugin

1. Copy the `wp-pet-of-the-day` folder to your WordPress plugins directory:
   ```
   /wp-content/plugins/wp-pet-of-the-day/
   ```

2. In your WordPress admin dashboard, go to **Plugins > Installed Plugins**

3. Find "Pet of the Day" and click **Activate**

4. The plugin will automatically start tracking pet selections

## Configuration

### Setting Up User Pet Management

1. In WordPress admin, navigate to **User Pets** in the sidebar menu

2. You'll see an overview of all pets added by users

3. Go to **User Pets > Settings** to see available shortcodes and instructions

### Creating a Pet Management Page

1. Create a new page: **Pages > Add New**

2. Give it a title like "My Pets" or "Manage Pets"

3. In the content area, add the shortcode:
   ```
   [user_pets]
   ```

4. Publish the page

5. Share the page URL with your users so they can manage their pets

### Setting Up Pet of the Day

1. Create a new page or edit your home page

2. Add the Pet of the Day shortcode where you want it to appear:
   ```
   [pet_of_the_day]
   ```

3. Customize with options if desired:
   ```
   [pet_of_the_day title="Meet Today's Featured Pet" show_owner="yes" show_description="yes"]
   ```

4. Publish or update the page

## Available Shortcodes

### User Pets Manager

- `[user_pets]` - Full interface with pet list and add/edit form
- `[user_pets show_form="no"]` - Pet list only, no form
- `[user_pets_form]` - Add pet form only

### Pet of the Day

- `[pet_of_the_day]` - Display the current Pet of the Day
- `[pet_of_the_day title="Custom Title"]` - With custom title
- `[pet_of_the_day show_owner="no"]` - Hide owner name
- `[pet_of_the_day show_description="no"]` - Hide description

## User Guide

### For Site Users - Adding Pets

1. Log in to your account

2. Navigate to the "My Pets" page (or wherever the admin placed the shortcode)

3. Fill in the pet information form:
   - Pet Name (required)
   - Pet Type (required) - Select from dropdown
   - Breed (optional)
   - Age (optional)
   - Description (optional)
   - Photo (optional) - Click "Upload Photo" to add an image

4. Click "Add Pet" to save

5. Your pet will appear in your pets list

6. You can edit or delete pets using the buttons on each pet card

### For Administrators

#### Managing All Pets

1. Go to **User Pets** in the WordPress admin menu

2. View all pets from all users

3. Click on a user's name to see their profile and all their pets

#### Managing Pet of the Day

1. Go to **User Pets > Pet of the Day**

2. View the current featured pet

3. See selection history

4. Manually select a new pet using "Select New Pet Now" button

5. Statistics show total available pets

## Features

### User Pets Manager Features

- ✅ User-friendly pet management interface
- ✅ Multiple pets per user
- ✅ Pet photo uploads with WordPress media library integration
- ✅ Detailed pet information (name, type, breed, age, description)
- ✅ Edit and delete functionality
- ✅ Admin dashboard to view all pets
- ✅ User profile integration
- ✅ Secure with WordPress nonces and permissions
- ✅ Responsive design

### Pet of the Day Features

- ✅ Automatic daily pet selection
- ✅ Random selection algorithm
- ✅ 24-hour caching
- ✅ Manual refresh option for admins
- ✅ Selection history tracking
- ✅ Customizable display options
- ✅ Responsive design with animations
- ✅ Beautiful card-based layout

## Troubleshooting

### Pet of the Day shows "requires User Pets Manager"

Make sure the User Pets Manager plugin is installed and activated first.

### Pets not showing up

1. Ensure users are logged in when adding pets
2. Check that the shortcode is properly placed on the page
3. Verify both plugins are activated

### Photos not uploading

1. Check WordPress media upload permissions
2. Verify the uploads directory is writable
3. Check PHP upload_max_filesize settings

### Pet of the Day not changing daily

1. Verify WordPress cron is working properly
2. Try manually refreshing from the admin panel
3. Check server timezone settings

## Security Features

- All database queries use prepared statements
- Data is sanitized and validated
- CSRF protection with WordPress nonces
- User permission checks
- Secure file upload handling

## Support & Contribution

For issues, questions, or contributions:
- Visit the GitHub repository: https://github.com/kdiggz87/D2Dropper

## License

Both plugins are licensed under GPL v2 or later.

---

**Enjoy your new pet management system!** 🐶🐱🐦🐠🐰

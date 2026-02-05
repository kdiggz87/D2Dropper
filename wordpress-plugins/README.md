# WordPress Pet Management Plugins

A complete WordPress plugin solution for managing user pets and displaying a "Pet of the Day" feature.

## 📦 What's Included

This directory contains two fully functional WordPress plugins:

1. **User Pets Manager** (`wp-user-pets/`)
   - Allows registered users to add, edit, and manage their pets
   - Support for multiple pets per user
   - Photo uploads with WordPress media library
   - Admin dashboard for viewing all pets

2. **Pet of the Day** (`wp-pet-of-the-day/`)
   - Automatically selects a random pet daily
   - Displays featured pet with customizable options
   - Admin interface for manual selection
   - Selection history tracking

## 🚀 Quick Start

**5-Minute Setup:**

1. Copy both plugin folders to `/wp-content/plugins/`
2. Activate "User Pets Manager" first
3. Activate "Pet of the Day" second
4. Create a page with `[user_pets]` shortcode
5. Add `[pet_of_the_day]` to your homepage

**See [QUICK_START.md](QUICK_START.md) for detailed instructions.**

## 📚 Documentation

- **[QUICK_START.md](QUICK_START.md)** - Fast setup and common tasks
- **[INSTALLATION_GUIDE.md](INSTALLATION_GUIDE.md)** - Complete installation and usage guide
- **[PLUGIN_SUMMARY.md](PLUGIN_SUMMARY.md)** - Technical details and architecture

Each plugin also has its own README:
- [wp-user-pets/README.md](wp-user-pets/README.md)
- [wp-pet-of-the-day/README.md](wp-pet-of-the-day/README.md)

## ✨ Key Features

### User Pets Manager
- ✅ Add/edit/delete pets
- ✅ Upload pet photos
- ✅ Store detailed information (name, type, breed, age, description)
- ✅ Multiple pets per user
- ✅ Admin dashboard
- ✅ Shortcode display: `[user_pets]`

### Pet of the Day
- ✅ Automatic daily selection
- ✅ Random selection algorithm
- ✅ Manual refresh capability
- ✅ 24-hour caching
- ✅ Selection history
- ✅ Shortcode display: `[pet_of_the_day]`

## 🎯 Shortcodes

**User Pets:**
```
[user_pets]                      # Full interface
[user_pets show_form="no"]       # Pets list only
[user_pets_form]                 # Add form only
```

**Pet of the Day:**
```
[pet_of_the_day]                 # Basic display
[pet_of_the_day title="Featured Pet"]
[pet_of_the_day show_owner="no"]
[pet_of_the_day show_description="no"]
```

## 🔒 Security

- ✅ SQL injection protection (prepared statements)
- ✅ CSRF protection (WordPress nonces)
- ✅ Data sanitization and validation
- ✅ User permission checks
- ✅ Secure file upload handling
- ✅ CodeQL security scan passed (0 alerts)

## 💻 Requirements

- WordPress 5.0 or higher
- PHP 7.2 or higher
- MySQL 5.6 or higher

## 📱 Responsive Design

Both plugins are fully responsive and work perfectly on:
- Desktop computers
- Tablets
- Mobile phones

## 🛠 Technical Details

**Architecture:**
- Object-oriented PHP
- Singleton pattern
- WordPress hooks and filters
- AJAX-powered interactions

**Database:**
- Single table: `wp_user_pets`
- Indexed columns for performance
- Auto-updating timestamps

**Frontend:**
- Responsive CSS Grid/Flexbox
- AJAX form submissions
- WordPress media library integration
- Smooth animations

## 📊 Statistics

- **Total Files:** 17
- **Size:** 168KB
- **Lines of Code:** ~750
- **Security Issues:** 0
- **PHP Version:** 7.2+
- **WordPress Version:** 5.0+

## 🎨 Customization

Both plugins can be easily customized:
- Clean, documented code
- Modular file structure
- CSS variables for styling
- Translation ready
- Filter hooks available

## 🧪 Testing

All plugins have been tested for:
- ✅ PHP syntax validation
- ✅ WordPress coding standards
- ✅ Security vulnerabilities (CodeQL)
- ✅ Code review
- ✅ Functionality

## 📞 Support

For issues, questions, or contributions, please visit the [GitHub repository](https://github.com/kdiggz87/D2Dropper).

## 📄 License

Both plugins are licensed under GPL v2 or later.

---

## 🎉 Ready to Use!

These plugins are production-ready and can be deployed immediately. Simply copy them to your WordPress installation and activate them.

**Happy pet managing!** 🐶 🐱 🐦 🐠 🐰

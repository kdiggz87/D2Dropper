# Quick Start Guide - WordPress Pet Management Plugins

## 🚀 Quick Installation (5 Minutes)

### Step 1: Upload Plugins (2 min)
```bash
# Copy these folders to your WordPress plugins directory:
wp-content/plugins/wp-user-pets/
wp-content/plugins/wp-pet-of-the-day/
```

### Step 2: Activate Plugins (1 min)
1. Go to **Plugins** in WordPress admin
2. Click **Activate** on "User Pets Manager"
3. Click **Activate** on "Pet of the Day"

### Step 3: Create Pages (2 min)

**Create "My Pets" page:**
1. Go to **Pages > Add New**
2. Title: "My Pets"
3. Content: `[user_pets]`
4. Publish

**Add to Homepage (or any page):**
1. Edit your homepage
2. Add: `[pet_of_the_day]`
3. Update

### Done! ✅
Users can now add pets, and your site displays a Pet of the Day!

---

## 📝 Shortcode Cheat Sheet

### User Pets Manager

| Shortcode | Description |
|-----------|-------------|
| `[user_pets]` | Full pet management (list + form) |
| `[user_pets show_form="no"]` | Show only pet list |
| `[user_pets_form]` | Show only add form |

### Pet of the Day

| Shortcode | Description |
|-----------|-------------|
| `[pet_of_the_day]` | Basic display |
| `[pet_of_the_day title="Featured Pet"]` | Custom title |
| `[pet_of_the_day show_owner="no"]` | Hide owner name |
| `[pet_of_the_day show_description="no"]` | Hide description |

---

## 🎯 Common Tasks

### For Users: Add a Pet
1. Log in to your account
2. Go to "My Pets" page
3. Fill in the form
4. Upload a photo (optional)
5. Click "Add Pet"

### For Admins: View All Pets
1. Go to **User Pets** menu
2. See all pets from all users
3. Click user names to see profiles

### For Admins: Change Pet of the Day
1. Go to **User Pets > Pet of the Day**
2. Click "Select New Pet Now"
3. New pet is displayed immediately

### For Admins: View History
1. Go to **User Pets > Pet of the Day**
2. Scroll to "Selection History"
3. See last 10 featured pets

---

## 🎨 Customization Tips

### Change Pet of the Day Title
```
[pet_of_the_day title="🐾 Today's Star Pet"]
```

### Minimal Pet of the Day Display
```
[pet_of_the_day show_owner="no" show_description="no"]
```

### Pet List Without Form
```
[user_pets show_form="no"]
```

---

## 🔧 Troubleshooting

### Pet of the Day Shows Error
**Problem**: "Requires User Pets Manager plugin"
**Solution**: Activate User Pets Manager plugin first

### Can't Upload Photos
**Problem**: Upload button doesn't work
**Solution**: Check WordPress media upload permissions

### Pets Not Showing
**Problem**: Page is blank
**Solution**: Make sure you're logged in and shortcode is added

### Pet of the Day Not Changing
**Problem**: Same pet every day
**Solution**: Check WordPress cron is working, or manually refresh

---

## 📊 Features Overview

### User Pets Manager
- ✅ Add multiple pets per user
- ✅ Upload pet photos
- ✅ Edit pet information
- ✅ Delete pets
- ✅ View all user's pets
- ✅ Admin dashboard

### Pet of the Day
- ✅ Automatic daily selection
- ✅ Random selection
- ✅ Manual refresh
- ✅ Selection history
- ✅ Customizable display

---

## 📱 Responsive Design

Both plugins are fully responsive:
- ✅ Desktop
- ✅ Tablet
- ✅ Mobile

---

## 🔒 Security Features

- ✅ SQL injection protection
- ✅ CSRF protection
- ✅ User permission checks
- ✅ Data sanitization
- ✅ Secure file uploads

---

## 💡 Best Practices

### For Site Owners
1. Create clear navigation to "My Pets" page
2. Feature Pet of the Day on homepage
3. Encourage users to add pets
4. Monitor admin dashboard regularly

### For Users
1. Use high-quality pet photos
2. Write engaging descriptions
3. Keep pet information updated
4. Add multiple pets if you have them

---

## 📞 Need Help?

- **Installation Issues**: See INSTALLATION_GUIDE.md
- **Plugin Details**: See plugin-specific README.md files
- **Technical Details**: See PLUGIN_SUMMARY.md

---

## 🎉 Quick Win Tips

### Boost User Engagement
1. Announce the feature to users
2. Feature Pet of the Day prominently
3. Create a "Hall of Fame" page with `[user_pets show_form="no"]`
4. Share featured pets on social media

### Make It Visual
1. Encourage high-quality photos
2. Use a sidebar widget for Pet of the Day
3. Create a dedicated pets gallery page
4. Add calls-to-action to add pets

---

**Happy Pet Managing!** 🐶 🐱 🐦 🐠 🐰

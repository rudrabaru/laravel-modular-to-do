# Testing Guide for Laravel Modular To-Do with Spatie Roles & Permissions

## 🎯 Overview
This guide will help you test all the role-based features including the Admin, Manager, and User panels.

## 👥 Default Users Created by Seeder

### Admin User
- **Email**: `admin@example.com`
- **Password**: `password`
- **Role**: Admin
- **Permissions**: All permissions

### Manager User
- **Email**: `manager@example.com`
- **Password**: `password`
- **Role**: Manager
- **Permissions**: Limited permissions (no role/permission management)

### Regular User
- **Email**: `user@example.com` (create this manually)
- **Password**: `password`
- **Role**: User
- **Permissions**: Basic task and reminder management

## 🧪 Testing Scenarios

### 1. Testing Admin Panel

#### Login as Admin
```bash
# Use these credentials
Email: admin@example.com
Password: password
```

#### What to Test:
1. **Dashboard Access**
   - Should redirect to `/admin/dashboard`
   - Should see all users and their task counts
   - Should see role management links

2. **Role Management**
   - Click "Manage Roles" → Should see roles list
   - Click "Assign Roles" → Should see users with role assignment options
   - Create new roles and assign permissions
   - Edit existing roles and modify permissions

3. **Task Management**
   - Click "View All Tasks" → Should see all tasks from all users
   - Click "Create Task" → Should be able to assign tasks to any user
   - Edit/Delete any task
   - View task details with user information

4. **User Management**
   - View all users with their roles
   - Assign/change roles for any user
   - See user statistics

### 2. Testing Manager Panel

#### Login as Manager
```bash
# Use these credentials
Email: manager@example.com
Password: password
```

#### What to Test:
1. **Dashboard Access**
   - Should redirect to `/manager/dashboard`
   - Should see users (but NOT admin users)
   - Should NOT see role management options

2. **Task Management**
   - Click "View All Tasks" → Should see all tasks
   - Click "Create Task" → Should be able to create tasks
   - Edit/Delete tasks
   - Cannot manage roles or permissions

3. **Limited Access**
   - Should NOT see "Manage Roles" or "Assign Roles" buttons
   - Should NOT see admin users in the user list
   - Should see notice about limited access

### 3. Testing User Panel

#### Create and Login as Regular User
```bash
# First, register a new user or create one via admin
# Then login with those credentials
```

#### What to Test:
1. **Dashboard Access**
   - Should redirect to `/user/dashboard`
   - Should see only their own tasks
   - Should see task statistics

2. **Task Management**
   - Create new tasks (assigned to themselves)
   - Edit their own tasks
   - Mark tasks as completed
   - View task details

3. **Reminder Management**
   - Click "Set Reminder" → Create reminders for their tasks
   - View/edit/delete their reminders

4. **Task Lists**
   - Pending tasks with priority badges
   - Overdue tasks highlighted in red
   - Completed tasks with completion time
   - Priority indicators (High/Medium/Low)

## 🔧 Manual Testing Steps

### Step 1: Test Admin Features
1. Login as admin
2. Create a few tasks for different users
3. Create a new role (e.g., "Supervisor")
4. Assign permissions to the new role
5. Assign the new role to a user
6. Test role management features

### Step 2: Test Manager Features
1. Login as manager
2. Verify you can see tasks but not admin users
3. Create tasks for users
4. Verify you cannot access role management
5. Test task management features

### Step 3: Test User Features
1. Login as a regular user
2. Create personal tasks
3. Set reminders
4. Mark tasks as completed
5. Verify overdue highlighting works

### Step 4: Test Permission Restrictions
1. Try accessing admin routes as manager/user
2. Try accessing manager routes as user
3. Verify proper redirects and access denied messages

## 🎨 UI Features to Test

### User Dashboard Enhancements
- **Priority Badges**: High (red), Medium (yellow), Low (green)
- **Overdue Highlighting**: Red background and "Overdue" badge
- **Task Grouping**: Pending and Completed sections
- **Due Date Formatting**: Shows date and time
- **Creation Time**: Shows when task was created
- **Completion Time**: Shows when task was completed
- **Icons**: Calendar and checkmark icons

### Admin Dashboard Features
- **Role Management**: Full CRUD for roles and permissions
- **User Management**: View all users with roles
- **Task Overview**: All tasks from all users
- **Statistics**: Total, pending, completed, overdue counts

### Manager Dashboard Features
- **Limited User View**: No admin users visible
- **Task Management**: Full task CRUD
- **Access Notices**: Clear indication of limited permissions

## 🐛 Common Issues to Check

1. **Route Access**: Ensure proper middleware protection
2. **Permission Checks**: Verify `@can` directives work
3. **Role Assignment**: Test role assignment via admin panel
4. **Data Isolation**: Users should only see their own data
5. **Overdue Detection**: Check if overdue tasks are properly highlighted
6. **Form Validation**: Test all form submissions
7. **Success Messages**: Verify flash messages appear
8. **Error Handling**: Test invalid operations

## 📝 Test Data Creation

### Create Test Tasks
```php
// Via Admin Panel
1. Login as admin
2. Go to "Create Task"
3. Assign to different users
4. Set different priorities and due dates
5. Create some overdue tasks

// Via User Panel
1. Login as user
2. Create personal tasks
3. Set some with past due dates (overdue)
4. Complete some tasks
```

### Create Test Users
```php
// Via Registration
1. Register new users
2. Assign roles via admin panel

// Via Admin Panel
1. Login as admin
2. Go to "Assign Roles"
3. Create users and assign roles
```

## ✅ Success Criteria

### Admin Panel
- [ ] Can access all features
- [ ] Can manage roles and permissions
- [ ] Can assign roles to users
- [ ] Can view all tasks and users
- [ ] Can create/edit/delete any task

### Manager Panel
- [ ] Cannot access role management
- [ ] Cannot see admin users
- [ ] Can manage tasks
- [ ] Sees limited access notice
- [ ] Cannot assign roles

### User Panel
- [ ] Only sees own tasks
- [ ] Can create/edit own tasks
- [ ] Sees proper task grouping
- [ ] Overdue tasks are highlighted
- [ ] Priority badges work correctly
- [ ] Can manage reminders

## 🚀 Quick Test Commands

```bash
# Clear caches
php artisan route:clear
php artisan config:clear
php artisan view:clear

# Check routes
php artisan route:list

# Check permissions
php artisan tinker
>>> $user = App\Models\User::find(1);
>>> $user->hasRole('admin');
>>> $user->can('manage-roles');
```

## 📞 Troubleshooting

### Common Issues:
1. **Routes not found**: Clear route cache
2. **Permissions not working**: Check if roles are assigned
3. **Views not loading**: Clear view cache
4. **Database issues**: Run migrations and seeders

### Debug Commands:
```bash
# Check if Spatie is working
php artisan tinker
>>> use Spatie\Permission\Models\Role;
>>> Role::all();

# Check user roles
>>> $user = App\Models\User::first();
>>> $user->roles;
>>> $user->permissions;
```

This testing guide covers all the major features and should help you thoroughly test the role-based access control system!

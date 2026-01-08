# 🚀 Laravel Cloud Deployment Guide for Shopera API

## Overview

This guide walks you through deploying Shopera API to Laravel Cloud with MongoDB Atlas as the database backend.

## ⚠️ Important Notes

- **Laravel Cloud doesn't natively support MongoDB**, so we use **MongoDB Atlas** (free tier available)
- **GitHub repository**: https://github.com/achrafhilaly/Shopera-API-V0.1.git
- **Main branch**: `main`
- **PHP Version**: 8.3
- **Laravel Version**: 12

## 📋 Prerequisites Checklist

- [ ] Laravel Cloud account created
- [ ] Project connected to GitHub (`main` branch)
- [ ] MongoDB Atlas account (free tier is sufficient)
- [ ] AWS S3 bucket for file storage (optional but recommended)

---

## Step 1: Set Up MongoDB Atlas (5 minutes)

### 1.1 Create MongoDB Atlas Account

1. Go to [MongoDB Atlas](https://www.mongodb.com/cloud/atlas/register)
2. Sign up for a free account
3. Create a new M0 FREE cluster (512MB storage, free forever)

### 1.2 Configure Database Access

1. In Atlas Dashboard, go to **Database Access**
2. Click **Add New Database User**
3. Create username/password credentials (save these!)
4. Grant **Atlas Admin** or **Read/Write** to any database

### 1.3 Configure Network Access

1. Go to **Network Access**
2. Click **Add IP Address**
3. Select **Allow Access from Anywhere** (0.0.0.0/0)
   - This is required for Laravel Cloud servers to connect
4. Confirm

### 1.4 Get Connection String

1. Click **Connect** on your cluster
2. Choose **Drivers** → **PHP**
3. Copy the connection string (looks like this):
   ```
   mongodb+srv://username:password@cluster0.xxxxx.mongodb.net/?retryWrites=true&w=majority
   ```
4. Replace `<password>` with your actual database password
5. Save this connection string - you'll need it soon!

---

## Step 2: Configure Laravel Cloud Environment Variables

In your Laravel Cloud dashboard, go to your project → **Environment** → **Environment Variables**

Add the following variables:

### Core Application Settings

```bash
APP_NAME="Shopera API"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-project.laravelcloud.app
APP_TIMEZONE=UTC
```

### Database Configuration (MongoDB Atlas)

```bash
DB_CONNECTION=mongodb
MONGODB_URI=mongodb+srv://your-username:your-password@cluster0.xxxxx.mongodb.net/?retryWrites=true&w=majority
MONGODB_DATABASE=shopera_production
```

**Important**: Replace with your actual MongoDB Atlas connection string!

### Authentication & Sanctum

```bash
SANCTUM_STATEFUL_DOMAINS=your-project.laravelcloud.app
SESSION_DOMAIN=.laravelcloud.app
SESSION_SECURE_COOKIE=true
```

### Cache & Session Configuration

```bash
CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database
```

### AWS S3 Configuration (for file uploads)

```bash
AWS_ACCESS_KEY_ID=your-access-key-id
AWS_SECRET_ACCESS_KEY=your-secret-access-key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=shopera-production
AWS_USE_PATH_STYLE_ENDPOINT=false
FILESYSTEM_DISK=s3
```

**Note**: If you don't have S3 yet, you can temporarily use:
```bash
FILESYSTEM_DISK=local
```

### Mail Configuration

```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@shopera.com"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## Step 3: Create Admin User (Important!)

After your first deployment, you need to create an admin user to access the API.

### Option 1: Using Laravel Cloud Terminal

1. Go to your Laravel Cloud project
2. Open **Terminal**
3. Run this command:

```bash
php artisan tinker
```

Then in Tinker:

```php
$user = new App\Models\User();
$user->name = 'Admin User';
$user->email = 'admin@shopera.com';
$user->password = bcrypt('your-secure-password');
$user->role = 'admin';
$user->email_verified_at = now();
$user->save();
```

Press `Ctrl+D` to exit Tinker.

### Option 2: Using Database Seeder (if configured)

If you want to seed test data, you can add this to your deploy commands in `cloud.yaml`:

```yaml
deploy:
  - 'composer install --no-dev --optimize-autoloader'
  - 'php artisan db:seed --force --class=DatabaseSeeder'  # Add this line
  - 'php artisan config:cache'
  - 'php artisan route:cache'
  - 'php artisan view:cache'
```

**Warning**: Only do this on first deployment, then remove this line!

---

## Step 4: Deploy Your Application

### 4.1 Commit and Push Configuration Files

The `cloud.yaml` file has been created for you. Now commit and push:

```bash
git add cloud.yaml .laravelcloud DEPLOYMENT.md
git commit -m "Add Laravel Cloud deployment configuration"
git push origin main
```

### 4.2 Trigger Deployment in Laravel Cloud

1. Go to your Laravel Cloud dashboard
2. Select your project
3. Go to **Deployments** tab
4. Click **Deploy Now**
5. Watch the deployment logs for any errors

### 4.3 Verify Deployment

Once deployment is complete, your API will be available at:
```
https://your-project.laravelcloud.app/api
```

---

## Step 5: Test Your Deployment

### Test the Health Check

```bash
curl https://your-project.laravelcloud.app/up
```

Should return a successful response.

### Test API Documentation

Visit in your browser:
```
https://your-project.laravelcloud.app/docs/api
```

### Test Login Endpoint

```bash
curl -X POST https://your-project.laravelcloud.app/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "admin@shopera.com",
    "password": "your-secure-password"
  }'
```

Should return a token if successful!

---

## 🎯 Quick Reference: Required Environment Variables

Here's a minimal set of environment variables to get started:

```bash
# Core
APP_NAME="Shopera API"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-project.laravelcloud.app

# Database (MongoDB Atlas)
DB_CONNECTION=mongodb
MONGODB_URI=mongodb+srv://username:password@cluster0.xxxxx.mongodb.net/?retryWrites=true&w=majority
MONGODB_DATABASE=shopera_production

# Sanctum
SANCTUM_STATEFUL_DOMAINS=your-project.laravelcloud.app

# Storage
FILESYSTEM_DISK=local

# Cache & Sessions
CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database
```

---

## 🔧 Troubleshooting

### Issue: "MongoDB extension not found"

**Solution**: The `cloud.yaml` file specifies the MongoDB extension. If this error persists, contact Laravel Cloud support to ensure the extension is available.

### Issue: "Connection timeout to MongoDB"

**Solution**: 
1. Verify your MongoDB Atlas connection string is correct
2. Ensure Network Access in Atlas allows `0.0.0.0/0`
3. Check if the database user has proper permissions

### Issue: "Class 'MongoDB\Laravel\MongoDBServiceProvider' not found"

**Solution**: This usually happens if composer dependencies weren't installed properly. Redeploy your application.

### Issue: "CORS errors when calling API from frontend"

**Solution**: Update your CORS configuration and add your frontend domain to `SANCTUM_STATEFUL_DOMAINS`:

```bash
SANCTUM_STATEFUL_DOMAINS=your-project.laravelcloud.app,your-frontend.com
```

---

## 📊 Post-Deployment Monitoring

### Check Application Logs

1. Go to Laravel Cloud dashboard
2. Select your project
3. Go to **Logs** tab
4. Monitor for errors

### Monitor Database Usage

1. Go to MongoDB Atlas dashboard
2. Check **Metrics** tab
3. Monitor connections, operations, and storage

---

## 🔄 Continuous Deployment

Laravel Cloud automatically deploys when you push to the `main` branch:

```bash
# Make changes to your code
git add .
git commit -m "Your changes"
git push origin main

# Laravel Cloud will automatically deploy!
```

---

## 🎉 Success Checklist

After deployment, verify these:

- [ ] Health check endpoint responds: `/up`
- [ ] API documentation accessible: `/docs/api`
- [ ] Can login with admin credentials: `/api/login`
- [ ] Can access authenticated endpoints with token
- [ ] MongoDB Atlas shows active connections
- [ ] No errors in Laravel Cloud logs
- [ ] All 36 endpoints working (run test script locally pointing to production URL)

---

## 🆘 Need Help?

1. **Laravel Cloud Issues**: Check [Laravel Cloud Documentation](https://cloud.laravel.com/docs)
2. **MongoDB Atlas Issues**: Check [MongoDB Atlas Documentation](https://docs.atlas.mongodb.com/)
3. **API Issues**: Review logs in Laravel Cloud dashboard
4. **Repository**: https://github.com/achrafhilaly/Shopera-API-V0.1.git

---

## 📝 Notes

- The free tier of MongoDB Atlas (M0) provides 512MB storage - sufficient for development/testing
- Laravel Cloud automatically manages SSL certificates
- Queue workers are configured to run automatically (`daemon` in cloud.yaml)
- The scheduler is enabled for any scheduled tasks

---

**Last Updated**: January 2026  
**Laravel Version**: 12  
**PHP Version**: 8.3  
**MongoDB Driver**: 5.5


# 🎯 Quick Start Guide - PPDB YAPI Production Deployment

## 📋 Pre-Requirements Checklist

### 1. VPS Requirements (BiznetGio)
- [ ] Ubuntu 20.04/22.04 LTS
- [ ] Minimum 4GB RAM, 2 vCPU
- [ ] 50GB+ storage space
- [ ] Root/sudo access

### 2. Domain Setup (Hostinger)
- [ ] Domain `yapi-alazhar.id` purchased
- [ ] DNS access in Hostinger panel
- [ ] Email account setup (optional)

### 3. Required Information ✅
- [x] VPS IP Address: `103.129.149.246`
- [x] SSH username: `ppdb26`
- [ ] Xendit Live API Key: `[AKAN DIBERIKAN KEMUDIAN]`
- [x] WhatsApp API Key: `SGIKDRJTT0MDQRVX`
- [x] WhatsApp Number Key: `f49e10YYE2Gee1hb` (from existing config)

## 🚀 Option 1: Automated Deployment (Recommended)

### Step 1: Connect to VPS
```bash
ssh ppdb26@103.129.149.246
```

### Step 2: Download and Run Script
```bash
# Download deployment script
wget -O deploy-to-vps.sh https://raw.githubusercontent.com/Adi-Sumardi/pmb/master/deploy-to-vps.sh

# Make executable
chmod +x deploy-to-vps.sh

# Run deployment
./deploy-to-vps.sh
```

### Step 3: Configure DNS (Hostinger)
Login to Hostinger → Manage Domain → DNS Records:
```
Type    Name    Value               TTL
A       @       103.129.149.246     3600
A       www     103.129.149.246     3600
```

### Step 4: Update Environment Variables
```bash
# Edit environment file
nano /var/www/ppdb-yapi/.env

# Update these values:
MAIL_PASSWORD=your_email_password
XENDIT_SECRET_KEY=[AKAN_DIBERIKAN_KEMUDIAN]
XENDIT_WEBHOOK_TOKEN=your_webhook_token
WHATSAPP_API_KEY=SGIKDRJTT0MDQRVX
WHATSAPP_NUMBER_KEY=f49e10YYE2Gee1hb
```

### Step 5: Test Application
Visit: `https://yapi-alazhar.id`

---

## 🛠️ Option 2: Manual Deployment

If you prefer manual setup, follow the complete guide in `docs/DEPLOYMENT_GUIDE.md`

## 🔧 Post-Deployment Tasks

### 1. Test Core Features
- [ ] User registration works
- [ ] Payment gateway (small test payment)
- [ ] WhatsApp notifications
- [ ] File uploads
- [ ] Admin panel access

### 2. Setup Monitoring
```bash
# Check application health
curl https://yapi-alazhar.id/health/check

# Monitor logs
tail -f /var/www/ppdb-yapi/storage/logs/laravel.log

# Check services
sudo systemctl status nginx postgresql redis-server php8.1-fpm
```

### 3. Configure Backups
Database backups are automatically scheduled at 2 AM daily.

Manual backup:
```bash
/home/deploy/backup-db.sh
```

## 🆘 Troubleshooting

### Application 500 Error
```bash
# Check application logs
tail -f /var/www/ppdb-yapi/storage/logs/laravel.log

# Clear cache
cd /var/www/ppdb-yapi
php artisan config:clear
php artisan cache:clear
```

### SSL Certificate Issues
```bash
# Check certificate
sudo certbot certificates

# Renew if needed
sudo certbot renew
```

### Database Connection Issues
```bash
# Test database
sudo -u postgres psql -d ppdb_yapi_production -U ppdb_user

# Check PostgreSQL service
sudo systemctl status postgresql
```

## 📞 Support

### Documentation
- Full deployment guide: `docs/DEPLOYMENT_GUIDE.md`
- Application features: [README.md](../README.md)

### Common Commands
```bash
# Deploy updates
/var/www/ppdb-yapi/deploy.sh

# Check worker status
sudo supervisorctl status

# Restart services
sudo systemctl restart nginx php8.1-fpm
sudo supervisorctl restart ppdb-yapi-worker:*

# View logs
tail -f /var/log/nginx/yapi-alazhar.id.access.log
tail -f /var/www/ppdb-yapi/storage/logs/laravel.log
```

## 🎯 Ready-to-Use Configuration

**Your VPS Details (BiznetGio):**
- **IP Address:** `103.129.149.246`
- **SSH Access:** `ssh ppdb26@103.129.149.246`
- **Domain:** `yapi-alazhar.id`

**API Credentials (Ready):**
- **WhatsApp API Key:** `SGIKDRJTT0MDQRVX`
- **WhatsApp Number Key:** `f49e10YYE2Gee1hb`
- **Xendit Live API:** `[Akan diberikan kemudian]`

**DNS Configuration (Set in Hostinger):**
```
A    @      103.129.149.246
A    www    103.129.149.246
```

## ✅ Success Checklist

- [ ] Website accessible at `https://yapi-alazhar.id`
- [ ] SSL certificate installed (green lock icon)
- [ ] User registration flow works
- [ ] Payment gateway integrated (after Xendit key)
- [ ] Admin panel accessible
- [ ] WhatsApp notifications working
- [ ] All services running properly
- [ ] Backups configured
- [ ] Monitoring active

---

**🎉 Congratulations! Your PPDB YAPI application is now live in production!**

For any issues or questions, refer to the complete deployment guide or check the application logs.

# 🚀 PPDB YAPI - Ready to Deploy!

## 📊 VPS Information (BiznetGio)
- **IP Address:** `103.129.149.246`
- **SSH Username:** `ppdb26`
- **Domain:** `yapi-alazhar.id`
- **OS:** Ubuntu 20.04/22.04 LTS

## 🔑 API Credentials Status
- ✅ **WhatsApp API Key:** `SGIKDRJTT0MDQRVX`
- ✅ **WhatsApp Number Key:** `f49e10YYE2Gee1hb`
- ⏳ **Xendit Live API Key:** `[Akan diberikan kemudian]`

---

## 🎯 Deployment Steps

### 1. Connect to VPS
```bash
ssh ppdb26@103.129.149.246
```

### 2. Download & Run Deployment Script
```bash
# Download deployment script
wget -O deploy-to-vps.sh https://raw.githubusercontent.com/Adi-Sumardi/pmb/master/deploy-to-vps.sh

# Make executable and run
chmod +x deploy-to-vps.sh
./deploy-to-vps.sh
```

### 3. Configure DNS at Hostinger
Login ke **Hostinger Control Panel** → Domain Management → DNS:

```
Record Type    Name    Value               TTL
A              @       103.129.149.246     3600
A              www     103.129.149.246     3600
```

### 4. Update Environment Variables (Optional)
Jika diperlukan, edit file environment:
```bash
nano /var/www/ppdb-yapi/.env

# Update jika perlu:
MAIL_PASSWORD=your_email_password
```

### 5. Test Website
Buka browser dan akses: `https://yapi-alazhar.id`

---

## ✅ What's Already Configured

### ✅ Server Stack
- **Web Server:** Nginx dengan SSL auto-install
- **Database:** PostgreSQL dengan user khusus
- **Cache:** Redis untuk performance
- **PHP:** 8.1 dengan semua ekstensi

### ✅ Application Features
- **Payment Gateway:** Siap untuk Xendit (tunggu API key)
- **WhatsApp Notifications:** Sudah dikonfigurasi dengan API key
- **File Upload:** Sudah optimal dengan Laravel storage
- **Security:** Multi-layer protection aktif

### ✅ Production Optimization
- **Queue Workers:** Auto-start dengan Supervisor
- **Cron Jobs:** Laravel scheduler aktif
- **Backups:** Database backup otomatis (daily)
- **SSL Certificate:** Auto-install dengan Let's Encrypt
- **Firewall:** UFW dengan rules optimized

---

## 📱 Post-Deployment Checklist

### Immediate Testing
- [ ] Website loading: `https://yapi-alazhar.id`
- [ ] SSL certificate active (green lock)
- [ ] User registration form working
- [ ] Admin panel accessible
- [ ] WhatsApp notifications working

### Payment Gateway Testing (Xendit Ready!)
- [x] ✅ Secret Key obtained: `xnd_production_izBEiajnupjv22yr8Kf0lZlXMcO0XJvM5LxLadP5qTew7whLqYVBFGph1lsSZn`
- [ ] Test payment flow with small amount (Rp 10,000)
- [ ] Verify webhook receiving payments
- [ ] Test Virtual Account (BCA, BNI, BRI, Mandiri)
- [ ] Test E-wallet (OVO, DANA, LinkAja, ShopeePay)

### Performance Monitoring  
- [ ] Check server resources: `htop`
- [ ] Monitor logs: `tail -f /var/www/ppdb-yapi/storage/logs/laravel.log`
- [ ] Check queue workers: `sudo supervisorctl status`

---

## 🛠️ Useful Commands

### Application Management
```bash
# Deploy updates
/var/www/ppdb-yapi/deploy.sh

# Clear application cache
cd /var/www/ppdb-yapi
php artisan config:clear
php artisan cache:clear
```

### Service Management
```bash
# Restart services
sudo systemctl restart nginx php8.1-fpm
sudo supervisorctl restart ppdb-yapi-worker:*

# Check service status
sudo systemctl status nginx postgresql redis-server
```

### Monitoring
```bash
# Application logs
tail -f /var/www/ppdb-yapi/storage/logs/laravel.log

# Nginx access logs
sudo tail -f /var/log/nginx/yapi-alazhar.id.access.log

# System resources
htop
df -h  # Disk usage
free -h  # Memory usage
```

### Database Operations
```bash
# Connect to database
sudo -u postgres psql -d ppdb_yapi_production -U ppdb_user

# Manual backup
/home/ppdb26/backup-db.sh

# Check backups
ls -la /home/ppdb26/backups/
```

---

## 🚨 Troubleshooting Quick Fixes

### Website 500 Error
```bash
# Check application logs
tail -f /var/www/ppdb-yapi/storage/logs/laravel.log

# Clear all caches
cd /var/www/ppdb-yapi
php artisan config:clear && php artisan cache:clear && php artisan view:clear
```

### SSL Certificate Issues
```bash
# Check certificate status
sudo certbot certificates

# Force renewal
sudo certbot renew --force-renewal
```

### Database Connection Error
```bash
# Check PostgreSQL service
sudo systemctl status postgresql

# Test connection
sudo -u postgres psql -c "SELECT version();"
```

---

## 📞 Support Information

### Documentation
- **Full Guide:** `/var/www/ppdb-yapi/docs/DEPLOYMENT_GUIDE.md`
- **Quick Deploy:** `/var/www/ppdb-yapi/QUICK_DEPLOY.md`

### Emergency Contacts
- **Server Status:** `ssh ppdb26@103.129.149.246`
- **Application Logs:** `tail -f /var/www/ppdb-yapi/storage/logs/laravel.log`

---

## 🎉 Ready to Go!

Dengan informasi VPS dan API credentials yang sudah ada, deployment tinggal menjalankan script dan mengatur DNS. 

**Aplikasi PPDB YAPI akan live dalam waktu 15-30 menit!** 🚀

### Next Steps After Deployment:
1. ✅ Test semua fitur aplikasi
2. ⏳ Tunggu Xendit API key untuk aktivasi payment
3. 🎯 Go live untuk penerimaan siswa baru!

**Good luck dengan deployment! 🎓✨**

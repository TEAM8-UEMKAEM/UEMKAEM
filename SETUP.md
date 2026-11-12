# Cara Pasang (Windows + XAMPP)

1. Nyalakan Apache & MySQL di XAMPP, buat database `uemkaem` lewat http://localhost/phpmyadmin
2. Buat proyek Laravel baru:
   composer create-project laravel/laravel uemkaem
3. Salin isi folder ini ke dalam folder `uemkaem` (timpa file yang sama, mis. routes/web.php & DatabaseSeeder.php)
4. Edit `.env`:
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=uemkaem
   DB_USERNAME=root
   DB_PASSWORD=
5. Jalankan:
   php artisan migrate --seed
   php artisan serve
6. Buka http://127.0.0.1:8000

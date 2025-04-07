ssh ci20642@92.53.96.244

git pull origin main

cd wisetao-admin/
/home/c/ci20642/wisetao-admin/public_html

ln -s ~/wisetao-admin/public ~/wisetao-admin/public_html

find ~/wisetao-admin -type d -exec chmod 755 {} \;
find ~/wisetao-admin -type f -exec chmod 644 {} \;
chmod -R 775 ~/wisetao-admin/storage

mkdir -p ~/wisetao-admin/storage/framework/sessions
chmod -R 775 ~/wisetao-admin/storage/framework/sessions


## Комманды
php artisan storage:link
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

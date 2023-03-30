# Installation Process

### Clone
```
git clone https://github.com/hiteshkr1995/netwin-test-app.git
```

### Change directory
```
cd netwin-test-app
```

### Create .env
```
cp .env.example .env
```

### Install vedor
```
composer install
```

### Generate app key
```
php artisan key:generate
```

### Create database & set credetial in .env
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=netwin_test_app
DB_USERNAME=root
DB_PASSWORD=
```

### Run migration
```
php artisan migrate
```

### Run seeder for doctor data
```
php artisan db:seed
```

### Run project on your local
```
php artisan serve
```
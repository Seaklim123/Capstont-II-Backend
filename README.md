for run Laravel we need to: 
- run composer install
-php artisan key:generate
- cp .env.example .env
past code below in to you .env file
	+ it for you wnat to use postreSQL
	```
	DB_CONNECTION=pgsql
	DB_HOST=127.0.0.1
	DB_PORT=5432
	DB_DATABASE=tosorder # dak tam ng mk lok ery
	DB_USERNAME=postgres # dak ey dak tv hot nas 
	DB_PASSWORD=1111 #change it to fit you 
	```
	+ it for you want to use MySQL 
	```
	DB_CONNECTION=myqsl # ah ng bach kea ey te dak tv veatrove hx love 
	DB_HOST=127.0.0.1
	DB_PORT=3306
	DB_DATABASE=tosorder
	DB_USERNAME=root
	DB_PASSWORD=	  
	```
 
   # Migration with database 
```
git pull
php artisan migrate 
```


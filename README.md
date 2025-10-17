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
  # Setup project in the Controller 
  # Setup project in the Request Rule
  # Setup project in the Response message request 

  #  Manage on the Table Number
1. Create a new table number 
2. Update table number data if incorrect
3. List all table number 
4. Delete table number
5. Before run the application please make source
 ```git pull
    php artisan migrate:refresh
    php artisan route:clear
    php artisan route:cache
    php artisan serve
```
# Project Structure
1. DTO: Data Transfer Object 
2. Exceptions to handle exception when request invalided
3. Mapper convert data from dto to models
4. Repository use to query data from database
5. Service to handle business logic 
6. Controller to implement service 
7. Request rule to request data 
8. Resource rule to response data to request 
9. Providers 


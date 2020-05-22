## GTFS Manager

This API manages routes for public transport agencies. Saving your data in a relational database, converts the information to the GTFS specification, which can be used to search for information from traffic agencies and to monitor in real time any specific itinerary. 

Implemented for a graduate work in the Computer Science course.

**It is necessary to have configured the SMTP server credentials to notify the user via e-mail with the generated password.**

 - For testing, I used [Mailtrap.io](https://mailtrap.io/)

## Installation

When cloning the repository, you must edit the **.env** file with the database credentials.

Copy the **.env.example** file to the project's root folder with the name **.env**

```
DB_CONNECTION=mysql
DB_HOST=your_db_host
DB_PORT=3306
DB_DATABASE=gtfs_manager
DB_USERNAME=your_db_user
DB_PASSWORD=your_password
```

After setting, run the commands:

```
composer install
php artisan migrate --seed
php artisan key:generate
php artisan jwt:secret
```

This will cause the database to be populated with the necessary information for operation.

## SMTP Configuration

It is necessary to update the .env file with the SMTP server information for the system to notify the user with their passwords.

- For testing, I used [Mailtrap.io](https://mailtrap.io/)

```
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no_reply@gtfsmanager.com
MAIL_FROM_NAME="${APP_NAME}" 
```

## Documentation

All documentation was made using the Swagger Editor library, being made available on the system's root route.

After starting the system with the command:
```
php artisan serve
```
Access in your browser the address informed in the answer to the command above.

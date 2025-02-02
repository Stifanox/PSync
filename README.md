# PSync

PSync - Pomodoro synchronizer for a team. Application helps to manage team focus across all team members.

## Table of contents
* [Installation](#instalation)
* [ERD Diagram](#erd-diagram)
* [User logins](#user-logins)
* [Views](#views)


## Instalation
To run project go to provisioning folder and run 
```
docker compose up -d
```

After that enter container named 'php-psync' using command:
```
docker exec -it {container_id} sh 
```
Later create .env in main folder of project file and copy all values from .env.example than run following commands:
```
composer install
php artisan key:generate
php artisan migrate:fresh --seed
npm i
npm run build
```

## ERD diagram
![Admin Panel](readme_images/ERD.png)

## User logins
**Accounts for testing:**
- **Admin Account:**
- **Email:** admin@psync.pl
- **Password:** password
- **Client Accounts:**

Because users are generated via factories and using faker you need to access database to check what emails are in database.

To do that in terminal execute 
```
docker exec -it {postgres_container_id} sh
```
and inside container run 
```
psql -U psync-user -d psync-db
select * from users;
```
Remember to include semicolon when executing query. After that you will be able to list all user emails. All accounts have password: password
## Views
**Login:**
![Login](readme_images/login.png)

**Dashboard:**
![img.png](readme_images/Dashboard.png)

**User profile:**
![img.png](readme_images/user_profile.png)

**Modals:**
![img.png](readme_images/modal.png)

**Admin view to manage users:**
![img.png](readme_images/img.png)

**Clock view:**
![img_1.png](readme_images/img_1.png)

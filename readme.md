
# Laravel PHP Framework

[![Build Status](https://travis-ci.org/laravel/framework.svg)](https://travis-ci.org/laravel/framework)
[![Total Downloads](https://poser.pugx.org/laravel/framework/d/total.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/framework/v/unstable.svg)](https://packagist.org/packages/laravel/framework)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as authentication, routing, sessions, queueing, and caching.

Laravel is accessible, yet powerful, providing tools needed for large, robust applications. A superb inversion of control container, expressive migration system, and tightly integrated unit testing support give you the tools you need to build any application with which you are tasked.

## Official Documentation

Documentation for the framework can be found on the [Laravel website](http://laravel.com/docs).
## Deployment

**NOTE :- Since nginx expects all files & folders to be owned by www-data user, run this command to make sure all files are owned by www-data :-**

    chown -R www-data:www-data /var/www/myapp

### Deploying the latest changes

1. Run **deploy.sh** with

    sudo ./deploy.sh
    
   #### What this will do
	a. Pull the code
	b. Change the owner & group of all files/folders to www-data
	c. Run php artisan deploy
	d. Recreate the app & supervisor containers.

### Manual Debugging

 **Note :- Make sure you're in the same directory where docker-compose.yaml lies**

To spawn a shell inside container, Replace app with supervisor, nginx if needed
	 

    docker-compose exec app /bin/bash 

To restart any container without building it

    docker-compose up -d --force-recreate --no-deps  <container-name>
    
    // Replace with the actual container name. For eg. app, supervisor, nginx
    // You can chain it as well in the single command 
    
    docker-compose up -d --force-recreate --no-deps  app supervisor nginx
    
To Build as well as recreate the container, Run

    docker-compose up -d --force-recreate --no-deps --build app <container-name>
    // Replace with the actual container name. For eg. app, supervisor, nginx
    // You can chain it as well in the single command 
    
    docker-compose up -d --force-recreate --no-deps --build app supervisor
To View the logs of any container

    docker-compose logs <container-name>
    // Replace with the actual container name. For eg. app, supervisor, nginx

### Things to Remember

1. Host FS is mounted directly inside the container, so whatever changes you make will be reflected in the container as well.
2. Please go through docker-compose.yaml, in order to check what services are available.
3. Dockerfile is the dockerfile for application
4. Dockerfile.supervisor is the dockerfile for supervisor
5. Supervisor Web UI runs on "IP of Instance:9001" . Credentials for logging in are Username : admin, Password: admin
6. Certbot Certificate Renewals + Scheduler cronjobs are already set with root user
You can check that using crontab -l as a root user
7. **IMP :- If you change the directory of the application to some other directory, 
For Eg. From /var/www/myapp to /var/www/test. Then you need to recreate the certificates :- For that you need to replace the nginx config volume mount from nginx/ to initial-nginx directory under docker-compose.yaml and run certbot using :-**
 `docker-compose up -d --force-recreate --no-deps certbot`

 **V. IMP. :- Don't try to run certbot using docker-compose again & again, as everytime it will try to get the new certificates & there's a weekly limit of 5 Certificate Requests on LetsEncrypt. Make sure you don't run
 docker-compose up -d**
 8. If you want install local Redis, Uncomment redis block in docker-compose.yaml and replace the URI under .env
 and start the redis container using 
  `docker-compose up -d --force-recreate --no-deps redis`



  
## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](http://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).



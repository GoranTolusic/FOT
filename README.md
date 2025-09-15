# Symfony-Starter

Before everything set your environment variables!
1. Make .env file and set variables (look for env.example)
2. Set environment variables in docker-compose.yml file if you want to run whole process out-of-the-box (mysql and nginx are included as docker containers as well)


# DOCKER RUNNING (Recommended)

Requirements
- Make sure you have installed and running docker on your system

Steps
1. Run "sudo bash run.sh". This will build and run container on port 8080. This command out-of-the-box installs whole application and set mysql, nginx, application and test containers.
You can run command with additional as "sudo bash run.sh include-tests" to start tests. If any of the test failes containers are stoping and rollbacking. Variables for test env is located in .env.test


# LOCAL RUNNING

Requirements
- Make sure you have installed appropriate php version on your system and some of required extensions as well
- Running MySql service
- Installed composer on your system

Steps
1. Run "composer install" in your terminal
2. Run "composer create-db" in your terminal. This will create database with name coresponding with the DB_NAME from your .env file. If db with same name already exists, it will skipp creation. 
3. Run "composer migrate" in your terminal. This will migrate all migration files which are not registered in symfony's migration table.
4. Run "composer start" to start a server process. This command will trigger php-built-in server process on 8000 port.





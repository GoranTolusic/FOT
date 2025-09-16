# Symfony-Starter

Before everything set your environment variables!
1. Make .env file and set variables (look for env.example)
2. Set environment variables in docker-compose.yml file if you want to run whole process out-of-the-box (nginx is included as docker containers as well)


# DOCKER RUNNING (Recommended)

Requirements
- Make sure you have installed and running docker on your system

Steps
1. Run "sudo bash run.sh". This will build and run application container on port 8080. This command out-of-the-box installs whole application and set nginx and application containers.


# LOCAL RUNNING

Requirements
- Make sure you have installed appropriate php version on your system and some of required extensions as well
- Installed composer on your system

Steps
1. Run "composer install" in your terminal
4. Run "composer start" to start a server process. This command will trigger php-built-in server process on 8000 port.





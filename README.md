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


# USAGE
- Web page covers everything from requirements (login page, profile page, logout button, list of authors and single authors page, conditional deleting authors and books...)
- You will be redirected to login page if you are trying to access to any page without token
- Login page already contain populated credentials from existing users from API

# COMMAND FOR CREATING AUTHORS
- You can run command in your terminal to create new author. You need to provide arguments in specific order. Each of arguments from example are required
- SAMPLE command: composer add-author ahsoka.tano@royal-apps.io Kryze4President Marko Markovic male 1990-01-01 Osijek "Hello"
- First two arguments are user email and password to enable retrieving token required for authentication
- Order of arguments {email} {password} {first_name} {last_name}, {gender}, {birthday}, {place_of_birth}, {biography}






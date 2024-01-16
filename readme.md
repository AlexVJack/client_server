# Client-Server Application Template

## Overview

This template provides a basic structure for a client-server application. It includes a Docker configuration for the client and server applications, as well as a `Makefile` with commands to build and run the applications.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

The Docker needs to be installed on your machine. 
Docker Compose version v2.5.1 is recommended (available with docker-compose command)
The project is tested on Ubuntu 22.04

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/AlexVJack/client_server.git
   ```
2. Navigate to the project directory:
   ```bash
   cd client_server
   ```
3. Use the `make build` command to build the Docker images for the project:
   ```bash
   make build
   ```
4. Use the `make up` command to start the Docker containers for the project:
   ```bash
    make up
    ```
5. Use the `make bash_server` command to access the server container:
   ```bash
    make bash_server
    ```
6. Run the composer install and migrations on the server:
   ```bash
    composer install
    php bin/console doctrine:migrations:migrate
    exit
    ```
7. Use the `make bash_client` command to access the client container:
   ```bash
    make bash_client
    ```
8. Run the composer install on the client:
9. ```bash
    composer install
    exit
    ```

## Usage

1. Use the `make bash_client` command to access the client container:
   ```bash
    make bash_client
    ```
2. Use one of the available commands to interact with the application:
   ```bash
    php bin/console GetUsers
    php bin/console MakeUser 
    ```

### List of the available commands

- GetUsers - returns a list of all users
- GetUserById - returns a user by id
- CreateUser - creates a new user
- UpdateUser - updates a user
- DeleteUser - deletes a user

- GetGroups - returns a list of all groups
- GetGroupById - returns a group by id
- CreateGroup - creates a new group
- UpdateGroup - updates a group
- DeleteGroup - deletes a group

- AddUserToGroup - adds a user to a group
- GetUserGroupReport - returns a report of all users in a groups

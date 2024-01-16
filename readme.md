# Client-Server Application Template

## Overview

This template provides a basic structure for a client-server application. It includes a Docker configuration for the client and server applications, as well as a `Makefile` with commands to build and run the applications.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

The Docker CLI needs to be installed on your machine. You can download the Docker Desktop application for your operating system from the [Docker website](https://www.docker.com/products/docker-desktop).
Docker-compose is also required. You can install it from [here](https://docs.docker.com/compose/install/).

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/AlexVJack/client_server.git [repository_url]
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
6. Run the migrations:
   ```bash
    php bin/console doctrine:migrations:migrate
    ```

## Usage

1. Use the `make bash_client` command to access the client container:
   ```bash
    make bash_client
    ```

### Building the Project

The `make build` command is used to build the Docker images for the project. This command needs to be run initially and whenever you make changes to the Docker configuration.

```bash
make build
```

#### Description

This command will build Docker images based on the Dockerfiles for the client and server applications. It ensures that all the necessary dependencies and configurations are set up correctly in the Docker environment.

### Starting the Project

The `make up` command is used to start the Docker containers for the project.

```bash
make up
```

#### Description

This command starts the Docker containers defined in your `docker-compose.yml` file. It includes the containers for the client and server applications and any other services such as databases or reverse proxies. Once the containers are up and running, your applications will be accessible as per the configuration.

## Additional Commands

You can add descriptions for other `make` commands or any other commands relevant to your project here.

---

### Notes for Customization:

- Replace `[repository_url]` and `[project_directory]` with the actual URL of your Git repository and the name of the project directory.
- Add any additional instructions specific to your project, such as how to access the applications, any default login credentials, etc.
- You might want to include information on how to run tests, how to contribute to the project, and any coding standards or guidelines you follow.

This template provides a basic structure for your README. Feel free to expand it to include more specific details about your project.

# MINI-MVC

## Description

This project is a simple pure MVC framework inspired by Laravel.

## Installation

To install the project, follow the steps below:

1. Install dependencies by running the following command:

    ```shell
    composer install
    ```

2. Copy `.env.example` to `.env`.

3. Create a `mini-mvc` database in your MySQL.

4. Change the `DB_USER` and `DB_PASSWORD` variables in the `.env` file to your MySQL username and password.

5. Run the following command to run migrations:

    ```shell
    php migration migrate
    ```

6. Run the following command for seeding (optional):

    ```shell
    php seed
    ```

## Usage

1. To use the project, you can run it with the following command:

    ```shell
    php -S localhost:8000 -t public
    ```

   Here, the port is set to 8000, but you can change it to any other port.

2. To run tests, use the following command:

    ```shell
    ./vendor/bin/phpunit -c phpunit.xml
    ```

   Before running tests, make sure you have a database named `mini-mvc-test` in your MySQL. Also, if needed, modify the username and password in the `.env.testing` file.

## Structure
```
mini-mvc
├───app
│   ├───Helpers
│   ├───Http
│   │   └───Middlewares
│   ├───Models
│   └───Validators
├───bootstrap
├───core
│   ├───Contracts
│   │   ├───DB
│   │   ├───Http
│   │   └───Validator
│   ├───DB
│   │   └───Schema
│   ├───Exceptions
│   ├───Http
│   └───Validator
│       └───Rules
├───database
│   └───migrations
├───public
├───storage
│   ├───cache
│   └───logs
├───tests
│   ├───Feature
│   │   ├───Core
│   │   └───Models
│   ├───Traits
│   └───Unit
│       ├───Core
│       │   ├───Exceptions
│       │   ├───Http
│       │   └───Validator
│       │       └───Rules
│       ├───Models
│       └───Validators
└───views
    ├───auth
    ├───layouts
    └───profile
```

## Contributing

If you would like to contribute to this project, please follow the guidelines below for bug reports, feature requests, or pull requests.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details. 

## Acknowledgments

I would like to acknowledge the following sources and references used in this project:

- [CodeShack](https://codeshack.io/lightweight-template-engine-php/) for providing a lightweight template engine in PHP.
- [LaraCasts](https://laracasts.com/) for tutorials and examples.

## Contact

For any questions or inquiries, please feel free to reach out to me via email at [Mehdi Daalvand](mailto:mdaalvand@gmail.com?subject=[GitHub]%20MINI-MVC).

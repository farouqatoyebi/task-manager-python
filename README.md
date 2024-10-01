# task-manager-python
In order to use this application, ensure that the following tools are available on your local machine;

- Xampp/Wampp or any local server of your choixe that serves PHP and MySQL
- Python
- Composer

You can download Xampp online on your local machine from <a href="https://www.apachefriends.org/download.html">here</a>

You can install python via your terminal or download it <a href="https://www.python.org/downloads/">here</a>

You can install composer via your terminal or download it <a href="https://getcomposer.org/download/">here</a>

<h4>Python</h4>
To get started, make sure you have Flask installed. You can install it via pip3

```sh
pip3 install Flask 
```

To run your Flask application, navigate into the python-service folder and execute the following command:

```sh
python3 app.py
```

or you can execute the following command from the root folder

```sh
python3 python-service/app.py
```

This will start the application using port 5000

<h4>Laravel</h4>

To start your Laravel application, first you must install its dependencies by running the following command

```sh
composer install
```

After the installation is done, duplicate the .env.example file in the root folder and save it as an .env file.

After saving it, generate a laravel app key by running the following command in the laravel root folder

```sh
php artisan key:generate
```

Once this is done, then run the following command to get your laravel application started

```sh
php artisan serve
```

The above command will start the laravel application on port 8080 and will be available via the URL http://localhost:8080

If you would like to run the application on a different port, you can specify the port by running the following command

```sh
php artisan serve --port=9000
```

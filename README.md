<p align="center">
    <img src="./public/assets/images/logo-light.png" width="150">
</p>
<p align="center">Free open source admin dashboard<br>
    <code><b>composer create-project anamsoft/hwaravel</b></code></p>
<p align="center">
<a href="https://packagist.org/packages/anamsoft/hwaravel"><img src="https://poser.pugx.org/anamsoft/hwaravel/d/total" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/anamsoft/hwaravel"><img src="https://poser.pugx.org/anamsoft/hwaravel/v/stable" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/anamsoft/hwaravel"><img src="https://poser.pugx.org/anamsoft/hwaravel/license" alt="License"></a>
</p>

## About Hwaravel

Hwaravel is the best free admin dashboard for individuals and businesses, built on top of Laravel Framework and the latest technologies.
Our goal is "Efficient and friendly for everyone":
- Efficiency: Meet even the smallest requirements of customers.
- Friendly: Easy to use, easy to maintain, easy to develop.
- Everyone: Businesses, individuals, developers, students.

## Technology
- Core <a href="https://laravel.com">Laravel Framework</a>

## Requirements:

From Version 5.0

> Core laravel framework 8.x requirements::

```
- PHP >= 7.3
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- Ctype PHP Extension
- JSON PHP Extension
- BCMath PHP Extension
```

## Installation & configuration:

**Step1: Install last version S-cart**

Option 1: **From composer**
```
composer create-project anamsoft/hwaravel
```

Option 2: **From github**
```
git clone https://github.com/anamsoftware/hwaravel.git
```
Then, install vendor:
```
composer install
```

**Step2: Set writable permissions for the following directories:**

- <code>storage</code>
- <code>vendor</code>
- <code>bootstrap/cache</code>


**Step3: Create database**
```
- Create a new database. Example database name is "hwaravel"
```

**Step4: Install**

```
1: Create new database.
2 Copy file .env.example to .env if file .env not exist.
3: Generate API key if APP_KEY is null. 
- Use command "php artisan key:gen"
4: Config value of file .env:
- APP_DEBUG=false (Set "false" is security)
- DB_HOST=127.0.0.1 (Database host)
- DB_PORT=3306 (Database port)
- DB_DATABASE=hwaravel (Database name)
- DB_USERNAME=root (User name use database)
- DB_PASSWORD= (Password connect to database)
- APP_URL=http://hwaravel.abc (Your url)
- APP_ADMIN_DIR=admin (Path to admin)
```

**Step5: Install completed**

- Access to url admin: <b>your-domain/admin</b>.
- User/pass <code><b>admin</b>/<b>admin123</b></code>

## Security Vulnerabilities:

If you discover a security vulnerability within Hwaravel, please send an e-mail to Phi Hoang via hoangphi.dev@gmail.com. All security vulnerabilities will be promptly addressed.

## License

The Hwaravel is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

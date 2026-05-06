# AJAXPHP Fullstack Starter

<p>
  <img alt="PHP 8.1+" src="https://img.shields.io/badge/PHP-8.1%2B-777bb4?logo=php&logoColor=white">
  <img alt="MySQL" src="https://img.shields.io/badge/Database-MySQL%20%2F%20MariaDB-4479a1?logo=mysql&logoColor=white">
  <img alt="Bootstrap" src="https://img.shields.io/badge/UI-Bootstrap%205.3-7952b3?logo=bootstrap&logoColor=white">
  <img alt="License" src="https://img.shields.io/badge/License-Apache%202.0-green">
</p>

A modern, lightweight PHP fullstack starter with an AJAX-driven frontend, a small MVC structure, and a ready baseline for authentication-oriented web applications.

The project keeps the stack intentionally simple: native PHP, MySQL/MariaDB, Bootstrap, jQuery, and clear file-level conventions. It is a good fit for learning projects, internal tools, client prototypes, and small production applications that do not need a large framework.

## Highlights

- Lightweight MVC organization with separate controller, model, view, and template files.
- AJAX-first page composition through `ajax.php`.
- User registration and login foundation with email validation, password validation, password hashing, and session state.
- Prepared-statement based MySQL access.
- `.env` and `.env.local` support.
- Bootstrap 5.3, Bootstrap Icons, and jQuery loaded from CDN.
- Database install script, ERD, and UML documentation under `_doku/`.

## Tech Stack

| Layer    | Tools                                               |
| -------- | --------------------------------------------------- |
| Backend  | PHP 8.1+, native MVC,`mysqli`                     |
| Frontend | HTML, CSS, Bootstrap 5.3, Bootstrap Icons, jQuery   |
| Database | MySQL or MariaDB                                    |
| Auth     | PHP sessions,`password_hash`, `password_verify` |
| Config   | `.env`, `.env.local`, `config/config.php`     |

## Project Structure

```text
.
├── ajax.php                 # AJAX request entry point
├── index.html               # Application shell
├── includes/
│   ├── bootstrap.php        # .env loader
│   └── autoload.php         # IAD namespace autoloader
├── config/
│   └── config.php           # Database and password settings
├── classes/                 # Shared backend classes
├── mvc/                     # MVC modules
│   ├── basecontroller.php
│   ├── basemodel.php
│   ├── baseview.php
│   ├── header/
│   ├── menu/
│   ├── footer/
│   ├── login/
│   └── user/
├── js/
│   ├── ajax.class.js        # jQuery AJAX wrapper
│   └── function.js          # UI behavior
├── css/
│   └── style.css
└── _doku/
    ├── db-install.sql       # Database setup script
    ├── database.erd
    └── uml.drawio
```

## Requirements

- PHP 8.1 or newer
- PHP `mysqli` extension
- MySQL or MariaDB
- A web server such as Apache, Nginx, Herd, Valet, XAMPP, or PHP's built-in server

This repository has no Composer or Node build step. Bootstrap, Bootstrap Icons, and jQuery are loaded from CDN.

## Quick Start

1. Serve the project from a PHP-capable web server.

   With Herd or Valet, the default local URL can be:

   ```text
   https://php-fullstack-starter.test
   ```

   With PHP's built-in server:

   ```bash
   php -S localhost:8000
   ```

   If you do not use the `.test` domain, update or remove the `app-herd-url` meta tag in `index.html`:

   ```html
   <meta name="app-herd-url" content="http://localhost:8000">
   ```
2. Create the database.

   ```bash
   mysql -u root -p < _doku/db-install.sql
   ```
3. Create a local `.env` file.

   ```dotenv
   DB_HOST=localhost
   DB_PORT=3306
   DB_NAME=php_fullstack_starter
   DB_USER=project_user
   DB_PASSWORD=Pa$$w0rd
   DB_CHARSET=utf8mb4
   ```
4. Open the application in your browser.

   ```text
   https://php-fullstack-starter.test
   ```

   or:

   ```text
   http://localhost:8000
   ```

## Configuration

`includes/bootstrap.php` loads `.env` first and `.env.local` second. Use `.env` for shared local defaults and `.env.local` for machine-specific overrides.

Supported database variables:

| Variable        | Default          | Description       |
| --------------- | ---------------- | ----------------- |
| `DB_HOST`     | `localhost`    | Database host     |
| `DB_PORT`     | `3306`         | Database port     |
| `DB_NAME`     | `project`      | Database name     |
| `DB_USER`     | `project_user` | Database user     |
| `DB_PASSWORD` | `Pa$$w0rd`     | Database password |
| `DB_CHARSET`  | `utf8mb4`      | Character set     |

The install script in `_doku/db-install.sql` creates a database named `php_fullstack_starter`, so `DB_NAME=php_fullstack_starter` is recommended for local setup.

Password rules are defined in `config/config.php`:

```php
define('PW_UPPERCASE', true);
define('PW_LOWERCASE', true);
define('PW_DIGIT', true);
define('PW_SYMBOL', true);
define('PW_MINLEN', 8);
define('PW_MAXLEN', 12);
```

## AJAX and MVC Flow

The frontend uses the `AJAX` class in `js/ajax.class.js` to send POST requests to `ajax.php`.

Call an MVC module:

```js
new AJAX({ mvc: "header.display", template: "special" }, showHeader);
```

This resolves to:

```text
IAD\mvc\header\Controller::display()
```

Call a backend class method:

```js
new AJAX({ class: "password.getMistakes", password: "Pa$$w0rd" }, callback);
```

This resolves to:

```text
IAD\classes\Password::getMistakes()
```

Typical JSON response:

```json
{
  "html": "...",
  "exceptions": []
}
```

## Adding a New MVC Module

Create a new folder under `mvc/<module-name>/` with this structure:

```text
mvc/example/
├── controller.php
├── model.php
├── view.php
└── tmpl/
    └── default.php
```

Use this namespace pattern:

```php
namespace IAD\mvc\example;
```

Then call it from the frontend:

```js
new AJAX({ mvc: "example.display" }, (response) => {
  document.querySelector("main").innerHTML = response.html;
});
```

To render a specific template:

```js
new AJAX({ mvc: "example.display", template: "compact" }, callback);
```

This looks for:

```text
mvc/example/tmpl/compact.php
```

If the requested template is missing, the base view falls back to `default.php` when available.

## Database Layer

SQL statements live in `classes/stmt.enum.php`:

```php
case addUser = 'INSERT INTO user (username, `password`) VALUES (?, ?)|ss';
case getUser = 'SELECT id, username, `password`, registered_at FROM user WHERE username = ?|s';
```

The part before `|` is the SQL query. The part after `|` contains the `mysqli::bind_param` type string.

Example:

```php
$db = new DB(Stmt::getUser, [$email]);
$users = $db->getResult();
```

## Authentication Flow

- Registration submits to `user.setRegister`.
- Login submits to `user.setLogin`.
- Logout calls `user.setLogout`.
- Session state is stored under `$_SESSION['auth']`.
- The menu renders either `Login` or `Abmelden` based on the current session state.

## Security Notes

- PHP files are guarded by the `_IAD` constant to reduce direct file access.
- Passwords are stored with `password_hash()` and verified with `password_verify()`.
- Database access uses prepared statements.
- Session cookies support `SameSite` and `HttpOnly`.
- During development, `ajax.php` can return exception details in JSON responses. For production, avoid exposing stack traces, file paths, and internal error details to end users.
- Some local session calls use `secure: false`. Revisit cookie security settings before deploying behind HTTPS.

## Development Notes

- The main page shell is `index.html`.
- Header, menu, footer, and modal content are loaded after page load through AJAX.
- `js/function.js` contains the main UI behavior.
- `js/script.js` contains older or alternative fetch-based example code and is not loaded by the default `index.html`.
- Local session files may be created under `tmp/`.
- `_doku/database.erd` and `_doku/uml.drawio` can be used as project documentation assets.

## License

This project is licensed under the Apache License 2.0. See `LICENSE` for details.

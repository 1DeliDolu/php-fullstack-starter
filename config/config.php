<?php
defined('_IAD') or die();

// database-settings from .env file
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_PORT', (int)(getenv('DB_PORT') ?: 3306));
define('DB_NAME', getenv('DB_NAME') ?: 'project');
define('DB_USER', getenv('DB_USER') ?: 'project_user');
define('DB_PASS', getenv('DB_PASSWORD') ?: getenv('DB_PASS') ?: 'Pa$$w0rd');
define('DB_CHARSET', getenv('DB_CHARSET') ?: 'utf8mb4');

// password requirements
define('PW_UPPERCASE', true);
define('PW_LOWERCASE', true);
define('PW_DIGIT', true);
define('PW_SYMBOL', true);
define('PW_MINLEN', 8);
define('PW_MAXLEN', 12);  // null for unlimited length or int gt PW_MINLEN
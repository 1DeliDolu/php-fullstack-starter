<?php
define('_IAD', false);

require('config/config.php');
require('includes/autoload.php');

use IAD\classes\Password;

$pass = new Password('');
var_dump( $pass->getMistakes() );
$pass = new Password('P');
var_dump( $pass->getMistakes() );
$pass = new Password('Pa');
var_dump( $pass->getMistakes() );
$pass = new Password('Pa$');
var_dump( $pass->getMistakes() );
$pass = new Password('Pa$$');
var_dump( $pass->getMistakes() );
$pass = new Password('Pa$$w');
var_dump( $pass->getMistakes() );
$pass = new Password('Pa$$w0');
var_dump( $pass->getMistakes() );
$pass = new Password('Pa$$w0r');
var_dump( $pass->getMistakes() );
$pass = new Password('Pa$$w0rd');
var_dump( $pass->getMistakes() );
$pass = new Password('Pa$$w0rd12345');
var_dump( $pass->getMistakes() );

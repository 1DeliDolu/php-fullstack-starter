<?php
namespace IAD\classes;

defined("_IAD") or die();

use Exception;
use IAD\classes\Log;
use IAD\classes\Mail;
use IAD\classes\Password;

final class User {
    private int $id;
    private Mail $username;
    private Password $password;

    public function __construct(array $creds) {
        
    }
}
<?php
namespace IAD\mvc;

defined('_IAD') or die();

use IAD\classes\Cookie;
use IAD\classes\DB;
use IAD\classes\Session;
use IAD\classes\SameSite;
use IAD\classes\Stmt;
use IAD\classes\Log;

abstract class BaseModel {
    protected DB $db;
    protected Session $session;
    protected Cookie $cookie;

    protected array $data = [];

    public function __construct(array &$data) {
        $this->data = $data;
    }
}
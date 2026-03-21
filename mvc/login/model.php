<?php
namespace IAD\mvc\login;

defined('_IAD') or die();

use IAD\mvc\BaseModel;
use IAD\classes\SameSite;
use IAD\classes\Stmt;
use IAD\classes\Log;

final class Model extends BaseModel {
    public function __construct(array &$data = []) {
        parent::__construct($data);
    }

    public function getModalID():int {
        return hrtime(true);
    }
}
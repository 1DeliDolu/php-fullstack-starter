<?php
namespace IAD\mvc\user;

defined("_IAD") or die();

use Exception;
use IAD\mvc\BaseController;
use IAD\mvc\user\Model as UserModel;
use IAD\classes\Log;

final class Controller extends BaseController {
    public function __construct(array &$creds) {
        try {
            parent::__construct($creds);
            $this->model = new UserModel($creds);
        }catch(Exception $e) {
            Log::add($e);
        }
    }
    public function setRegister():bool {
        try {
            return $this->model->setRegister();
        }catch(Exception $e){
            Log::add($e);
            return false;
        }
    }
    public function setLogin():bool {
        try {
            return false;
        }catch(Exception $e){
            Log::add($e);
            return false;
        }
    }
    public function getLogin():bool {
        try {
            return true;
        }catch(Exception $e){
            Log::add($e);
            return false;
        }
    }
    public function setLogout():bool {
        try {
            return true;
        }catch(Exception $e){
            Log::add($e);
            return false;
        }
    }
    public function getStates():array {
        try {
            $states = [];
            return $states;
        }catch(Exception $e){
            Log::add($e);
            return [];
        }
    }
}
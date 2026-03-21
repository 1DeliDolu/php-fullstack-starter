<?php
namespace IAD\mvc\menu;

defined('_IAD') or die();

use IAD\mvc\BaseModel;
use IAD\classes\Session;
use IAD\classes\Log;

final class Model extends BaseModel {
    public function __construct(array &$data = []) {
        parent::__construct($data);
    }
    public function isLoggedIn(): bool {
        try {
            new Session(secure: false, savepath: '/tmp');
            $auth = $_SESSION['auth'] ?? null;
            return is_array($auth) && !empty($auth['logged_in']);
        } catch(\Exception $e) {
            Log::add($e);
            return false;
        }
    }
    public function getUsername(): string {
        $auth = $_SESSION['auth'] ?? null;
        return is_array($auth) ? htmlspecialchars($auth['username'] ?? '', ENT_QUOTES, 'UTF-8') : '';
    }
}
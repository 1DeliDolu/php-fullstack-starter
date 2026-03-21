<?php
namespace IAD\mvc\user;

defined("_IAD") or die();

use Exception;
use IAD\mvc\BaseModel;
use IAD\classes\DB;
use IAD\classes\Log;
use IAD\classes\Mail;
use IAD\classes\Password;
use IAD\classes\Session;
use IAD\classes\Stmt;

final class Model extends BaseModel {
    private int|null $id = null;
    private Mail $username;
    private Password $password;
    public function __construct(array &$data = []) {
        try{
            parent::__construct($data);
            $this->id = $data['data']['id'] ?? null;
            $this->username = new Mail($data['data']['username'] ?? '');
            $this->password = new Password($data['data']['pass'] ?? '');
        }catch(Exception $e){
            Log::add($e);
        }
    }
    public function setRegister():bool {
        try{
            // if username and password are'nt valid
            if(!$this->username->getVerify() || $this->password->getMistakes()['code']){
                throw new Exception(sprintf('Es konnte keine Registrierung durchgeführt werden! Ungültiger Benutzername %s oder ungültiges Passwort .', $this->username->getMail()), 0x800A0001);
            }
            // if username already exists
            $this->db = new DB(Stmt::getUser, [$this->username->getMail()]);
            if($this->db->getNumRows()){
                throw new Exception('Es existiert bereits ein Benutzer mit dieser E-Mail-Adresse.', 0x800A0002);
            }
            // save user
            $this->db = new DB(Stmt::addUser, [$this->username->getMail(), $this->password->getHash()]);
            if(!$this->db->getInsertID()) {
                throw new Exception('Der Benutzer konnte nicht angelegt werden!', 0x800A0003);
            }
            return true;
        }catch(Exception $e){
            Log::add($e);
            return false;
        }
    }
    public function setLogin():bool {
        try {
            if(!$this->username->getVerify()){
                throw new Exception('Es konnte keine Anmeldung durchgeführt werden! Ungültige E-Mail-Adresse.', 0x800A0004);
            }

            $this->db = new DB(Stmt::getUser, [$this->username->getMail()]);
            $users = $this->db->getResult();
            if(!$users || !count($users)) {
                throw new Exception('Die Anmeldedaten sind ungültig.', 0x800A0005);
            }

            $user = $users[0];
            if(!$this->password->isValide($user['password'] ?? '')) {
                throw new Exception('Die Anmeldedaten sind ungültig.', 0x800A0005);
            }

            $this->session = new Session(secure: false, savepath: '/tmp');
            session_regenerate_id(true);
            $_SESSION['auth'] = [
                'id' => (int)($user['id'] ?? 0),
                'username' => $user['username'] ?? $this->username->getMail(),
                'logged_in' => true,
                'logged_in_at' => time(),
            ];

            return true;
        } catch(Exception $e) {
            Log::add($e);
            return false;
        }
    }
    public function setLogout():bool {
        try {
            $this->session = new Session(secure: false, savepath: '/tmp');
            unset($_SESSION['auth']);
            session_regenerate_id(true);
            return true;
        } catch(Exception $e) {
            Log::add($e);
            return false;
        }
    }
    public function getStates():array {
        try {
            $this->session = new Session(secure: false, savepath: '/tmp');
            $auth = $_SESSION['auth'] ?? null;
            return [
                'logged_in' => is_array($auth) && !empty($auth['logged_in']),
                'user' => is_array($auth) ? [
                    'id' => $auth['id'] ?? null,
                    'username' => $auth['username'] ?? null,
                ] : null,
            ];
        } catch(Exception $e) {
            Log::add($e);
            return [
                'logged_in' => false,
                'user' => null,
            ];
        }
    }
}

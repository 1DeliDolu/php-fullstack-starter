<?php
namespace IAD\classes;

use Exception;

defined('_IAD') or die();

final class Session extends CookieSession {
    private int $lifetime = 0;
    private string $savepath;


    public function __construct(int $lifetime = 0, string|null $domain = null, string $path = "/", bool $secure = true, bool $httponly = true, string|null $savepath = null, SameSite $samesite = SameSite::Strict) {
        try{
            if($this->isStarted() === PHP_SESSION_DISABLED)throw new Exception('Session is disabled!', 0x80030001);
            if($this->isStarted() === PHP_SESSION_NONE){
                parent::__construct($domain, $path, $secure, $httponly, $samesite);
                if(!$this->setLifeTime($lifetime))throw new Exception('Die Sessionlebenszeit konnte nicht übernommen werden!', 0x80030002);
                if(!$this->setSavePath($savepath))throw new Exception('Der Speicherort konnte nicht übernommen werden!', 0x80030003);
            }
            $this->start();
        }catch(Exception $e){
            Log::add($e);
        }
    }
    private function setLifeTime(int $time = 0):bool {
        try{
            // muss mindestens 0 sein; 0 bis schließen des browsers
            $this->lifetime = max(0, $time);
            return true;
        }catch(Exception $e){
            Log::add($e);
            return false;
        }
    }
    private function setSavePath(string|null $path = null):bool {
        try{
            // wenn null hole wert aus php.ini
            if(is_null($path) || !str_starts_with($path, '/')) {
                $path = ini_get('session.save_path');
            }
            // prüfe ob pfad existiert, lege ihn ggf. an; nur innerhalb des domain-dirs möglich!
            else{
                $dirs = explode('/', substr($path, 1));
                $path = $_SERVER['DOCUMENT_ROOT'];  // C:/xampp/htdocs/project | Y:/htdocs/project
                foreach($dirs as $dir) {
                    $path .= '/' . $dir;
                    if(!is_dir($path)) mkdir($path, 0600, true);
                }                
            }
            // übernehme pfad
            $this->savepath = $path;
            return true;
        }catch(Exception $e){
            Log::add($e);
            return false;
        }
    }
    private function start():bool {
        try{
            session_set_cookie_params([
                'lifetime'  => $this->lifetime,
                'domain'    => $this->domain,
                'path'      => $this->path,
                'secure'    => $this->secure,
                'httponly'  => $this->httponly,
                'samesite'  => $this->samesite->value
            ]);
            session_save_path($this->savepath);
            session_start();
            // session_regenerate_id(true);
            if($this->isStarted() === PHP_SESSION_ACTIVE) {
                return true;
            }else{
                throw new Exception('Session konnte nicht gestartet werden!', 0x80030004);
            }
        }catch(Exception $e){
            Log::add($e);
            return false;
        }
    }
    private function destroy():bool {
        try{
            if($this->isStarted() === PHP_SESSION_DISABLED)throw new Exception('Session is disabled!', 0x80030001);
            if($this->isStarted() === PHP_SESSION_NONE)throw new Exception('Session is not started!', 0x80030005);
            if(!session_destroy())throw new Exception('Session konnte nicht zerstört werden!', 0x80030006);
            return true;
        }catch(Exception $e){
            Log::add($e);
            return false;
        }
    }
    public function isStarted():int {
        try{
            return session_status();
        }catch(Exception $e){
            Log::add($e);
            return false;
        }
    }
}
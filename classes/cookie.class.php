<?php
namespace IAD\classes;

defined('_IAD') or die();

// über use ns\classname werden die abhängigkeiten definiert
// die definierte spl_autoload_register(function(ns\class){...}) reagiert auf das verwenden unbekannter klassen und kann diese bereitstellen
use Exception;
use IAD\classes\CookieSession;
use IAD\classes\Log;
use IAD\classes\SameSite;

final class Cookie extends CookieSession {
    private string $cookiename;
    private string $cookievalue;
    private int $expires;

    public function __construct(string $name, array|bool|float|int|string $value, int|null $expires = null, string|null $domain = null, string $path = "/", bool $secure = true, bool $httponly = true, SameSite $samesite = SameSite::Strict) {
        try{
            parent::__construct($domain, $path, $secure, $httponly, $samesite);
            if(!$this->setCookieName($name))throw new Exception("Der Name $name konnte für das Cookie nicht verwendet werden!", 0x80020001);
            if(!$this->setCookieValue($value))throw new Exception('Der Wert konnte für das Cookie nicht übernommen werden!', 0x80020002);
            if(!$this->setExpires($expires))throw new Exception('Die Ablaufzeit konnte für das Cookie nicht gesetz werden!', 0x80020003);
            if(!$this->add())throw new Exception('Das Cookie konnte nicht erstellt werden!', 0x80020004);
        }catch(Exception $e){
            Log::add($e);
        }
    }
    private function setCookieName(string $name):bool {
        try{
            $name = preg_replace('/\s/', '', $name);
            if(!strlen($name))throw new Exception('Es wurde kein Name für das Cookie angegeben!', 0x80020005);
            $this->cookiename = $name;
            return true;
        }catch(Exception $e){
            Log::add($e);
            return false;
        }
    }
    private function setCookieValue(array|bool|float|int|string $value):bool {
        try{
            if(is_array($value)){
                $value = json_encode($value);
            }elseif(is_bool($value)){
                $value = $value ? 'true' : 'false';
            }elseif(is_int($value) || is_float($value)){
                $value = (string)$value;
            }
            $this->cookievalue = $value;
            return true;
        }catch(Exception $e){
            Log::add($e);
            return false;
        }
    }
    private function setExpires(int|null $expires):bool {
        try{
            if(is_null($expires) || $expires < 0){
                $expires = 0;
            }
            $this->expires = $expires;
            return true;
        }catch(Exception $e){
            Log::add($e);
            return false;
        }
    }
    private function add():bool {
        try{
            return setcookie($this->cookiename, $this->cookievalue, [
                'expires'   => time() + $this->expires,
                'domain'    => $this->domain,
                'path'      => $this->path,
                'secure'    => $this->secure,
                'httponly'  => $this->httponly,
                'samesite'  => $this->samesite->value
            ]);
        }catch(Exception $e){
            Log::add($e);
            return false;
        }
    }
    public static function get(string $name):array|bool|float|int|string|null {
        try{
            $name = preg_replace('/\s/', '', $name);
            if(!strlen($name))throw new Exception('Es wurde kein Name für das Cookie angegeben!', 0x80020006);
            if(!array_key_exists($name, $_COOKIE))throw new Exception('Das Cookie ist nicht vorhanden!',  0x80020007);
            // lese wert
            $value = $_COOKIE[$name];
            // wandle wert um
            if(preg_match('/^(\{.*\})|(\[.*\])$/', $value)){
                $value = json_decode($value, true);
            }elseif(preg_match('/^\d+$/', $value)){
                $value = (int)$value;
            }elseif(preg_match('/^\d*\.\d+$/', $value)){
                $value = (float)$value;
            }elseif($value === 'true'){
                $value = true;
            }elseif($value === 'false'){
                $value = false;
            }
            return $value;
        }catch(Exception $e){
            Log::add($e);
            return null;
        }
    }
    public static function delete(string $name):bool {
        try{
            $name = preg_replace('/\s/', '', $name);
            if(!strlen($name))throw new Exception('Es wurde kein Name für das zu löschende Cookie angegeben!', 0x80020008);
            if(!array_key_exists($name, $_COOKIE))throw new Exception('Das Cookie ist nicht vorhanden und kann nicht gelöscht werden!',  0x80020009);
            return setcookie($name, '', [
                'expires'   => time() -86400,
                'domain'    => $_SERVER['SERVER_NAME']
            ]);
        }catch(Exception $e){
            Log::add($e);
            return false;
        }
    }
}
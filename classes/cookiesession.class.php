<?php
namespace IAD\classes;

defined('_IAD') or die();

use Exception;
use IAD\classes\Log;
use IAD\classes\SameSite;


abstract class CookieSession {
    protected string $domain;
    protected string $path = "/";
    protected bool $secure = true;
    protected bool $httponly = true;
    protected SameSite $samesite = SameSite::Strict;

    public function __construct(string|null $domain, string $path, bool $secure, bool $httponly, SameSite $samesite){
        try {
            if(!$this->setDomain($domain))throw new Exception('Domain nicht gesetzt!', 0x80010001);
            if(!$this->setPath($path))throw new Exception('Pfad nicht gesetzt!', 0x80010002);
            if(!$this->setSecure($secure))throw new Exception('Sicherheitseinstellung nicht gesetzt!', 0x80010003);
            if(!$this->setHttpOnly($httponly))throw new Exception('HTTP-Modus nicht gesetzt!', 0x80010004);
            if(!$this->setSameSite($samesite))throw new Exception('SameSite-Modus nicht gesetzt!', 0x80010005);
        }catch(Exception $e){
            Log::add($e);
        }
    }
    private function setDomain(string|null $domain):bool {
        try {
            // prüfe $domain auf null -> setze eigene domain
            if(is_null($domain)){
                $domain = $_SERVER['SERVER_NAME'];
            }                
            // sonst prüfe ob domain existiert
            else {
                // extrahiere aus einer url die domain
                $regex = '/^(\w+:\/\/)?(([\w|-]+\.)+\w{2,10})(\/.*)?/';
                $domain = preg_replace($regex, '$2', $domain);
                if(!checkdnsrr($domain, 'ANY')){
                    throw new Exception("Die Domain $domain ist nicht existent!", 0x80010006);
                }
            }
            // setze domain in object
            $this->domain = $domain;
            return true;
        }catch(Exception $e){
            Log::add($e);
            return false;
        }
    }
    private function setPath(string $path):bool {
        try {
            $this->path = $path;
            return true;
        }catch(Exception $e){
            return false;
        }
    }
    private function setSecure(bool $secure):bool {
        try {
            $this->secure = $secure;
            return true;
        }catch(Exception $e){
            return false;
        }
    }
    private function setHttpOnly(bool $httponly):bool {
        try {
            $this->httponly = $httponly;
            return true;
        }catch(Exception $e){
            return false;
        }
    }
    private function setSameSite(SameSite $samesite):bool {
        try {
            $this->samesite = $samesite;
            return true;
        }catch(Exception $e){
            return false;
        }
    }
}
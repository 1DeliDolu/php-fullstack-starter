<?php
namespace IAD\classes;

defined('_IAD') or die();

use Exception;

abstract class Server {
    protected string $host = '';
    protected int $port = 0;
    protected string|null $user = null;
    protected string|null $password = null;
    protected string|null $service = null;
    protected string|null $protocol = null;

    public function __construct(string $host, int $port, string $user, string $password) {
        try {
            if(!$this->setHost($host))throw new Exception("Der Server $host konnte nicht gesetzt werden!", 0x80040001);
            if(!$this->setPort($port))throw new Exception("Der Port $port konnte nicht gesetzt werden!", 0x80040002);
            if(!$this->setUser($user))throw new Exception("Der Benutzername $user konnte nicht gesetze werden!", 0x80040003);
            if(!$this->setPassword($password))throw new Exception("Das Passwort ***** konnte nicht gesetzt werden!", 0x80040004);
        }catch(Exception $e) {
            Log::add($e);
        }
    }
    protected function setHost(string $host, string $type = 'ANY'):bool {
        try {
            // prüfe ob host als IPv4 vorliegt und ersetze sie durch den host
            // filter_var: bietet verschiede filter um werte auf etwas zu überprüfem (ip, mail, float, int, ...)
            if (filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4|FILTER_FLAG_IPV6)) {
                $tmp = gethostbyaddr($host);
                if(!$tmp)throw new Exception("Für die IP $host ist keine gültige Domain ermittelbar!", 0x80040005);
                $host = $tmp;
                unset($tmp);
            }
            // prüfe ob für host ein dns-eintrag ermittelbar ist
            if(!checkdnsrr($host, $type))throw new Exception("Der Host $host ist unbekannt!", 0x80040006);
            // übernehme host
            $this->host = $host;
            return true;
        }catch(Exception $e) {
            Log::add($e);
            return false;
        }
    }
    private function setPort(int $port):bool {
        try {
            // muss zwischen 1 und 65535 liegen (16 Bit-Integer)
            if($port < 1 || $port > 65535)throw new Exception("Der Port $port ist ungültig!", 0x80040007);
            // übernehme port
            $this->port = (int)$port;
            // ermittle service und protocol - nur ermittelbar, wenn eintrag in /etc/services vorhanden ist (port 0 - 1023)
            if(getservbyport($port, 'udp')){
                $this->service = getservbyport($port, 'udp');
                $this->protocol = 'udp';
            }elseif(getservbyport($port, 'tcp')){
                $this->service = getservbyport($port, 'tcp');
                $this->protocol = 'tcp';
            }
            return true;
        }catch(Exception $e) {
            Log::add($e);
            return false;
        }
    }
    private function setUser(string $user):bool {
        try {
            $this->user = trim($user);
            return true;
        }catch(Exception $e) {
            Log::add($e);
            return false;
        }
    }
    private function setPassword(string $password):bool {
        try {
            $this->password = trim($password);
            return true;
        }catch(Exception $e) {
            Log::add($e);
            return false;
        }
    }
    protected function isAvailable(bool $withCreds = false, object|null $fnConnect = null):bool {
        try {
            $available = false;
            if(!$withCreds) {
                //prüfe ob host und port erreichbar sind
                $available = @fsockopen($this->host, $this->port, timeout: 1) ? true : false;
            }elseif(!is_object($fnConnect) && !is_callable($fnConnect)) {
                throw new Exception("Es muss eine Funktion zum Überprüfen der Verbindung übergeben werden!", 0x80040008);
            }elseif(is_callable($fnConnect)){
                $available = $fnConnect($this->host, $this->port, $this->user, $this->password);
            }
            return $available;
        }catch(Exception $e) {
            Log::add($e);
            return false;
        }
    }
}
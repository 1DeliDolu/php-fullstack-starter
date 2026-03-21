<?php
namespace IAD\classes;

defined('_IAD') or die();

use Exception;
use IAD\classes\Server;
use IAD\classes\Log;

final class Mail extends Server {
    private string|null $mail = null;
    public function __construct(string|array $mail) {
        if(is_array($mail) && array_key_exists('mail', $mail)) {
            $this->mail = $mail['mail'] ?? '';
        }elseif(is_array($mail) && array_key_exists('data', $mail)) {
            $this->mail = $mail['data']['mail'] ?? '';
        }else{
            $this->mail = $mail;
        }
    }

    public function getVerify():bool {
        try {
            if(!filter_var($this->mail, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Die E-Mail-Adresse ist ungültig!', 0x80060001);
            }
            $domain = explode('@', $this->mail)[1];
            parent::__construct($domain, 1, '', '');
            if(!$this->setHost($domain, 'MX')) {
                throw new Exception('Der Domain-Server für die E-Mail-Adresse konnte nicht ermittelbar werden!', 0x80060002);
            }
            return true;
        }catch(Exception $e) {
            Log::add($e);
            return false;
        }
    }

    public function getMail():string {
        return $this->mail ?? '';
    }
}

<?php
namespace IAD\classes;

defined('_IAD') or die();

use Exception;
use IAD\classes\Log;

final class Password {
    // requirements
    private bool $lowercase = true;
    private bool $uppercase = true;
    private bool $digit = true;
    private bool $symbol = true;
    private int|null $maxlen = null;
    private int $minlen = 8;
    private string|null $password = null;
    public function __construct(string|array $password) {
        try{
            if(defined('PW_UPPERCASE') && is_bool(PW_UPPERCASE))$this->uppercase = PW_UPPERCASE;
            if(defined('PW_LOWERCASE') && is_bool(PW_LOWERCASE))$this->lowercase = PW_LOWERCASE;
            if(defined('PW_DIGIT') && is_bool(PW_DIGIT))$this->digit = PW_DIGIT;
            if(defined('PW_SYMBOL') && is_bool(PW_SYMBOL))$this->symbol = PW_SYMBOL;
            if(!($this->uppercase|$this->lowercase|$this->digit|$this->symbol))$this->lowercase = true;
            if(defined('PW_MINLEN') && is_int(PW_MINLEN) && PW_MINLEN > 0)$this->minlen = PW_MINLEN;
            if(defined('PW_MAXLEN') && is_int(PW_MAXLEN) && PW_MAXLEN >= $this->minlen)$this->maxlen = PW_MAXLEN;
            if(is_array($password) && array_key_exists('password', $password)) {
                $this->password = $password['password'] ?? '';
            }elseif(is_array($password) && array_key_exists('data', $password)) {
                $this->password = $password['data']['password'] ?? '';
            }else{
                $this->password = $password;
            }
        }catch(Exception $e){
            Log::add($e);
        }
    }
    public static function getPassword():string|bool {
        try {
            $uppercase = $lowercase = $digit = $symbol = true;
            $minlen = 8;
            $password = '';
            // defaults
            if(defined('PW_UPPERCASE') && is_bool(PW_UPPERCASE))$uppercase = PW_UPPERCASE;
            if(defined('PW_LOWERCASE') && is_bool(PW_LOWERCASE))$lowercase = PW_LOWERCASE;
            if(defined('PW_DIGIT') && is_bool(PW_DIGIT))$digit = PW_DIGIT;
            if(defined('PW_SYMBOL') && is_bool(PW_SYMBOL))$symbol = PW_SYMBOL;
            if(!($uppercase|$lowercase|$digit|$symbol))$lowercase = true;
            if(defined('PW_MINLEN') && is_int(PW_MINLEN) && PW_MINLEN > 0)$minlen = PW_MINLEN;
            // create array(s?) with ascii values
            $possible = [];
            $current = [];
            if($uppercase)$possible[] = range(65, 90);
            if($lowercase)$possible[] = range(97, 122);
            if($digit)$possible[] = range(48, 57);
            if($symbol)$possible[] = array_merge(range(33, 38),range(40, 43),range(45, 47),range(58, 64),range(91, 95),range(123, 126));
            // choose random-characters, but one from each array
            while(count($current) < $minlen){
                if(!count($current)){
                    foreach($possible as $signs){
                        $current[] = chr( $signs[random_int(0, count($signs)-1)] );
                    }
                }else{
                    $signs = $possible[ random_int(0, count($possible)-1) ];
                    $current[] = chr( $signs[random_int(0, count($signs)-1)] );
                }
            }
            // mix random characters
            shuffle($current);
            // create string
            $password = implode('', $current);
            // return password
            return $password;
        }catch(Exception $e){
            Log::add($e);
            return false;
        }
    }
    public function getHash():string|false {
        try{
            return password_hash($this->password, PASSWORD_DEFAULT);
        }catch(Exception $e){
            Log::add($e);
            return false;
        }
    }

    public function isValide(string $hash):bool {
        try{
            return password_verify($this->password, $hash);
        }catch(Exception $e){
            Log::add($e);
            return false;
        }
    }

    public function getMistakes():array {
        try {
            $neccessaries = 1;
            // binary code to store mistakes; 0b00000 is no mistake
            $code = 0b000000;
            // looking for mistakes; if mistake found, set bit in code
            // check length
            if(strlen($this->password) < $this->minlen || (!is_null($this->maxlen) && strlen($this->password) > $this->maxlen)) $code |= 0b00001;
            // check uppercase, if neccessary
            if($this->uppercase)$neccessaries++;
            if($this->uppercase && !preg_match('/[A-Z]/', $this->password)) $code |= 0b000010;
            // check lowercase, if neccessary
            if($this->lowercase)$neccessaries++;
            if($this->lowercase && !preg_match('/[a-z]/', $this->password)) $code |= 0b000100;
            // check digit, if neccessary   -> [0-9] | \d
            if($this->digit)$neccessaries++;
            if($this->digit && !preg_match('/\d/', $this->password)) $code |= 0b001000;
            // check symbol, if neccessary
            if($this->symbol)$neccessaries++;
            if($this->symbol && !preg_match('/\W/', $this->password)) $code |= 0b010000;
        }catch(Exception $e){
            Log::add($e);
            $code = 0b100000;
        }finally{
            // set error-message
            $msgs = [];
            if($code & 0b000001) $msgs[] = is_null($this->maxlen) ? sprintf('Das Passwort muss mindestens %d Zeichen lang sein!', $this->minlen) : sprintf('Das Passwort muss zwischen %d und %d Zeichen lang sein', $this->minlen, $this->maxlen);
            if($code & 0b000010) $msgs[] = 'Das Passwort muss mindestens einen Großbuchstaben enthalten!';
            if($code & 0b000100) $msgs[] = 'Das Passwort muss mindestens einen Kleinbuchstaben enthalten!';
            if($code & 0b001000) $msgs[] = 'Das Passwort muss mindestens einen Ziffer enthalten!';
            if($code & 0b010000) $msgs[] = 'Das Passwort muss mindestens einen Sonderzeichen enthalten!';
            if($code & 0b100000) $msgs[] = 'Bei der Überprüfung der Richtigkeit des Passworts ist ein unbekannter Fehler aufgetreten!';
            // return result array
            return [
                'code'          => $code,
                // change code to binary-string and count 0
                'percentage'    => $code & 0b100000 ? 0 : substr_count(sprintf('%0'.$neccessaries.'d', decbin($code)), '0') / $neccessaries,
                'msgs'          => $msgs
            ];
        }
    }
}
<?php
namespace IAD\classes;

defined('_IAD') or die();

use Exception;
use mysqli;
use mysqli_stmt;
use mysqli_result;

final class DB extends Server {
    private string $db;
    private mysqli|false $dbc = false;
    private mysqli_stmt|false $stmt = false;
    private mysqli_result|false $result = false;
    private int|false $num_rows = false;
    private int|string|false $insert_id = false;
    private int|false $affected_rows = false;

    public function __construct(Stmt|null $stmt, array $values = [], string|null $host = null, int|null $port = null, string|null $db = null, string|null $user = null, string|null $password = null){
        try {
            // deaktiviere das mysqli-Error-Reporting
            mysqli_report(MYSQLI_REPORT_OFF);
            error_reporting(MYSQLI_REPORT_OFF);
            // prüfe die parameter auf null und setze definierte config-consts oder defaultwerte aus der php.ini
            if(!defined('DB_HOST'))define('DB_HOST', null);
            if(!defined('DB_PORT'))define('DB_PORT', null);
            if(!defined('DB_NAME'))define('DB_NAME', null);
            if(!defined('DB_USER'))define('DB_USER', null);
            if(!defined('DB_PASS'))define('DB_PASS', null);
            $host = $host ?? DB_HOST ?? ini_get('mysqli.default_host');
            $port = $port ?? DB_PORT ?? ini_get('mysqli.default_port');
            $db = $db ?? DB_NAME ?? '1';
            $user = $user ?? DB_USER ?? ini_get('mysqli.default_user');
            $password = $password ?? DB_PASS ?? ini_get('mysqli.default_password');

            parent::__construct($host, $port, $user, $password);

            if(!is_null($db) && !$this->setDB($db))throw new Exception("Die Datenbank $db wurde nicht übernommen!", 0x80050001);
            if(!$this->connect())throw new Exception("Die Verbindung zur Datenbank ist fehlgeschlagen!", 0x80050002);
            if(!is_null($stmt))$this->query($stmt, $values);
        }catch(Exception $e) {
            Log::add($e);
        }
    }
    private function connect():bool {
        try {
            if(!$this->isAvailable(false)){
                throw new Exception("Der DB-Server ist offline oder nicht erreichbar!", 0x80050003);
            }
            $this->dbc = new mysqli($this->host, $this->user, $this->password, $this->db, $this->port);
            if($this->dbc->connect_errno){
                throw new Exception(message: $this->dbc->connect_error, code: $this->dbc->connect_errno);
            }elseif($this->dbc === false) {
                throw new Exception("Die Verbindung mit dem Datenbankserver ist aus unbekannten Gründen fehlgeschlagen!", 0x80050004);
            }
            return true;
        }catch(Exception $e) {
            Log::add($e);
            return false;
        }
    }
    private function setDB(string|null $db):bool {
        try {
            if(!defined('DB_NAME'))define('DB_NAME', '1');
            $this->db = $db ?? DB_NAME;
            return true;
        }catch(Exception $e) {
            Log::add($e);
            return false;
        }
    }
    public function query(Stmt $stmt, array $values = []):bool {
        try {            
            // erzeuge mysqli_stmt
            $sql = $stmt->getQuery();
            $this->stmt = new mysqli_stmt($this->dbc, $sql);
            if($this->stmt->errno)throw new Exception(message: $this->stmt->error, code: $this->stmt->errno);
            // prüfe ob länge von types mit länge von values übereinstimmt
            $types = $stmt->getTypes();
            if(count($values) != strlen($types))throw new Exception('Fehler bei der Erstellung des SQL-Statements! Die übergebenen Werte stimmen nicht mit den erforderlichen Werten überein.', 0x80050005);            
            // binde values an stmt
            if(strlen($types)){
                $this->stmt->bind_param($types, ...$values);
                if($this->stmt->errno)throw new Exception(message: $this->stmt->error, code: $this->stmt->errno);
            }
            // führe statement aus
            $this->stmt->execute();
            if($this->stmt->errno)throw new Exception(message: $this->stmt->error, code: $this->stmt->errno);

            // prüfe welches stmt ausgeführt wurde
            $sql = ltrim(strtolower($sql));
            // bei select, ermittle result und num_rows
            if(str_starts_with($sql, 'select')){
                $this->result = $this->stmt->get_result();
                if($this->result === false)throw new Exception(message: $this->stmt->error, code: $this->stmt->errno);
                $this->num_rows = $this->result->num_rows;
            }
            // bei insert, ermittle insert id
            elseif(str_starts_with($sql, 'insert')){
                $this->insert_id = $this->stmt->insert_id;
            }
            // bei update/delete ermittle affected rows
            elseif(str_starts_with($sql, 'update') || str_starts_with($sql, 'delete')){ // preg_match('/^(update|delete)/i', $sql)){
                $this->affected_rows = $this->stmt->affected_rows;
            }
            return true;
        }catch(Exception $e) {
            Log::add($e);
            return false;
        }
    }
    public function getResult(int $type = MYSQLI_ASSOC):array|false {
        try {
            if(!$this->result)throw new Exception("Es konnte keine Abfrage ausgeführt werden!", 0x80050006);
            if($type !== MYSQLI_ASSOC && $type!== MYSQLI_NUM)$type = MYSQLI_BOTH;
            return $this->result->fetch_all($type);
        }catch(Exception $e) {
            Log::add($e);
            return false;
        }
    }
    public function getNumRows():int|false {
        return $this->num_rows;
    }
    public function getInsertID():int|string|false {
        return $this->insert_id;
    }
    public function getAffectedRows():int|false {
        return $this->affected_rows;
    }
}
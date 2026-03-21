<?php
namespace IAD\mvc;

defined('_IAD') or die();

use IAD\classes\Log;

abstract class BaseView {
    protected $model;
    protected array $data = [];
    protected string|false $tmplPath = false;

    public function __construct(array &$data, string $dir) {
        $this->data = $data;
        # __DIR__  -> "C:\xampp\htdocs\project\mvc\header
        $this->tmplPath = '%s\tmpl\%s.php';
        // prüfe ob ein template explizit angegeben wurde und existiert; wenn nicht default oder false
        if(array_key_exists('template', $this->data['data']) &&
                file_exists(sprintf($this->tmplPath, $dir, $this->data['data']['template'])) ) {
            $this->tmplPath = sprintf($this->tmplPath, $dir, $this->data['data']['template']);
        }elseif(file_exists(sprintf($this->tmplPath, $dir, 'default'))){
            $this->tmplPath = sprintf($this->tmplPath, $dir, 'default');
        }else{
            $this->tmplPath = false;
        }
    }
    public function display():string|array {
        // stoppe/puffere ausgaben: ob_... output_buffering
        ob_start();
        // include template
        if($this->tmplPath !== false) {
            include $this->tmplPath;
        }else{
            echo '';
        }
        // gebe gepufferte ausgaben zurück
        return ob_get_clean();
    }
}
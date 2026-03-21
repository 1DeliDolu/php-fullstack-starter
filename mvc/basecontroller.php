<?php
namespace IAD\mvc;

defined('_IAD') or die();

// der definierte alias ist nicht unbedingt notwendig, macht jedoch sinn, wenn ein MVC auf ein anderes zugreift
use IAD\classes\Log;

abstract class BaseController {
    protected $view;
    protected $model;
    protected array $data;
    public function __construct(array &$data = []){
        $this->data = $data;
    }
    public function display(): string|array {
        return $this->view->display();
    }
}
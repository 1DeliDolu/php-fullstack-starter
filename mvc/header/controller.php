<?php
namespace IAD\mvc\header;

defined('_IAD') or die();

// der definierte alias ist nicht unbedingt notwendig, macht jedoch sinn, wenn ein MVC auf ein anderes zugreift
use IAD\mvc\BaseController;
use IAD\mvc\header\Model as HeaderModel;
use IAD\mvc\header\View as HeaderView;
use IAD\classes\Log;

final class Controller extends BaseController {
    public function __construct(array &$data = []){
        parent::__construct($data);
        $this->view = new HeaderView($this->data);
        $this->model = new HeaderModel($this->data);
    }
}
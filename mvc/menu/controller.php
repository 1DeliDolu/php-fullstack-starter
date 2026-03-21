<?php
namespace IAD\mvc\menu;

defined('_IAD') or die();

// der definierte alias ist nicht unbedingt notwendig, macht jedoch sinn, wenn ein MVC auf ein anderes zugreift
use IAD\mvc\BaseController;
use IAD\mvc\menu\Model as MenuModel;
use IAD\mvc\menu\View as MenuView;
use IAD\classes\Log;

final class Controller extends BaseController {
    public function __construct(array &$data = []){
        parent::__construct($data);
        $this->view = new MenuView($this->data);
        $this->model = new MenuModel($this->data);
    }
}
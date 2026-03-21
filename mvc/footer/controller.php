<?php
namespace IAD\mvc\footer;

defined('_IAD') or die();

// der definierte alias ist nicht unbedingt notwendig, macht jedoch sinn, wenn ein MVC auf ein anderes zugreift
use IAD\mvc\BaseController;
use IAD\mvc\footer\Model as FooterModel;
use IAD\mvc\footer\View as FooterView;
use IAD\classes\Log;

final class Controller extends BaseController {
    public function __construct(array &$data = []){
        parent::__construct($data);
        $this->view = new FooterView($this->data);
        $this->model = new FooterModel($this->data);
    }
}
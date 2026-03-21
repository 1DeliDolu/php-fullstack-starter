<?php
namespace IAD\mvc\login;

defined('_IAD') or die();

// der definierte alias ist nicht unbedingt notwendig, macht jedoch sinn, wenn ein MVC auf ein anderes zugreift
use IAD\mvc\BaseController;
use IAD\mvc\login\Model as LoginModel;
use IAD\mvc\login\View as LoginView;
use IAD\classes\Log;

final class Controller extends BaseController {
    public function __construct(array &$data = []){
        parent::__construct($data);
        $this->view = new LoginView($this->data);
        $this->model = new LoginModel($this->data);
    }
}
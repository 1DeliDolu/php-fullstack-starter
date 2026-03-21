<?php
namespace IAD\mvc\login;

defined('_IAD') or die();

use IAD\mvc\BaseView;
use IAD\mvc\login\Model as LoginModel;
use IAD\classes\Log;

final class View extends BaseView {
    protected int $modalID = 0;
    public function __construct(array &$data = []) {
        parent::__construct($data, __DIR__);
        $this->model = new LoginModel($this->data);
        if(preg_match('/^modal/i', $this->data['data']['template'])){
            $this->modalID = $this->model->getModalID();
        }
    }
    public function display():string|array {
        if(preg_match('/^modal/i', $this->data['data']['template'])){
            return [
                'modal' => parent::display(),
                'id' => 'modal-' . $this->modalID
            ];
        }else{
            return parent::display();
        }
    }
}
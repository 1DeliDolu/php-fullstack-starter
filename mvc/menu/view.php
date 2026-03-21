<?php
namespace IAD\mvc\menu;

defined('_IAD') or die();

use IAD\mvc\BaseView;
use IAD\mvc\menu\Model as MenuModel;
use IAD\classes\Log;

final class View extends BaseView{
    public function __construct(array &$data = []) {
        parent::__construct($data, __DIR__);
        $this->model = new MenuModel($this->data);
    }
}
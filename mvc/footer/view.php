<?php
namespace IAD\mvc\footer;

defined('_IAD') or die();

use IAD\mvc\BaseView;
use IAD\mvc\footer\Model as FooterModel;
use IAD\classes\Log;

final class View extends BaseView {
    public function __construct(array &$data = []) {
        parent::__construct($data, __DIR__);
        $this->model = new FooterModel($this->data);
    }
}
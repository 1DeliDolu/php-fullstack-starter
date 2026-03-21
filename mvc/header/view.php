<?php
namespace IAD\mvc\header;

defined('_IAD') or die();

use IAD\mvc\BaseView;
use IAD\mvc\header\Model as HeaderModel;
use IAD\classes\Log;

final class View extends BaseView {
    public function __construct(array &$data = []) {
        parent::__construct($data, __DIR__);
        $this->model = new HeaderModel($this->data);
    }
}
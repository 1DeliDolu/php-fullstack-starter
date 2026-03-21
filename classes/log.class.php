<?php
namespace IAD\classes;

defined('_IAD') or die();

use Exception;
final class Log{
    
    public static function add(Exception $e):void {
        if(defined('_AJAX')){
            global $exceptions;
            $exceptions[] = [
                'message'   => $e->getMessage(),
                'code'      => $e->getCode(),
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'trace'     => $e->getTrace(),
                'trace_str' => $e->getTraceAsString(),
                'prev'      => $e->getPrevious()
            ];
        }else{
            var_dump($e);
        }
    }
}
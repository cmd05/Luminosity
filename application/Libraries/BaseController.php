<?php 

declare(strict_types = 1); 

/**
 * Base Controller Abstract Class
 * Method to load Models and Views for all Controllers
 */

abstract class BaseController {
    public function model(string $model) {
        require_once APPROOT.'/Models/'.$model.'.php';

        return new $model;
    }

    public function view(string $view, array $data = []): void {
        $filePath = APPROOT.'/Views/'.$view.'.php';
        file_exists($filePath) ? require_once $filePath : die('View Not Found'); 
    }
}
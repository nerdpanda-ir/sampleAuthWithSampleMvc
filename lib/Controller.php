<?php
class Controller
{
    protected string $baseViewPath;
    public function __construct()
    {
        $this->baseViewPath=dirname(__DIR__).DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR;
    }

    protected function view($view,array $arguments=null)
    {
        if (!is_null($arguments))
            extract($arguments);
        require_once $this->baseViewPath.$view.'.php';
    }
}
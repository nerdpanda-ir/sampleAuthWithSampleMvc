<?php
    if (!isset($_SESSION))
        session_start();
?>
<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'Controller.php'?>
<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'Route.php' ; ?>
<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'middleware'.DIRECTORY_SEPARATOR.'Auth.php' ; ?>
<?php
final class PanelController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Auth::anonymousCheck();
        Auth::userValidate();
        Auth::checkRequestAt();
    }

    public function index():void
    {
        $this->view('panel',['route'=>new Route()]);
    }
}

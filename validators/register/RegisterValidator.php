<?php session_start() ?>
<?php require_once dirname(__DIR__,2).DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'Route.php'?>
<?php require_once 'ExistCheck.php' ?>
<?php require_once 'LengthCheck.php'?>
<?php require_once 'RegexCheck.php'?>
<?php require_once 'UniqueCheck.php'?>
<?php require_once 'ThumbnailCheck.php'?>
<?php
class RegisterValidator
{
    public function __construct()
    {
        //@todo secure inputes
        new ExistCheck();
        new LengthCheck();
        new RegexCheck();
        new UniqueCheck();
        new ThumbnailCheck();
    }
    protected function haveError():bool
    {
        return isset($_SESSION['errors']) and !empty($_SESSION['errors']);
    }
    protected function redirectToRegister(bool $withInputes=false)
    {
        if ($withInputes)
            $_SESSION['values']=$_REQUEST;
        header('Location:'.Route::getFullUri(false));
        exit();
    }
/*    private function redirectWhenErrorExist()
    {
        if ($this->haveError())
            $this->redirectToRegister();
    }*/
}
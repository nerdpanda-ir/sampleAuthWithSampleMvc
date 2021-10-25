<?php require_once __DIR__.DIRECTORY_SEPARATOR.'ExistCheck.php'?>
<?php require_once __DIR__.DIRECTORY_SEPARATOR.'LengthCheck.php' ?>
<?php require_once dirname(__DIR__,2).DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'Route.php'?>
<?php use \validators\login\ExistCheck; ?>
<?php use \validators\login\LengthCheck; ?>

<?php
 class LogInValidator
{
    public function __construct()
    {
        new ExistCheck();
        new LengthCheck();
    }
    protected function redirectToLogin(bool $withInputes=true):void
    {
        if ($withInputes)
            $_SESSION['values']=$_POST;
        Route::redirect(Route::getFullUri(false).'login.php',true);
    }
}
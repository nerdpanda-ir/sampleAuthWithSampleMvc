<?php namespace validators\login; ?>
<?php use LogInValidator; ?>
    <?php require_once 'LogInValidator.php' ; ?>
<?php require_once dirname(__DIR__,2).DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'InputValidate.php' ?>
<?php use InputValidate ;?>
<?php
final class LengthCheck extends LogInValidator
{
    public function __construct()
    {
        $password = InputValidate::getInput('password');
        if (strlen($password)<=7)
            $this->haveBadPassword();
    }
    private function haveBadPassword():void
    {
        InputValidate::setError('password','حداقل پسورد باید بالای 8 کاراکتر باشد !!!');
        $this->redirectToLogin();
    }
}
<?php namespace validators\login; ?>
<?php use LogInValidator; ?>
<?php use InputValidate; ?>
<?php    require_once __DIR__.DIRECTORY_SEPARATOR.'LogInValidator.php' ; ?>
<?php require_once dirname(__DIR__,2).DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'InputValidate.php'?>
<?php
final class ExistCheck extends LogInValidator
{
    public function __construct()
    {
        $results = $this->checkExistResults();
        if (in_array(false,$results))
        {
            $errors= $this->checkExistErrorMessages();
            foreach ($results as $key=>$result)
                if (!$result)
                    InputValidate::setError($key,$errors[$key]);
            $this->redirectToLogin();
        }
    }
    private function checkExistResults():array
    {
        return
            [
                'userId'=>InputValidate::stringValidate('userId') ,
                'password'=>InputValidate::stringValidate('password') ,
            ];
    }
    private function checkExistErrorMessages():array
    {
        return ['userId'=>'وارد کردن نامکاربری الزامی میباشد !!!','password'=>'پلشت !!!‌برای ورود باید حتما پسورتو وارد کنی !!! غاغ !!!'];
    }
}
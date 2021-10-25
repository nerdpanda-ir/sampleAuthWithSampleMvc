<?php require_once dirname(__DIR__,2).DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'InputValidate.php' ?>
<?php require_once __DIR__.DIRECTORY_SEPARATOR.'RegisterValidator.php' ?>
<?php
final class ExistCheck extends RegisterValidator
{
    public function __construct()
    {
        $results=$this->checkFieldResults();
        if (in_array(false,$results))
        {
            $errors = $this->checkFieldErrorMessages();
            foreach ($results as $key=>$result)
                if (!$result)
                    InputValidate::setError($key,$errors[$key]);
            $this->redirectToRegister(true);
        }
    }

    private function checkFieldResults():array
    {
        return [
            'name'=>InputValidate::stringValidate('name'),
            'family'=>InputValidate::stringValidate('family'),
            'userId'=>InputValidate::stringValidate('userId'),
            'email'=>InputValidate::stringValidate('email'),
            'phone'=>InputValidate::stringValidate('phone'),
            'password'=>InputValidate::stringValidate('password'),
        ];
    }
    private function checkFieldErrorMessages():array
    {
        return [
            'name'=>'فیلد نام ضروری میباشد !!!',
            'family'=>'required family field ' ,
            'userId'=>'required userId field ' ,
            'email'=>'required email field ' ,
            'phone'=>'required phone field ' ,
            'password'=>'required password field ' ,
        ];
    }
}
<?php require_once __DIR__.DIRECTORY_SEPARATOR.'RegisterValidator.php' ?>
<?php require_once dirname(__DIR__,2).DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'InputValidate.php' ?>
<?php
class LengthCheck extends RegisterValidator
{
    public function __construct()
    {
        $results = $this->lengthCheckResults();

        if (in_array(false,$results))
        {
            $errors = $this->lengthErrorMessages();
            foreach ($results as $key=>$result)
                if (!$result)
                    InputValidate::setError($key,$errors[$key]);
            $this->redirectToRegister(true);
        }
    }
    private function lengthCheckResults():array
    {
        return[
            'name'=>InputValidate::stringValidateLen('name',32) ,
            'family'=>InputValidate::stringValidateLen('family',32),
            'userId'=> InputValidate::stringValidateLen('userId',60) ,
            'email'=> InputValidate::stringValidateLen('email',128) ,
            'phone'=> strlen(InputValidate::getInput('phone'))==11 ,
            'password'=> strlen(InputValidate::getInput('password'))>=8
        ];
    }
    private function lengthErrorMessages():array
    {
        return[
            'name'=>'name length should less than 32 character ',
            'family'=>'family length should less than 32 character',
            'userId'=> 'user name length should less than 60 character' ,
            'email'=> 'email length should less than 128 character' ,
            'phone'=> 'phone length should equal to 11 character' ,
            'password'=>'password should more than 8 character'
        ];
    }
}
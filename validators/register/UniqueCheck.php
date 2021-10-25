<?php require_once __DIR__.DIRECTORY_SEPARATOR.'RegisterValidator.php' ?>
<?php require_once dirname(__DIR__,2).DIRECTORY_SEPARATOR.'model'.DIRECTORY_SEPARATOR.'User.php'; ?>
<?php require_once dirname(__DIR__,2).DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'InputValidate.php' ?>
<?php
final class UniqueCheck extends RegisterValidator
{
    public function __construct()
    {
        $results = $this->uniqueCheckResults();
        if (in_array(true,$results))
        {
            $errors =$this->uniqueErrorMessages();
            foreach ($results as $key=>$result)
                if ($result===true)
                    InputValidate::setError($key,$errors[$key]);
            $this->redirectToRegister(true);
        }
    }

    private function uniqueCheckResults():array
    {
        $user = new User();
        $uniqueFieldCheckResults = [
            'userId'=>$user->isExistWithUserName(InputValidate::getInput('userId')),
            'email' =>$user->isExistWithEmail(InputValidate::getInput('email')),
            'phone'=>$user->isExistWithPhone(InputValidate::getInput('phone'))
        ];
        return $uniqueFieldCheckResults;
    }
    private function uniqueErrorMessages():array
    {
        return[
            'userId'=> ' this user name used before !!!' ,
            'email'=> 'this email used before !!!' ,
            'phone' => 'this phone used before !!!'
        ];
    }

}
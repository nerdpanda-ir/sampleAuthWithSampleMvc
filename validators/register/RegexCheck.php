<?php require_once __DIR__.DIRECTORY_SEPARATOR.'RegisterValidator.php' ?>
<?php require_once dirname(__DIR__,2).DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'InputValidate.php' ?>
<?php
final class RegexCheck extends RegisterValidator
{
    private string $phoneRegex ='/^((09)\d{9})$/';
    private string $emailRegex = '/^[a-zA-Z][A-Za-z._\-0-9]*@[a-zA-Z]{3,20}\.[a-zA-Z]{2,10}$/';
    public function __construct()
    {
        $results = $this->regexCheckResults();
        if (in_array(false,$results))
        {
            $errors = $this->regexErrorMessages();
            foreach ($results as $key=>$result)
                if (!$result)
                    InputValidate::setError($key,$errors[$key]);
            $this->redirectToRegister(true);
        }
    }
    private function regexCheckResults():array
    {
        return
            [
                'phone'=> InputValidate::inputRegexValidate('phone',$this->phoneRegex),
                'email'=>InputValidate::inputRegexValidate('email',$this->emailRegex)
            ];
    }
    private function regexErrorMessages():array
    {
        return [
            'phone'=>'فورمت موبایل باید این شکلی باشد -> 09014773920' ,
            'email' => 'فورمت ایمیل ارسالی نامعتبر است !!! باید با این فورمت ارسال کنید -> name@host.extension'
        ];
    }
}
<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'Controller.php'?>
<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'Route.php'?>
<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'validators'.DIRECTORY_SEPARATOR.'register'.DIRECTORY_SEPARATOR.'RegisterValidator.php'?>
<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'model'.DIRECTORY_SEPARATOR.'User.php'; ?>
<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'InputValidate.php'?>
<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'FileUploader.php';?>
<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'middleware'.DIRECTORY_SEPARATOR.'Auth.php' ?>
<?php
final class RegisterController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Auth::loginCheck();
        Auth::cookieLogin();
    }

    public function index()
    {
        $this->view('register');
    }
    public function register()
    {
        //@todo secure inputes
        $validator = new RegisterValidator();
        $made = $this->makeUser($this->thumbnailUpload());
        $_SESSION['successfullyRegister']=['status'=>$made,'name'=>InputValidate::getInput('name').' '.InputValidate::getInput('family')];
        Route::redirect(Route::getFullUri(false,false));
    }
    private function makeUser(string $thumbnail=null):bool
    {
        $user= new User('users','id');
        $added = $user->addUser(
            InputValidate::getInput('name') ,
            InputValidate::getInput('family') ,
            InputValidate::getInput('userId') ,
            InputValidate::getInput('email') ,
            InputValidate::getInput('phone') ,
            InputValidate::getInput('password') ,
            $thumbnail ,
        );
        return $added;
    }
    private function thumbnailUpload()
    {
        if (!InputValidate::fileExist('thumbnail'))
            return null;
        $uploader = new FileUploader(InputValidate::getFile('thumbnail'),'media/',true,false);
        $uploader= $uploader->doUpload();
        if ($uploader['moved'])
            return $uploader['uri'];
        return null;
    }
}

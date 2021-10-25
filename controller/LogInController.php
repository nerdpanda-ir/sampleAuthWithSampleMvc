<?php if (!isset($_SESSION)) session_start(); ?>
<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'Controller.php' ?>
<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'validators'.DIRECTORY_SEPARATOR.'login'.DIRECTORY_SEPARATOR.'LogInValidator.php'?>
<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'model'.DIRECTORY_SEPARATOR.'User.php'?>
<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'Route.php'?>
<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'middleware'.DIRECTORY_SEPARATOR.'Auth.php' ?>
<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'login.php'?>
<?php require_once __DIR__.DIRECTORY_SEPARATOR.'LogOutController.php' ?>
<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'model'.DIRECTORY_SEPARATOR.'UserAccessTokens.php'; ?>
<?php
final class LogInController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Auth::cookieLogin();
        Auth::loginCheck();
    }
    public function index()
    {
        $this->view('login');
    }
    public function doLogin()
    {
        new LogInValidator();
        $user = $this->userIsExist();
        $passwordCheck =password_verify(InputValidate::getInput('password'),$user->password);
        if (!$passwordCheck)
            $this->failLogin();
        else
            $this->successLogin($user);
    }
    private function finUser():array
    {
        $user= new User('users','id');
        return $user->getWithUserName(
            InputValidate::getInput('userId'),
            5,
            ['id','password','name','family','thumbnail','user_id','email','phone']
        );
    }
    private function userIsExist()
    {
        $user = $this->finUser();
        if (!empty($user))
            return $user[0];
        $this->failLogin();
    }
    private function failLogin():void
    {
        InputValidate::setError('failLongIn','نام کاربری یا پسورد اشتباه است !!!');
        $_SESSION['values']= $_POST;
        Route::redirect(Route::getFullUri(false).'login.php');
    }
    private function successLogin(object $user):void
    {
        $remember = InputValidate::inputExist('remember');
        if ($remember)
            $this->rememberUser($user->id);
 /*           $this->rememberUser($user->user_id,InputValidate::getInput('password'));*/

        $this->setUserDataToSession($user->name,$user->family,$user->user_id,InputValidate::getInput('password'),$user->email,$user->phone,$user->thumbnail);
        Auth::refreshRequestAt();
        Route::redirect(Route::getFullUri(false).'panel.php');
    }
    private function getCookieExpireDate():int
    {
        return time()+REMEMBER_COOKIE_AGE;
    }
    private function rememberUser(int $userId):void
    {
        $accessToken = new UserAccessTokens('user_access_tokens');
        $expire = new DateTime();
        $expire->setTimestamp($this->getCookieExpireDate());
        $token =$accessToken->addToken($userId,$expire)['token'];
        setcookie('accessToken',$token,$expire->getTimestamp());
    }
 /*   private function rememberUser(string $userName , string $password)
    {
        $expire = $this->getCookieExpireDate();
        setcookie('auth_user_id',$userName,$expire);
        setcookie('auth_user_password',$password,$expire);
    }*/

    private function setUserDataToSession(string $name,string $family,string $userName,string $password,string $email,string $phone,string $thumbnail=null):void
    {
        $_SESSION['user']['name'] = $name.' '.$family;
        if (!is_null($thumbnail))
            $_SESSION['user']['thumbnail'] = Route::getFullUri(false).$thumbnail;
        $_SESSION['user']['user_id']=$userName;
        $_SESSION['user']['password']=$password;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['phone'] = $phone;
    }

}

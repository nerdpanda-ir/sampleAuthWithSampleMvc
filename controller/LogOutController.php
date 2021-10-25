<?php if (!isset($_SESSION)) session_start(); ?>
<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'Controller.php' ?>
<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'middleware'.DIRECTORY_SEPARATOR.'Auth.php';?>
<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'Route.php'?>
<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'login.php'; ?>
<?php
final class LogOutController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Auth::anonymousCheck();
    }
    public function index()
    {
        self::doLogOut();
    }
    public static function doLogOut()
    {
        unset($_SESSION['user']);
        if (self::isAuthCookieExist())
            self::destroyLoginCookie();
        Route::redirect(Route::getFullUri(false).'login.php');
    }
    /*public static function isAuthCookieExist():bool
    {
        return isset($_COOKIE['auth_user_id']) and isset($_COOKIE['auth_user_password']);
    }*/
    public static function isAuthCookieExist():bool
    {
        return isset($_COOKIE['accessToken']);
    }
    public static function destroyLoginCookie():void
    {
        $expire = time()-(time()*2);
        setcookie('accessToken',null,$expire);
    }
}

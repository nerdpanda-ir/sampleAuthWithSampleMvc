<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'Route.php' ?>
<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'login.php';?>
<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'controller'.DIRECTORY_SEPARATOR.'LogOutController.php'; ?>
<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'model'.DIRECTORY_SEPARATOR.'User.php'?>
<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'controller'.DIRECTORY_SEPARATOR.'LogInController.php';?>
<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'model'.DIRECTORY_SEPARATOR.'UserAccessTokens.php' ?>
<?php
class Auth
{
    public static function isAuth():bool
    {
        if (isset($_SESSION['user']))
            return true;
        return false;
    }
    public static function loginCheck()
    {
        if (self::isAuth())
            Route::redirect(Route::getFullUri(false).'panel.php');
    }
    public static function anonymousCheck()
    {
        if (!self::isAuth())
            Route::redirect(Route::getFullUri(false).'login.php');
    }
    public static function refreshRequestAt()
    {
        $_SESSION['user']['requestAt']=time();
    }
    public static function checkRequestAt()
    {
        if (self::isAuth())
        {
            $now = time();
            $lastRequestAt = $_SESSION['user']['requestAt'];
            $diff = $now - $lastRequestAt;
            if ($diff>MAX_AFK_SECONDS)
                self::afkLogOut();
            else
                self::refreshRequestAt();
        }
    }
    private static function afkLogOut():void
    {
        $_SESSION['errors']['afk'] = 'شما بیشتر از '.MAX_AFK_SECONDS.'ثانیه در حالت خلصه بوده اید و توسط سیستم سیکتیر شدید !!!';
        LogOutController::doLogOut();
    }
    public static function userValidate()
    {
        $user = self::searchUserFromSessionInDatabase();
        if (empty($user))
           self::failValidate();
        $user=$user[0];
        /* @todo replace password with access token !! in session !*/
        if (!isset($_SESSION['user']['accessTokenLogin'])) {
            $passwordMatch = password_verify($_SESSION['user']['password'], $user->password);

            if (!$passwordMatch)
                self::failValidate();
            else
                self::updateUserDataFromDatabaseToSession($user->name, $user->family, $user->thumbnail);
        }
    }
    private static function failValidate():void
    {
        $_SESSION['errors']['userFailValidate'] = 'اطلاعات کاربر با دیتابیس همخانی ندارد دوباره لاگین کنید !!!';
        LogOutController::doLogOut();
    }
    private static function searchUserFromSessionInDatabase():array
    {
        $user = new User('users','id');
        return $user->getUser(['name','family','thumbnail','password'],['user_id'=>$_SESSION['user']['user_id'],'email'=>$_SESSION['user']['email'],'phone'=>$_SESSION['user']['phone']]);
    }
    private static function updateUserDataFromDatabaseToSession(string $name,string $family, string $thumbnail=null):void
    {
        $_SESSION['user']['name']=$name.' '.$family;
        if (!is_null($thumbnail))
            $_SESSION['user']['thumbnail'] = Route::getFullUri(false).$thumbnail;
    }
    /*public static function cookieLogin()
    {
        if (LogOutController::isAuthCookieExist())
            self::doCookieLogin();
    }

    private  static function doCookieLogin()
    {
        $where = ['user_id'=>$_COOKIE['auth_user_id']];
        $user = new User();
        $user = $user->getUser(['name','family','email','phone','thumbnail','password','user_id'],$where);
        if (!empty($user))
        {
            $user=$user[0];
            $passwordCheck = password_verify($_COOKIE['auth_user_password'],$user->password);
            if ($passwordCheck)
            {
                $_SESSION['user']['name'] = $user->name.' '.$user->family;
                if (!is_null($user->thumbnail))
                    $_SESSION['user']['thumbnail'] = Route::getFullUri(false).$user->thumbnail;
                $_SESSION['user']['user_id']=$user->user_id;
                $_SESSION['user']['password']=$_COOKIE['auth_user_password'];
                $_SESSION['user']['email'] = $user->email;
                $_SESSION['user']['phone'] = $user->phone;
                Auth::refreshRequestAt();
                Route::redirect(Route::getFullUri(false).'panel.php');
            }
            else
                LogOutController::destroyLoginCookie();
        }
        else
            LogOutController::destroyLoginCookie();
    }*/
    public static function cookieLogin()
    {
        if (LogOutController::isAuthCookieExist())
            self::doCookieLogin();
    }
    public static function doCookieLogin()
    {
        $token = $_COOKIE['accessToken'];
        $tokenIsValid = self::isValidAccessToken($token);
        if (is_object($tokenIsValid))
            self::successTokenValidation($tokenIsValid->user_id);
        else
            self::failTokenValidation();
    }
    public static function isValidAccessToken(string $token)
    {
        $accessTokenModel = new UserAccessTokens('user_access_tokens','id');
        return $accessTokenModel->isValidateToken($token);
    }
    private static function successTokenValidation(string $id):void
    {
        $user = new User('users','id');
        $user = $user->getUser(['name','user_id','family','thumbnail','email','phone'],['id'=>$id],1)[0];
        $_SESSION['user']['name']= $user->name . ' ' . $user->family;
        if (!is_null($user->thumbnail))
            $_SESSION['user']['thumbnail']=Route::getFullUri(false).$user->thumbnail;
        $_SESSION['user']['user_id']=$user->user_id;
        $_SESSION['user']['phone']=$user->phone;
        $_SESSION['user']['email']=$user->email;
        $_SESSION['user']['accessTokenLogin']=true;
        self::refreshRequestAt();
        Route::redirect(Route::getFullUri(false).'panel.php');
        var_dump('successfully validate');
    }
    private static function failTokenValidation():void
    {
        var_dump('fail validate');
        LogOutController::destroyLoginCookie();
    }
}
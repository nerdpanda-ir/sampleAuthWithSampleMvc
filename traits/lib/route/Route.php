<?php namespace traits\lib\route; ?>
<?php
trait Route
{
    public static function getProtocol(): string
    {
        return $_SERVER['REQUEST_SCHEME'];
    }
    public static function getIp():string
    {
        return  $_SERVER['SERVER_ADDR'];
    }
    public static function getHost(): string
    {
        return $_SERVER['HTTP_HOST'];
    }
    public static function getPort():string
    {
        return $_SERVER['SERVER_PORT'];
    }
    public static function getUserIp():string
    {
        return  $_SERVER['REMOTE_ADDR'];
    }
    public static function fileNameRemover(string $value):string
    {
        $fileName = basename($value);
        $value= str_replace($fileName,'',$value);
        return $value;
    }
    public static function getLastChar(string $value):string
    {
        $valueLength = strlen($value);
        return substr($value,$valueLength-1);
    }
    public static function appendSlash(string $value):string
    {
        $lastChar=self::getLastChar($value);
        if ($lastChar!=DIRECTORY_SEPARATOR)
            $value.=DIRECTORY_SEPARATOR;
        return $value;
    }
    public static function removeLastSlash(string $value):string
    {
        $lastChar = self::getLastChar($value);
        if ($lastChar==DIRECTORY_SEPARATOR)
            return substr($value,0,strlen($value)-1);
        return $value;
    }
    public static function getBaseUri(bool $appendSlash=true):string
    {
        $baseUri = self::getProtocol().':'.DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR.self::getHost();
        if (!$appendSlash)
            return $baseUri;
        else
            $baseUri= self::appendSlash($baseUri);
        return $baseUri;
    }
    public static function getRequestUri(bool $withFileName=true,bool $appendSlash=false):string
    {
        $requestUri = $_SERVER['SCRIPT_NAME'];
        if (!$withFileName)
            $requestUri= self::fileNameRemover($requestUri);
        if ($appendSlash)
            $requestUri=self::appendSlash($requestUri);
        else
            $requestUri=self::removeLastSlash($requestUri);
        return $requestUri;
    }
    public static function getFullUri(bool $withFileName=true ,bool $appendSlash=true):string
    {
        $base = self::getBaseUri(false);
        $requestUri = self::getRequestUri($withFileName,$appendSlash);
        $fullUri = $base.$requestUri;
        return $fullUri;
    }
    public static function redirect(string $path,bool $fastRedirect=true):void
    {
        header('Location:'.$path);
        if ($fastRedirect)
            exit();
    }
}

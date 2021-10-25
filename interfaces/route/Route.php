<?php namespace interfaces\route; ?>
<?php

use mysql_xdevapi\SqlStatementResult;

interface Route
{
    public static function getProtocol():string;
    public static function getIp():string;
    public static function getHost():string;
    public static function getPort():string;
    public static function getUserIp():string;
    public static function fileNameRemover(string $value):string;
    public static function getLastChar(string $value):string;
    public static function appendSlash(string $value):string;
    public static function removeLastSlash(string $value):string;
    public static function getBaseUri(bool $appendSlash=true):string;
    public static function getRequestUri(bool $withFileName=true,bool $appendSlash=false):string;
    public static function getFullUri(bool $withFileName=true,bool $appendSlash=false):string;
    public static function redirect(string $path,bool $fastRedirect=true):void;
}
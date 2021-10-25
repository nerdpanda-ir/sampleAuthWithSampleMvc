<?php
final class InputValidate
{
    public static function setError(string $key,$error)
    {
        $_SESSION['errors'][$key] = $error;
    }
    public static function getInput($key)
    {
        return $_REQUEST[$key];
    }
    public static function inputExist($key):bool
    {
        return isset($_REQUEST[$key]);
    }
    public static function stringValidate($key):bool
    {
        return self::inputExist($key) and is_string(self::getInput($key)) and strlen(self::getInput($key));
    }
    public static function stringValidateLen(string $key , int $maxLen):bool
    {
        $input = self::getInput($key);
        return strlen($input)<=$maxLen;
    }
    public static function inputRegexValidate(string $key,string $regex):bool
    {
        $input = self::getInput($key);
        return preg_match($regex,$input);
    }

    private function getFileErrors():array
    {
        return [
            1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            3 => 'The uploaded file was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk.',
            8 => 'A PHP extension stopped the file upload.',
        ];
    }
    public static function getFile(string $key):array
    {
        return $_FILES[$key];
    }
    public static function fileExist(string $key):bool
    {
        return isset($_FILES[$key]) and !empty($_FILES[$key]) and is_array($_FILES[$key]) and $_FILES[$key]['error']!=UPLOAD_ERR_NO_FILE;
    }
    public static function fileErrorExist(string $key):bool
    {
        if ($_FILES[$key]['error']==UPLOAD_ERR_OK)
            return false;
        else
            return true;
    }
    public static function checkFileError(string $key,bool $setError=false):bool
    {
        if (!self::fileErrorExist($key))
            return false;
        if ($setError)
            self::setError($key,self::getFileErrors()[$_FILES[$key]['error']]);
        return true;
    }
    public static function checkFileMime(string $key,array $validMimes , bool $setError=false):bool
    {
        $checkMime = in_array($_FILES[$key]['type'],$validMimes);
        if ($checkMime)
            return true;
        if ($setError)
            self::setError($key,'file mime is not valid !!! valid mimes is '.implode(' | ',$validMimes));
        return false;
    }
    public static function checkFileExtension($key,array $validExtensions,bool $setError=false)
    {
        $extension = pathinfo($_FILES[$key]['name'],PATHINFO_EXTENSION);
        $checkExtension = in_array($extension,$validExtensions);
        if ($checkExtension)
            return true;
        if ($setError)
            self::setError($key,'file extensions is not valid . valid extensions : '.implode(' | ',$validExtensions));
        return  false;
    }
    public static function checkFileSize(string $key,int $maxMgSize, bool $setError = false):bool
    {
        $mgSize = $_FILES[$key]['size'] / pow(1024,2);
        if ($mgSize<=$maxMgSize)
            return true;
        if ($setError)
            self::setError($key,'file size should less than : '.$maxMgSize. 'Mg !!! this file have '.number_format($mgSize,2).'mg !!!');
        return false;
    }
}
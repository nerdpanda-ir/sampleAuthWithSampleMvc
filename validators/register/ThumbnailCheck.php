<?php require_once __DIR__.DIRECTORY_SEPARATOR.'RegisterValidator.php' ?>
<?php require_once dirname(__DIR__,2).DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'InputValidate.php' ?>
<?php require_once dirname(__DIR__,2).DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'register.php'?>
<?php
class ThumbnailCheck extends RegisterValidator
{
    public function __construct()
    {
        $key = 'thumbnail';
        $exist = InputValidate::fileExist($key);
        if ($exist)
        {
            $counter = 0 ;
            $check = false;
            while ($counter<=3)
            {
                switch ($counter)
                {
                    case 0 :
                        $check = !InputValidate::checkFileError($key,true);
                        break;
                    case 1 :
                        $check = InputValidate::checkFileMime($key,THUMBNAIL_VALID_MIMES,true);
                        break;
                    case 2 :
                        $check = InputValidate::checkFileExtension($key,THUMBNAIL_VALID_EXTENSIONS,true);
                        break;
                    case 3:
                        $check = InputValidate::checkFileSize($key,THUMBNAIL_MAX_SIZE,true);
                        break;
                }
                if (!$check)
                    $this->redirectToRegister(true);
                $counter++;
            }
        }
    }

}
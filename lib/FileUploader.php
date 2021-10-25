<?php require_once 'Route.php' ?>
<?php
class FileUploader
{
    private array $file;
    private string $destination;
    private bool $uniqueIdToFileName;
    private bool $makeUniqueDirForFile;
    private int $idLen;
    public function __construct(array $file,string $destination,bool $appendUniqueIdToFileName=false,bool $makeUniqueDirForFile=true,int $uniqueIdLen=12)
    {
        $this->file = $file;
        $this->destination = $destination;
        $this->uniqueIdToFileName = $appendUniqueIdToFileName;
        $this->makeUniqueDirForFile = $makeUniqueDirForFile ;
        $this->idLen = $uniqueIdLen;
        if (!$this->isExistDestination())
            $this->makeDestination();
    }
    public function doUpload():array
    {
        $this->destinationFillerByArguments();
        $moved =  move_uploaded_file($this->file['tmp_name'],$this->destination);
        return ['moved'=>$moved,'uri'=>$this->destination];
    }
    private function isExistDestination():bool
    {
        return (file_exists($this->destination) && is_dir($this->destination));
    }
    private function makeDestination():bool
    {
        return mkdir($this->destination);
    }
    private function hashMaker():string
    {
        $data = microtime();
        $hash = md5($data);
        return substr($hash,0,$this->idLen);
        /*$hash ='';
        $algos = hash_algos();
        $data = microtime();
        foreach ($algos as $algo)
            $hash.=hash($algo,$data);
        var_dump($hash);
        return substr($hash,0,4096);*/
    }
    private function getUniqueDirName():void
    {
        do
        {
            $hash = $this->hashMaker();
            $this->destination = Route::appendSlash($this->destination).$hash;
        }while(is_dir($this->destination));
    }
    private function uniqueDirMaker():array
    {
        $name = $this->getUniqueDirName();
        return ['made'=>mkdir($this->destination),'dirName'=>$name];
    }
    private function getUniqueFileName():void
    {
        do
        {
            $hash = $this->hashMaker();
            $this->destination =Route::appendSlash($this->destination).$hash.'_'.$this->file['name'];
        }while(is_file($this->destination));
    }
    private function destinationFillerByArguments():void
    {
        if ($this->makeUniqueDirForFile and $this->uniqueIdToFileName)
        {
            $this->uniqueDirMaker();
            $this->getUniqueFileName();

        }
        else
            if($this->makeUniqueDirForFile)
            {
                $this->uniqueDirMaker();
                $this->destination = Route::appendSlash($this->destination).$this->file['name'];
            }
            else
                if ($this->uniqueIdToFileName)
                    $this->getUniqueFileName();
                else
                    $this->destination=Route::appendSlash($this->destination).$this->file['name'];
    }
}
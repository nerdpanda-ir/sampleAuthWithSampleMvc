<?php
class TokenGenerator
{
    private string $token;
    private int $length;
    public function __construct(int $length=32)
    {
       $this->setLength($length);
    }
    private function makeToken():void
    {
        $this->token='';
        $data = microtime();
        $algos = hash_algos();
        foreach ($algos as $algo)
            $this->token .= hash($algo,$data);
    }

    private function tokenSlicer():void
    {
        $this->token = substr($this->token,0,$this->length);
    }
    public function getToken():string
    {
        $this->makeToken();
        $this->tokenSlicer();
        return $this->token;
    }
    public function setLength(int $length)
    {
        $this->length = $length;
    }
}
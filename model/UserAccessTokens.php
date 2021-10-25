<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'Model.php'; ?>
<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'TokenGenerator.php' ?>
<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'login.php'?>
<?php
final class UserAccessTokens extends Model
{
    private function selectWithAccessToken(string $accessToken,array $columns=['*']):PDOStatement
    {
        $columns = $this->columnsToString($columns);
        $query ='select '.$columns.' from `'.$this->getTable()."` where `access_token`=?";
        $statement = $this->getConnection()->prepare($query);

        $execute = $statement->execute([$accessToken]);

        return $statement;
    }
    public function getWithAccessToken(string $accessToken,array $columns=['*'],int $fetchMode=5):array
    {
        $statement = $this->selectWithAccessToken($accessToken,$columns);
        return $statement->fetchAll($fetchMode);
    }
    private function makeToken():string
    {
        $tokenMaker = new TokenGenerator(MAX_ACCESS_TOKEN_LENGTH);
        do
        {
            $token = $tokenMaker->getToken();
        }while($this->existToken($token));
        return $token;
    }
    public function existToken(string $accessToken):bool
    {
        $find = $this->selectWithAccessToken($accessToken,['id']);
        return $find->rowCount()==1;
    }
    private function insertToken(int $userId,string $expiredAt):array
    {
        $token = $this->makeToken();
        $query = 'insert into `'.$this->getTable().'`(`access_token`,`user_id`,`expired_at`) value("'.$token.'", "'.$userId.'" , "'.$expiredAt.'")';
        $execute = $this->getConnection()->exec($query);
       return ['executed'=>$execute,'token'=>$token];
    }
    public function addToken(int $userId,DateTime $expiredAt):array
    {
        $expiredAt = $expiredAt->format('Y:m:d H:i:s');
        return $this->insertToken($userId,$expiredAt);
    }

    private function selectValidateToken(string $token):PDOStatement
    {
        $query = 'select `user_id` from `'.$this->getTable().'` where `access_token`=? and current_timestamp<= `expired_at` limit 0,1';
        $statmenet = $this->getConnection()->prepare($query);
        $statmenet->execute([$token]);
        return $statmenet;
    }
    public function isValidateToken(string $token)
    {
        // is exist , is not expired -> (now <= expire expire)!!!
        $statement = $this->selectValidateToken($token);
        return $statement->fetch($this->getConnection()::FETCH_OBJ);
    }
}
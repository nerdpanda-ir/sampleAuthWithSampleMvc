<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'Model.php';?>
<?php
class User extends Model
{
    private function selectWithUserName(string $userName, array $columns=['*']):PDOStatement
    {
        $columns= implode(',',$columns);
        $query = 'select '.$columns.' from `'.$this->getTable().'` where user_id=:value';
        $statement =$this->getConnection()->prepare($query);

        $statement->bindValue(':value',$userName);

        $execute = $statement->execute();

        return $statement;
    }
    public function getWithUserName(string $userName, int $fetchMode= 5,array $columns=['*']):array
    {
        $userStatement= $this->selectWithUserName( $userName,$columns);
        return $userStatement->fetchAll($fetchMode);
    }

    public function isExistWithUserName(string $userName):bool
    {
        $userStatement = $this->selectWithUserName($userName,[$this->getPrimaryKeyColumn()]);
        return $userStatement->rowCount()>=1;
    }


    private function selectWithEmail(string $email,array $columns=['*']):PDOStatement
    {
        $columns = implode(',',$columns);

        $query = 'select '.$columns.' from '.$this->getTable().' where email=?';
        $statement=$this->getConnection()->prepare($query);

        $statement->bindColumn('columns',$columns);
        $statement->bindValue(1,$email);

        $execute = $statement->execute();

        return $statement;
    }

    public function getWithEmail(string $email, int $fetchMode= 5 , array $columns=['*']):array
    {
        $userStatement = $this->selectWithEmail($email,$columns);
        return $userStatement->fetchAll($fetchMode);
    }
    public function isExistWithEmail(string $email):bool
    {
        $userStatement = $this->selectWithEmail($email,[$this->getPrimaryKeyColumn()]);
        return $userStatement->rowCount()>=1;
    }

    private function selectWithPhone(string $phone,array $columns=['']):PDOStatement
    {
        $columns= implode(',',$columns);

        $query = 'select '.$columns.' from '.$this->getTable().' where phone=?';

        $statement = $this->getConnection()->prepare($query);

        $statement->bindColumn('columns',$columns);
        $statement->bindValue(1,$phone);

        $execute = $statement->execute();

        return $statement;
    }

    public function getWithPhone(string $phone,int $fetchMode= 5 , array $columns=['*']):array
    {
        $userStatement = $this->selectWithPhone($phone,$columns);

        return $userStatement->fetchAll($fetchMode);
    }

    public function isExistWithPhone(string $phone):bool
    {
        $userStatement = $this->selectWithPhone($phone,[$this->getPrimaryKeyColumn()]);
        return $userStatement->rowCount()>=1;
    }

    private function insertUser(string $name,string $family,string $userName,string $email , string $phone,string $password,string $thumbnail =null):PDOStatement
    {
        $arguments = array_slice(get_defined_vars(),0,func_num_args());
        $query =
            '
                insert into `users`
                (
                    `name`,
                    `family`,
                    `user_id`,
                    `email`,
                    `phone`,
                    `password`,
                    `thumbnail`
                 )
                 values
                (
                 :name,
                 :family,
                 :userName ,
                 :email ,
                 :phone,
                 :password ,
                 :thumbnail
                );
            ';

            $statement = $this->getConnection()->prepare($query);

            foreach ($arguments as $key=>$argument)
                $statement->bindValue(':'.$key,$argument);
            $execute = $statement->execute();

            return $statement;
    }

    public function addUser(string $name,string $family,string $userName,string $email , string $phone,string $password,string $thumbnail =null):bool
    {
        $password=password_hash($password,PASSWORD_DEFAULT);
        $userStatement = $this->insertUser($name,$family,$userName,$email,$phone,$password,$thumbnail);

        return $userStatement->rowCount()===1;
    }

    private function selectUser(array $columns=['*'],array $where=null,int $operator = 1 ):PDOStatement
    {
        $columns = $this->columnsToString($columns);
        $query = 'select '.$columns.' from `'.$this->getTable().'` where '.$this->whereGenerator($where,$operator);

        $statement = $this->getConnection()->prepare($query);

        foreach ($where as $key=>$value)
            $statement->bindValue(':'.$key,$value);
        $execute = $statement->execute();

        return $statement;
    }

    public function getUser(array $columns=['*'],array $where=null,int $operator = 1 ,int $fetchMode=5)
    {
        $userStatement  = $this->selectUser($columns,$where,$operator);
        return $userStatement->fetchAll($fetchMode);
    }

    private function whereGenerator(array $where=null,int $operator=1):string
    {
        $result='';
        if (is_null($where)||empty($where))
            return $result;
        $keys = array_keys($where);
        foreach ($keys as &$key)
            $key.='=:'.$key;
        return  implode($this->operatorDetector($operator),$keys);
    }
    private function operatorDetector(int $operator):string
    {
        switch ($operator)
        {
            default :
            case 1 :
                return ' and ';
            break;
            case 2:
                return 'or';
            break;
        }
    }
}

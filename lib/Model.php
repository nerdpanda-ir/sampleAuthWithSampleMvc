<?php require_once __DIR__.DIRECTORY_SEPARATOR.'DatabaseConnection.php' ?>
<?php
class Model
{
    private PDO $connection;
    private string $table;
    private string $primaryKeyColumn;
    public function __construct(string $table=null,string $primaryKeyColumn=null)
    {
        $this->connection = DatabaseConnection::getConnection();
        $this->initializeTable($table);
        $this->initializePrimaryKeyColumn($primaryKeyColumn);
    }
    private function initializeTable(string $table=null):void
    {
        if (is_string($table) and strlen($table)>=1)
            $this->table=$table;
        else
            $this->table=lcfirst(get_class($this)).'s';
    }
    private function initializePrimaryKeyColumn(string $primaryKeyColumn=null)
    {
        if (is_string($primaryKeyColumn) and strlen($primaryKeyColumn)>=1)
            $this->primaryKeyColumn=$primaryKeyColumn;
        else
            if (is_null($primaryKeyColumn) or strlen($primaryKeyColumn)==0)
            {
                $query = 'show keys from '.$this->table.' where `Key_name`=\'PRIMARY\'';
                $statement = $this->connection->query($query);
                $result =$statement->fetch($this->connection::FETCH_OBJ);
                $this->primaryKeyColumn =$result->Column_name;

            }
    }
    public function getConnection():PDO
    {
        return $this->connection;
    }
    public function getTable():string
    {
        return $this->table;
    }
    public function getPrimaryKeyColumn():string
    {
        return  $this->primaryKeyColumn;
    }
    protected function columnsToString(array $columns):string
    {
        return  implode(',',$columns);
    }
}
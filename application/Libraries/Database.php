<?php 

declare(strict_types = 1); 

    /* PDO DATABASE CLASS
    * Connect to Database
    * Prepared Statement
    * Bind Values
    * Return Values
    */

class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $stmt;
    private $error;

    public function __construct() {
        $dsn = 'mysql:host='.$this->host.';dbname='.$this->dbname;
        $opts = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false
        ];

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $opts);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            die($this->error);
        }
    }

    public function dbInsert(string $table, array $array): bool {
        $sql = $this->constructInsert($table, $array);
        $this->query($sql);

        foreach ($array as $key => $value) {
            $this->bind(":$key", $value);
        }

        return ($this->execute()) ? true : false;
    }

    public function constructInsert(string $table, array $array): string {
        $sql = "INSERT INTO $table(";
        $i = 0;
        $arrCount = count($array);
        foreach ($array as $key => $value) {
            $sql .= $key;
            if($i != $arrCount - 1) $sql .= ', '; // If not last index add comma
            $i++;
        }
        $sql .= ") VALUES(";
        $j = 0;
        foreach ($array as $key => $value) {
            $sql .= ":".$key;
            if($j != $arrCount - 1) $sql .= ', '; // If not last index add comma
            $j++;
        }
        $sql .= ')';
        return $sql;
    }
    
    // Querying with prepared stmts
    public function query(string $sql): void {
        $this->stmt = $this->dbh->prepare($sql);
    }

    public function bind($params, $value, $type = null): void {
        if(is_null($type)) {
            switch(true) {
                case is_int($value):
                    $type = PDO::PARAM_INT; 
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL; 
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL; 
                    break;
                default:
                    $type = PDO::PARAM_STR; 
            }
        }

        $this->stmt->bindValue($params, $value, $type);
    }

    public function bindMultiple(array $arr): void {
        foreach ($arr as $key => $value) 
            $this->bind(":$key", $value);
    }

    // Execute prepared statement
    public function execute() {
        return $this->stmt->execute();
    }

    // Get result set as array of objects
    public function fetchRows() {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    // Get single record as object
    public function fetchRow() {
        $this->execute();
        return $this->stmt->fetch();
    }

    // Get Row Count
    public function rowCount(): int {
        return $this->stmt->rowCount();
    }

    // get last insert id of database table
    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }
}
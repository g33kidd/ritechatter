<?php

class DB extends PDO {
    
    public function __construct() {
        $dsn = "mysql:dbname=ritechat_users;host=ritechatter.com";
    	try {
			parent::__construct($dsn, "ritechat", "JoshuaK123!");
		}catch(PDOException $e){
			throw new PDOException($e);
		}
    }
    
    public static function add($table, $data) {
        global $db;
        
        if(!is_array($data) || !count($data)) return false;
        
        try{
            $bind = ':'.implode(',:', array_keys($data));
            $sql = "INSERT INTO {$table} (".implode(',', array_keys($data)).")
                    VALUES (".$bind.") ";
            $stmt = $db->prepare($sql);
            $stmt->execute(array_combine(explode(',', $bind), array_values($data)));
        }catch(PDOException $e){
            return $e->getMessage();
        }
    }
    
        
    public static function update($table, $data) {
        global $db;
        
        if(!is_array($data) || !count($data)) return false;
        
        try{
            //do this later...
        }catch(PDOException $e){
            return $e->getMessage();
        }
    }
    
    public static function del($table, $field, $value){
        global $db;
        
        try{
            $sql = "DELETE FROM {$table}
                    WHERE {$field}='{$value}'";
            $stmt = $db->prepare($sql);
            $stmt->execute();
        }catch(PDOException $e){
            return $e->getMessage();
        }
    }
    
}

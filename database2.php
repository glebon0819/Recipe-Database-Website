<?php
class Database1
{
    private static $dbName = 'benrud_tech_club' ;
    private static $dbHost = 'localhost' ;
    private static $dbUsername = 'benrud_tech_club';
    private static $dbUserPassword = 'frostByte12!';
    private static $cont  = null;
     
    public function __construct1() {
        die('Init function is not allowed');
    }
     
    public static function connect1()
    {
       // One connection through whole application
       if ( null == self::$cont )
       {     
        try
        {
          self::$cont =  new PDO( "mysql:host=".self::$dbHost.";"."dbname=".self::$dbName, self::$dbUsername, self::$dbUserPassword); 
        }
        catch(PDOException $e)
        {
          die($e->getMessage()); 
        }
       }
       return self::$cont;
    }
     
    public static function disconnect1()
    {
        self::$cont = null;
    }
}
?>
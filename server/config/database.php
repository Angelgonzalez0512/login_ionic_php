<?php 

class Conex
{ 
    public $db;      
    private static $dns = "mysql:host=bzax6oniku4qckdjsomz-mysql.services.clever-cloud.com;dbname=bzax6oniku4qckdjsomz"; 
    private static $user = "ufcsa8vyrdmqc19a"; 
    private static $pass = "JKEcuE55dYtK8EaI3IMj";     
    private static $instance;

    public function __construct ()  
    {        
       $this->db = new PDO(self::$dns,self::$user,self::$pass,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));       
    } 

    public static function getInstance()
    { 
        if(!isset(self::$instance)) 
        { 
            $object= __CLASS__; 
            self::$instance=new $object; 
        } 
        return self::$instance; 
    }    
} 

?>
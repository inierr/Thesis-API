<?php

/**
 * User object has following functions:
 * 1. loginValidate()
 * 2. loginUser()
 */
class User{

    //database local variable
    private $conn;
    private $table_name = "user_table";

    public $id;
    public $username;
    public $name;
    public $password;
    public $hashed_password;
    /**
     * User Constructor
     */
    public function __construct($db){
        $this->conn = $db;
    }
    /**
     * function to validate username and password
     */
    function loginValidate(){
        if(strlen($this->username) > 4 && strlen($this->username) < 50 && strlen($this->password) > 4 && strlen($this->password) < 50 && ctype_alnum($this->username)){
            return true;
        }else{
            return false;
        }
    }
    function loginUser(){
        try{
            //sql to get id and username with object username and password
            $sql = "SELECT id, username FROM ".$this->table_name." WHERE username=:username AND password=:password";

            //prepare statement
            $stmt = $this->conn->prepare($sql);
            
            //bind parameters to prepared statement
            $stmt->bindParam(":username", $this->username);
            $stmt->bindParam(":password", $this->hashed_password);

            //execute prepared statement and return stmt
            $stmt->execute();
            return $stmt;

        }catch(PDOEXCEPTION $e){
            echo $e->getMessage();
        }
    }
}

/**
 * Activity object has the following functions:
 * 1. getUserActivity()
 */
class Activity{
    //database local variable
    private $conn;
    private $table_name = "activity";
    
    //object properties
    public $id;
    public $user_activity;
    public $user_id;
    public $user_username;
    public $date_time;

    /**
     * Activity Constructor
     */
    public function __construct($db){
        $this->conn = $db;
    }

    /**
     * function to get user activity without pagination
     */
    function getUserActivity(){
        try{
            //sql to get id, user_activity, user_id, user_username, date_time FROM ACTIVITY
            $sql = "SELECT id, user_activity, user_id, user_username, date_time FROM ".$this->table_name." ORDER BY date_time DESC";
            
            //query sql and return statement
            $stmt = $this->conn->query($sql);
            return $stmt;
        }catch(Exception $e){
            
        }
    }

    /**
     * function to save user activity
     */
    function saveActivity(){
        //query insert record
        $query = "INSERT INTO ".$this->table_name." SET user_activity=:user_activity, user_id=:user_id, user_username=:user_username, date_time=:date_time";

        //prepare query
        $stmt = $this->conn->prepare($query);

        //sanitize
        $this->user_activity=htmlspecialchars(strip_tags($this->user_activity));
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $this->user_username=htmlspecialchars(strip_tags($this->user_username));
        $this->date_time=htmlspecialchars(strip_tags($this->date_time));

        //bind to prepare stmt
        $stmt->bindParam(":user_activity", $this->user_activity);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":user_username", $this->user_username);
        $stmt->bindParam(":date_time", $this->date_time);

        try{
            //execute stmt
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }catch(Exception $e){
            return false;
        }
    }
}

/**
 * DailyConsumed object has the following functions: 
 * 1. getDailyConsumed()
 */
class DailyConsumed{
    //database local variable
    private $conn;
    private $table_name = "power_daily";

    //object properties
    public $id;
    public $socket_id;
    public $watt_cons;
    public $date;

    /**
     * DailyConsumed Constructor
     */
    public function __construct($db){
        $this->conn = $db;
    }

    /**
     * function to get power consumed of $socket_id
     */
    function getDailyConsumed(){
        try{
            
            //sql to get id, user_activity, user_id, user_username, date_time FROM ACTIVITY
            $sql = "SELECT id, socket_id, watt_cons, date FROM ".$this->table_name." WHERE socket_id=".$this->socket_id." ORDER BY date DESC";
            
            //query sql and return statement
            $stmt = $this->conn->query($sql);

            return $stmt;
        }catch(Exception $e){
            return null;
        }
    }
}

/**
 * WeeklyConsumed object has the following functions: 
 * 1. getWeeklyConsumed()
 */
class WeeklyConsumed{
    //database local variable
    private $conn;
    private $table_name = "power_weekly";

    //object properties
    public $id;
    public $socket_id;
    public $watt_cons;
    public $date_from;
    public $date_to;
    public $week_number;

    /**
     * WeeklyConsumed Constructor
     */
    public function __construct($db){
        $this->conn = $db;
    }

    /**
     * function to get power consumed of $socket_id
     */
    function getWeeklyConsumed(){
        try{
            
            //sql to get id, user_activity, user_id, user_username, date_time FROM ACTIVITY
            $sql = "SELECT id, socket_id, watt_cons, date_from, date_to, week_number FROM ".$this->table_name." WHERE socket_id=".$this->socket_id." ORDER BY week_number DESC";
            
            //query sql and return statement
            $stmt = $this->conn->query($sql);

            return $stmt;
        }catch(Exception $e){
            return null;
        }
    }
}


/**
 * WeeklyConsumed object has the following functions: 
 * 1. 
 */
class Socket{
    //database local variable
    private $conn;
    private $table_name = "socket";

    //object properties
    public $id;
    public $socket_id;
    public $socket_name;
    public $socket_description;
    public $socket_pin;
    public $socket_read;
    public $socket_switch;

    /**
     * private variables for socket
     */
    private $socket = array(1, 4, 5, 7 ,0 , 2);

    /**
     * function to read a single socket info
     */
    function readSocketInfo(){
        $pin = $this->socket_id;
        $status;
        switch($pin){
            case "1":
                // exec("gpio read ".$this->socket[0], $gpiostatus);
                $gpiostatus = '1';
                $status = $gpiostatus;
                break;
            case "2":
                // exec("gpio read ".$this->socket[1], $status);
                $gpiostatus = '1';
                $status = $gpiostatus;
                break;
            case "3":
                // exec("gpio read ".$this->socket[2], $status);
                $gpiostatus = '1';
                $status = $gpiostatus;
                break;
            case "4":
                // exec("gpio read ".$this->socket[3], $status);
                $gpiostatus = '0';
                $status = $gpiostatus;
                break;
            case "5":
                // exec("gpio read ".$this->socket[4], $status);
                $gpiostatus = '0';
                $status = $gpiostatus;
                break;
            case "6":
                // exec("gpio read ".$this->socket[5], $status);
                $gpiostatus = '0';
                $status = $gpiostatus;
                break;
            default:
                $status = -1;
        }
        if($status[0] == "1"){
            return "off";
        }elseif($status[0] == "0"){
            return "on";
        }else{
            return "ERROR";
        }
    }

    /**
     * function to read state to on/off
     */
    function stateOnOffSocket(){
        $state = $this->socket_switch;
        $pin = $this->socket_id;
        $status;
        switch($pin){
            case "1":
                $msg = $this->socketOnOff($this->socket1, $state);
                $status = $msg;
                break;
            case "2":
                $msg = $this->socketOnOff($this->socket2, $state);
                $status = $msg;
                break;
            case "3":
                $msg = $this->socketOnOff($this->socket3, $state);
                $status = $msg;
                break;
            case "4":
                $msg = $this->socketOnOff($this->socket4, $state);
                $status = $msg;
                break;
            case "5":
                $msg = $this->socketOnOff($this->socket5, $state);
                $status = $msg;
                break;
            case "6":
                $msg = $this->socketOnOff($this->socket6, $state);
                $status = $msg;
                break;
            default:
                $status = "ERROR";
        }
        return $status;
    }
    /**
     * function to turn on/off the socket
     */
    function socketOnOff($pin, $state){
        $status;
        if($state == "on"){
            // system("gpio mode ".$pin." out");
            // system("gpio write ". $pin . " 0");
            $status = "Turned on socket " .$pin;
        }else{
            // system("gpio mode ".$pin." out");
            // system("gpio write ". $pin . " 1");
            $status = "Turned off socket " .$pin;
        }
        return $status;
    }
}
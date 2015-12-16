<?php
/**
 * Created by IntelliJ IDEA.
 * User: sohaib
 * Date: 10/10/15
 * Time: 9:54 AM
 */

class OurChatConnection {

    private static $hostname = "127.0.0.1";

    private static $username = "root", $password = "mysqlpass", $database = "ourchat";

    private static $conn = NULL;

    function __construct(){
        $this->connect();
    }

    /**
     * @return bool
     */
    private static function connect(){
        if(self::$conn == NULL || self::$conn == FALSE){
            self::$conn = (new mysqli( self::$hostname, self::$username, self::$password, self::$database));
            /* check connection */
            if (mysqli_connect_errno()) {
                syslog(LOG_PID | LOG_PERROR, "Connect failed: " . mysqli_connect_error());
                return FALSE;
            }
        }
        return TRUE;
    }

    public function escape($string){
        if(self::$conn == FALSE && connect() == FALSE){
            return FALSE;
        };
        return self::$conn->escape_string($string);
    }

    function __destruct(){
        self::$conn->close();
    }


    public function query($querystring){
        if(self::$conn == FALSE && connect() == FALSE){
            return FALSE;
        };
        $result = self::$conn->query($querystring);
        $results = array();
        //syslog(LOG_ERR, $querystring);
        if($result == FALSE){
            return FALSE;
        }else{
            if($result===true){
                //insert or delete query
                return $result;
            }else{
                while($row = $result->fetch_assoc()){
                    $results[] = $row;
                }
            }
        }
        return $results;
    }

}

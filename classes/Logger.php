<?php
/**
 * Created by IntelliJ IDEA.
 * User: sohaib
 * Date: 15/12/15
 * Time: 6:56 PM
 */

class Logger {

    private static $log_file = "/Users/sohaib/temp/our_chat.log";

    private static $file = NULL;

    function __construct(){
        $this->init();
    }

    /**
     * @return bool
     */
    private static function init(){
        if(self::$file == NULL || self::$file == FALSE){
            self::$file = fopen(self::$log_file, "a");
            /* check connection */
            if (!self::$file) {
                syslog(LOG_PID | LOG_PERROR, "Open file failed: " . self::$log_file);
                return FALSE;
            }
        }
        return TRUE;
    }

    public function log($message) {
        if(self::$file == FALSE && $this->init() == FALSE){
            return FALSE;
        };
        fwrite(self::$file, $message . "\n");
    }

    public function __destruct() {
        if(self::$file) fclose(self::$file);
    }

}

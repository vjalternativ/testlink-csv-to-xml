<?php
class lib_logger {
    private $logFile;
    private $threadId=false;
    private static $instance = null;
    function __construct($logfile=false) {
            $this->logFile = $logfile ? $logfile : "logger.log";
            $this->threadId = uniqid();
    }

    static function getInstace() {
        if(self::$instance==null) {
            self::$instance = new lib_logger();
        }
        return self::$instance;
    }

    private function log($type,$message) {
        $date = date("Y-m-d");
        $logmessage = date("Y-m-d H:i:s").' : ';
        if($this->threadId) {
            $logmessage .= $this->threadId." ";
        }
        $logmessage .= $type.' : '.$message.PHP_EOL;
        $dir = __DIR__.'/../';
        $path = $dir."logs/".$date.'/';


        if(!is_dir($path)) {
            $cmd ='mkdir -p '.$path;
            shell_exec($cmd);
        }
        $path .= $this->logFile;

        echo $logmessage;
        error_log($logmessage,3,$path);
    }

    function error($message) {
        $this->log("ERROR",$message);
    }
    function warn($message) {
        $this->log("WARN",$message);
    }
    function debug($message) {
        $this->log("DEBUG",$message);
    }
    function info($message) {
        $this->log("INFO",$message);
    }


}
?>
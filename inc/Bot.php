<?php

require_once dirname(dirname(__FILE__))."/Snoopy/Snoopy.class.php";

class Bot
{
    private static $instance;
    public $snoopy;

    public static function newInstance()
    {
        if( !self::$instance instanceof self ) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    function __construct()
    {
        $this->snoopy = new Snoopy;
        $this->snoopy->agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)";
        $this->snoopy->rawheaders["Pragma"] = "no-cache";
        $this->snoopy->maxredirs = 3;
        $this->snoopy->offsiteok = false;
        $this->snoopy->expandlinks = false;
    }

}
?>
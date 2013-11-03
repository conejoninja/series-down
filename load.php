<?php


define('BASE_PATH', dirname(__FILE__)."/");

require_once BASE_PATH."config.php";

require_once BASE_PATH.'inc/database/DBConnectionClass.php';
require_once BASE_PATH.'inc/database/DBCommandClass.php';
require_once BASE_PATH.'inc/database/DBRecordsetClass.php';
require_once BASE_PATH.'inc/database/DAO.php';
require_once BASE_PATH.'inc/database/Log.php';
require_once BASE_PATH.'inc/utils.php';

require_once BASE_PATH."inc/Media.php";
require_once BASE_PATH."inc/Episode.php";
require_once BASE_PATH."inc/Bot.php";

?>
<?php
/* -----------------------------------------------------------------------------------------
   $Id: xtc_db_connect_installer.inc.php 4200 2013-01-10 19:47:11Z Tomcraft1980 $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(database.php,v 1.2 2002/03/02); www.oscommerce.com
   (c) 2003 nextcommerce (xtc_db_connect_installer.inc.php,v 1.3 2003/08/13); www.nextcommerce.org
   (c) 2006 XT-Commerce (xtc_db_connect_installer.inc.php 899 2005-04-29)

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

function xtc_db_connect_installer($server, $username, $password, $link = 'db_link') {
  global $db_error, $db_connections;

  $db_error = false;

  if (!$server) {
    $db_error = 'No Server selected.';
    return false;
  }

  // set charset defined in configure.php
  if(!defined('DB_SERVER_CHARSET')) {
    define('DB_SERVER_CHARSET','latin1');
  }
  $collation = DB_SERVER_CHARSET == 'utf8' ? 'utf8_general_ci' : 'latin1_german1_ci';

  $configParams = array(
    'host' => $server,
    'user' => $username,
    'password' => $password,
    'dbname' => null,
    'driver' => 'pdo_mysql',
    'charset' => 'latin1',
    'driverOptions' => array(
      \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES ".DB_SERVER_CHARSET." COLLATE ".$collation
    )
  );

  try {
    $config = new \Doctrine\DBAL\Configuration();
    $conn = \Doctrine\DBAL\DriverManager::getConnection($configParams, $config);
  }
  catch(\Doctrine\DBAL\DBALException $e){
    xtc_db_error('', '', $e->getMessage());
    die();
  }

  // save the connection in a global var
  $db_connections[$link] = $conn;

  return $conn;
}
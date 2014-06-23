<?php
/* -----------------------------------------------------------------------------------------
   $Id: xtc_db_test_create_db_permission.inc.php 899 2005-04-29 02:40:57Z hhgag $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(database.php,v 1.2 2002/03/02); www.oscommerce.com 
   (c) 2003	 nextcommerce (xtc_db_test_create_db_permission.inc.php,v 1.3 2003/08/13); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
function xtc_db_test_create_db_permission($database) {
  global $db_error;
  $conn = xtc_db_get_conn();

  $db_error = false;

  if (!$database) {
    $db_error = 'No Database selected.';
    return false;
  }

  $sm = $conn->getSchemaManager();
  $databases = $sm->listDatabases();
  if (!in_array($database, $databases)) {
    try {
      xtc_db_query_installer('create database ' . $database);
      xtc_db_select_db($database);
    }
    catch(\Doctrine\DBAL\DBALException $e){
      $db_error = $e->getMessage();
      return false;
    }
  }

  try {
    $sql = 'ALTER DATABASE '.$database.' DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;';
    xtc_db_query_installer($sql);
    $sql = 'SET NAMES utf8 COLLATE utf8_general_ci;';
    xtc_db_query_installer($sql);
  }
  catch(\Doctrine\DBAL\DBALException $e){
    $db_error = $e->getMessage();
    return false;
  }

  try {
    xtc_db_query_installer('create table temp ( temp_id int(5) )');
    xtc_db_query_installer('drop table temp');
  }
  catch(\Doctrine\DBAL\DBALException $e){
    $db_error = $e->getMessage();
    return false;
  }

  try {
    xtc_db_query_installer('drop database ' . $database);
  }
  catch(\Doctrine\DBAL\DBALException $e){
    $db_error = $e->getMessage();
    return false;
  }

  return true;
}
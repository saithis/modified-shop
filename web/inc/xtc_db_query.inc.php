<?php
/* -----------------------------------------------------------------------------------------
   $Id: xtc_db_query.inc.php 1195 2005-08-28 21:10:52Z mz $

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(database.php,v 1.19 2003/03/22); www.oscommerce.com
   (c) 2003	 nextcommerce (xtc_db_query.inc.php,v 1.4 2003/08/13); www.nextcommerce.org

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

/**
 * @param $query
 * @param string $link
 * @return \Doctrine\DBAL\Driver\Statement
 */
function xtc_db_query($query, $link = 'db_link') {
  global $db_last_error;
  $db_last_error = false;

  $conn = xtc_db_get_conn($link);

  if (defined('STORE_DB_TRANSACTIONS') && STORE_DB_TRANSACTIONS == 'true') {
    error_log('QUERY ' . $query . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
  }

  $result = false;
  try {
    $result = $conn->executeQuery($query);
  }
  catch(\Doctrine\DBAL\DBALException $e){
    $db_last_error = $e->getMessage();
  }

  if (defined('STORE_DB_TRANSACTIONS') && STORE_DB_TRANSACTIONS == 'true') {
    if(defined('_VALID_XTC')){
      if($db_last_error) error_log('ERROR  ' . $e->getMessage() . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }
    else {
      error_log('RESULT ' . $result . ' ' . $e->getMessage() . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }
  }

  return $result;
}
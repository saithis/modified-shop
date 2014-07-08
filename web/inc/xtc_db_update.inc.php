<?php
/* -----------------------------------------------------------------------------------------
   $Id: xtc_db_update.inc.php 4200 2014-06-21 19:47:11Z saithis $

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(database.php,v 1.19 2003/03/22); www.oscommerce.com
   (c) 2003	 nextcommerce (xtc_db_input.inc.php,v 1.3 2003/08/13); www.nextcommerce.org

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

/**
 * @param $query
 * @param string $link
 * @return int Number of affected rows
 */
function xtc_db_update($query, $link = 'db_link') {
  $conn = xtc_db_get_conn($link);

  $result = $conn->executeUpdate($query);

  if (defined('STORE_DB_TRANSACTIONS') && STORE_DB_TRANSACTIONS == 'true') {
    error_log('QUERY ' . $query . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    $result_error = print_r($conn->errorInfo(), true);
    error_log('RESULT ' . $result . ' ' . $result_error . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
  }

  return $result;
}
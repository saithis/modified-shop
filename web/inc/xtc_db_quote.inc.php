<?php
/* -----------------------------------------------------------------------------------------
   $Id: xtc_db_quote.inc.php 4200 2014-06-21 19:47:11Z saithis $

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
 * @param $string
 * @param string $link
 * @return string Escaped variable (with quoted if necessary)
 */
function xtc_db_quote($string, $link = 'db_link') {
  if($string === NULL){
    return 'NULL';
  }
  $conn = xtc_db_get_conn($link);
  return $conn->quote($string);
}
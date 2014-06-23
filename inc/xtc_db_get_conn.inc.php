<?php
/* -----------------------------------------------------------------------------------------
   $Id: xtc_db_get_conn.inc.php 4200 2014-06-21 19:47:11Z saithis $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(database.php,v 1.19 2003/03/22); www.oscommerce.com
   (c) 2003 nextcommerce (xtc_db_connect.inc.php,v 1.3 2003/08/13); www.nextcommerce.org
   (c) 2006 XT-Commerce (xtc_db_connect.inc.php 1248 2005-09-27)

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

/**
 * @param string $link
 * @return \Doctrine\DBAL\Connection|null
 */
function xtc_db_get_conn($link = 'db_link'){
  global $db_connections;

  if(isset($db_connections[$link]) && $db_connections[$link] instanceof \Doctrine\DBAL\Connection){
    return $db_connections[$link];
  }

  return null;
}
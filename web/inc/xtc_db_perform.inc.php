<?php
/* -----------------------------------------------------------------------------------------
   $Id: xtc_db_perform.inc.php 899 2005-04-29 02:40:57Z hhgag $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(database.php,v 1.19 2003/03/22); www.oscommerce.com 
   (c) 2003	 nextcommerce (xtc_db_perform.inc.php,v 1.3 2003/08/13); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

/**
 * @param $table
 * @param $data
 * @param string $mode
 * @param bool $where
 * @param string $link
 * @return int number of affected rows
 */
function xtc_db_perform($table, $record, $mode = 'insert', $where = false, $link = 'db_link') {
  if(empty($record)){
    return 0;
  }

  $mode = strtoupper($mode);
  $conn = xtc_db_get_conn($link);
  reset($record);

  if ($mode == 'REPLACE') {
    $sql = 'SELECT count(*) total FROM '.$table;
    if($where !== false){
      $sql .= ' WHERE '.$where;
    }
    if($conn->fetchColumn($sql, array(), 0) == 0) {
      $mode = 'INSERT';
      $where = false;
    }
    else {
      $mode = 'UPDATE';
    }
  }

  if($mode == 'UPDATE' && $where === false){
    $error = "xtc_db_perform: update without 'where' doesn't work! <br />";
    $error .= "$table <br />";
    foreach($record as $key => $value){
      $error .= "$key => $value <br />";
    }
    xtc_db_error('', '', $error);
    die;
  }

  $sm = $conn->getSchemaManager();
  $columns = $sm->listTableColumns(str_replace(array('?','`'), '', $table));
  $set = array();
  foreach ($columns as $column) {
    if(isset($record[$column->getName()])){
      $value = $record[$column->getName()];
      if(empty($value)){
        $value = "''";
      }
      else if($value !== 'now()' && $value !== 'null'){
        $value = $conn->quote($value, $column->getType()->getBindingType());
      }
      $set[$column->getName()] = $value;
    }
  }

  switch($mode){
    case 'INSERT':
      $sql = 'INSERT INTO '.$table.' ('.implode(',', array_keys($set)).') VALUES ('.implode(',', $set).')';
      return $conn->executeUpdate($sql);
    case 'UPDATE':
      $pairs = array();
      foreach($set as $field => $value){
        $pairs[] = $field.' = '.$value;
      }

      $sql = 'UPDATE '.$table.' SET '.implode(', ', $pairs).' WHERE '.$where;
      return $conn->executeUpdate($sql);
  }
  return 0;
}
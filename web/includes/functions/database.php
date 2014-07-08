<?php


require_once DIR_FS_INC.'xtc_db_close.inc.php';
require_once DIR_FS_INC.'xtc_db_connect.inc.php';
require_once DIR_FS_INC.'xtc_db_error.inc.php';
require_once DIR_FS_INC.'xtc_db_fetch_array.inc.php';
require_once DIR_FS_INC.'xtc_db_free_result.inc.php';
require_once DIR_FS_INC.'xtc_db_get_conn.inc.php';
require_once DIR_FS_INC.'xtc_db_input.inc.php';
require_once DIR_FS_INC.'xtc_db_insert_id.inc.php';
require_once DIR_FS_INC.'xtc_db_num_rows.inc.php';
require_once DIR_FS_INC.'xtc_db_perform.inc.php';
require_once DIR_FS_INC.'xtc_db_query.inc.php';
require_once DIR_FS_INC.'xtc_db_quote.inc.php';
require_once DIR_FS_INC.'xtc_db_select_db.inc.php';
require_once DIR_FS_INC.'xtc_db_update.inc.php';

function xtc_db_get_server_info(){
  $conn = xtc_db_get_conn();
  return $conn->getWrappedConnection()->getAttribute(PDO::ATTR_SERVER_VERSION);
}
function xtc_db_get_client_info(){
  $conn = xtc_db_get_conn();
  return $conn->getWrappedConnection()->getAttribute(PDO::ATTR_CLIENT_VERSION);
}
function xtc_db_fetch_row(&$db_query){
  return xtc_db_fetch_array($db_query, false, \PDO::FETCH_NUM);
}
function xtc_db_fetch_object(&$db_query){
  return xtc_db_fetch_array($db_query, false, \PDO::FETCH_OBJ);
}
function xtc_db_get_error(){
  global $db_last_error;
  return $db_last_error;
}
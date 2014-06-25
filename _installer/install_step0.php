<?php
  /* --------------------------------------------------------------
   $Id: install_step1.php 3072 2012-06-18 15:01:13Z hhacker $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   --------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(install.php,v 1.7 2002/08/14); www.oscommerce.com
   (c) 2003 nextcommerce (install_step1.php,v 1.10 2003/08/17); www.nextcommerce.org
   (c) 2006 XT-Commerce www.xt-commerce.com

   Released under the GNU General Public License
   --------------------------------------------------------------*/

  require('includes/application.php');

  include('language/'.$lang.'.php');

  if (!$script_filename = str_replace('\\', '/', getenv('PATH_TRANSLATED'))) {
    $script_filename = getenv('SCRIPT_FILENAME');
  }
  $script_filename = str_replace('//', '/', $script_filename);

  if (!$request_uri = getenv('REQUEST_URI')) {
    if (!$request_uri = getenv('PATH_INFO')) {
      $request_uri = getenv('SCRIPT_NAME');
    }

    if (getenv('QUERY_STRING')) $request_uri .=  '?' . getenv('QUERY_STRING');
  }

  $dir_fs_www_root_array = explode('/', dirname($script_filename));
  $dir_fs_www_root = array();
  for ($i=0; $i<sizeof($dir_fs_www_root_array)-2; $i++) {
    $dir_fs_www_root[] = $dir_fs_www_root_array[$i];
  }
  $dir_fs_www_root = implode('/', $dir_fs_www_root);

  //DIR_WS_CATALOG
  $dir_ws_www_root_array = explode('/', dirname($request_uri));
  $dir_ws_www_root = array();
  for ($i=0; $i<sizeof($dir_ws_www_root_array)-1; $i++) {
    if ($dir_ws_www_root_array[$i] != '.' && $dir_ws_www_root_array[$i] != '..') { // web28 - 2010-03-18 - Fix Dir
      $dir_ws_www_root[] = $dir_ws_www_root_array[$i];
    }
  }
  $dir_ws_www_root = implode('/', $dir_ws_www_root);

  //BOF - web28 - 2010-03-18 - RESTORE POST  & GET DATA
  $inst_db = true;
  $config = true;
  if(isset($_POST['DB_SERVER'])){
    //echo 'TEST' . $_POST['install_db'];
    if($_POST['install_db'] == 1) $inst_db = true; else $inst_db = false;
    if($_POST['install_cfg'] == 1) $config = true; else $config = false;
  }
  if(isset($_GET['insdb']) && $_GET['insdb'] !=1 ) $inst_db = false;
  if(isset($_GET['cfg']) && $_GET['cfg'] !=1 ) $config = false;
  //EOF - web28 - 2010-03-18 - RESTORE POST  & GET DATA

$composer_error = false;
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'install'){

  chdir(__DIR__.'/../');

  if(!file_exists(__DIR__.'/../composer.phar')){
    $last_line = exec('curl -sS https://getcomposer.org/installer | php', $retarray, $composer_error);
    
    if($composer_error) {
      $composer_error = TEXT_COMPOSER_DOWNOAD_ERROR;
      $composer_error .= '<br><br><b>Composer output:</b><br>'.implode('<br>', $retarray);

      $last_line = exec('php -r "readfile(\'https://getcomposer.org/installer\');" | php', $retarray, $composer_error2);
      if($composer_error2) {
        $composer_error .= '<br><br><b>Composer output (2nd try):</b><br>'.implode('<br>', $retarray);
      }
      else {
        $composer_error = false;
      }
    }
  }

  putenv('COMPOSER_HOME=' . __DIR__.'/.composer');
  $last_line = exec('php composer.phar install 2>&1', $retarray, $composer_error);
  if($composer_error) {
    $composer_error = TEXT_COMPOSER_INSTALL_ERROR;
    $composer_error .= '<br><br><b>Composer output:</b><br>'.implode('<br>', $retarray);
  }

  chdir(__DIR__);

  if(empty($composer_error)){
    header('Location: install_step1.php');
    exit;
  }
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>modified eCommerce Shopsoftware Installer - STEP 0 / Composer</title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset;?>" />
    <style type="text/css">
      body { background: #eee; font-family: Arial, sans-serif; font-size: 12px;}
      table,td,div { font-family: Arial, sans-serif; font-size: 12px;}
      h1 { font-size: 18px; margin: 0; padding: 0; margin-bottom: 10px; }
      <!--
        .messageStackError, .messageStackWarning { font-family: Verdana, Arial, sans-serif; font-weight: bold; font-size: 10px; background-color: #; }
      -->
    </style>
  </head>
  <body>
    <table width="800" style="border:30px solid #fff;" height="80%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td height="95" colspan="2" >
          <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td><img src="images/logo.png" alt="modified eCommerce Shopsoftware" /></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td align="center" valign="top">
          <br />
          <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td>
                <img src="images/step0.gif" width="705" height="180" border="0"><br />
                <br />
                <br />
                <div style="border:1px solid #ccc; background:#fff; padding:10px;"><?php echo TEXT_WELCOME_STEP0; ?></div>
              </td>
            </tr>
          </table>
          <br />
          <form name="install" method="post" action="install_step0.php?action=install">
            <?php echo $input_lang; ?>
            <table width="95%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td>
                  <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td><h1><?php echo TITLE_WEBSERVER_SETTINGS; ?> </h1></td>
                    </tr>
                  </table>
                  <div style="border:1px solid #ccc; background:#fff; padding:10px;">
                    <?php //BOF - web28 - 2010.02.20 -  NEW ROOT INFO ?>
                    <p><b><?php echo TEXT_WS_ROOT; ?></b></p>
                    <?php echo xtc_draw_hidden_field_installer('DIR_FS_DOCUMENT_ROOT', DIR_FS_DOCUMENT_ROOT); ?>
                    <span style="border: #a3a3a3 1px solid; padding: 3px; background-color: #f4f4f4;"><?php echo DIR_FS_DOCUMENT_ROOT; ?></span>
                    <p><?php echo TEXT_WS_ROOT_INFO; ?></p>
                    <p><b><?php echo TEXT_WS_CATALOG; ?></b></p>
                    <?php echo xtc_draw_hidden_field_installer('DIR_WS_CATALOG', $dir_ws_www_root . '/'); ?>
                    <span style="border: #a3a3a3 1px solid; padding: 3px; background-color: #f4f4f4;"><?php echo $dir_ws_www_root . '/'; ?></span>
                    <p><?php echo TEXT_WS_ROOT_INFO; ?></p>
                    <?php //EOF - web28 - 2010.02.20 -  NEW ROOT INFO ?>                    
                  </div>
                </td>
              </tr>
            </table>
            <br />
            <br />
            <table width="95%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td>
                  <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td><h1><?php echo TITLE_COMPOSER_INFO; ?></h1></td>
                    </tr>
                  </table>
                  <div style="border:1px solid #ccc; background:#fff; padding:10px;">
                    <p>
                      <?php
                      if(!$composer_error){
                        echo TITLE_COMPOSER_TEXT;
                      }
                      else {
                        echo $composer_error;
                      }
                      ?>
                    </p>
                  </div>
                </td>
              </tr>
            </table>
            <br />
            <table border="0" width="95%" cellspacing="0" cellpadding="0">
              <tr>
                <td align="right"><a href="index.php"><img src="buttons/<?php echo $lang;?>/button_cancel.gif" border="0" alt="Cancel"></a> <input type="image" src="buttons/<?php echo $lang;?>/button_continue.gif" border="0" alt="Continue"></td>
              </tr>
            </table>
          </form>
        </td>
      </tr>
    </table>
    <br />
    <div align="center" style="font-family:Arial, sans-serif; font-size:11px;"><?php echo TEXT_FOOTER; ?></div>
  </body>
</html>

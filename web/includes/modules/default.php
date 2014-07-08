<?php
/* -----------------------------------------------------------------------------------------
   $Id: default.php 2774 2012-04-20 18:30:22Z web28 $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
  -----------------------------------------------------------------------------------------
  based on:
  (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
  (c) 2002-2003 osCommerce(default.php,v 1.84 2003/05/07); www.oscommerce.com
  (c) 2003 nextcommerce (default.php,v 1.11 2003/08/22); www.nextcommerce.org
  (c) 2006 xt:Commerce (cross_selling.php 1243 2005-09-25); www.xt-commerce.de

  Released under the GNU General Public License
  -----------------------------------------------------------------------------------------
  Third Party contributions:
  Enable_Disable_Categories 1.3        Autor: Mikel Williams | mikel@ladykatcostumes.com
  Customers Status v3.x  (c) 2002-2003 Copyright Elari elari@free.fr | www.unlockgsm.com/dload-osc/
  | CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs...by=date#dirlist

  Released under the GNU General Public License
  ---------------------------------------------------------------------------------------*/

$requestPath = $_SERVER['REQUEST_URI'];
$requestPath = str_replace($_SERVER['SCRIPT_NAME'], '', $requestPath);
$requestPath = str_replace($_SERVER['BASE'], '', $requestPath);
$queryStartPos = strpos($requestPath, '?');
if($queryStartPos !== false){
    $requestPath = substr($requestPath, 0, $queryStartPos);
}
if($requestPath !== '' && $requestPath !== '/'){
    \XTC\Kernel::handle();
}
else {
    // create smarty elements
    $smarty = new XTC\Template\Template;

    require (DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/source/boxes.php');

    $default_smarty = new XTC\Template\Template;
    $default_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
    $default_smarty->assign('session', session_id());

    // define defaults
    $main_content = '';
    $group_check = '';
    $fsk_lock = '';

    // include needed functions
    require_once (DIR_FS_INC.'xtc_customer_greeting.inc.php');
    require_once (DIR_FS_INC.'xtc_get_path.inc.php');
    require_once (DIR_FS_INC.'xtc_check_categories_status.inc.php');

    // check categorie exist
    if (xtc_check_categories_status($current_category_id) >= 1) {
        $error = CATEGORIE_NOT_FOUND;
        include (DIR_WS_MODULES.FILENAME_ERROR_HANDLER);
        return;
    }

    // the following cPath references come from application_top.php

    // the following cPath references come from application_top.php
    $listing_module = '';
    if (isset ($cPath) && xtc_not_null($cPath)) {
        $categories_query = "
            SELECT listing_module
            FROM ".TABLE_CATEGORIES." c
            WHERE c.categories_id = ".intval($current_category_id)."
        ";
        $categories_result = xtc_db_query($categories_query);
        if (xtc_db_num_rows($categories_result) > 0) {
            $categories_data = xtc_db_fetch_array($categories_result);
            $listing_module = $categories_data['listing_module'];
        }

        if(empty($listing_module)){
            $categories_products_query = "select p2c.products_id
                                  from ".TABLE_PRODUCTS_TO_CATEGORIES." p2c
                                  left join ".TABLE_PRODUCTS." p
                                   on p2c.products_id = p.products_id
                                  where p2c.categories_id = ".(int)$current_category_id."
                                  and p.products_status = 1";
            $categories_products_result = xtDBquery($categories_products_query);
            if (xtc_db_num_rows($categories_products_result, true) > 0) {
                $listing_module = 'product_listing'; // display products
            } else {
                $category_parent_query = "select parent_id from ".TABLE_CATEGORIES." where parent_id = ".(int)$current_category_id." AND categories_status = 1";
                $category_parent_result = xtDBquery($category_parent_query);
                $category_parent = xtc_db_fetch_array($category_parent_result, true);
                if (xtc_db_num_rows($category_parent_result, true) > 0) {
                    $listing_module = 'category_listing'; // navigate through the categories
                } else {
                    $listing_module = 'product_listing'; // category has no products, but display the 'no products' message
                }
            }
        }
    }
    else if(isset($_GET['manufacturers_id']) && $_GET['manufacturers_id'] > 0){
        $listing_module = 'product_listing';
    }


    $listing_module_path = DIR_WS_MODULES.'listing/'.$listing_module.'.php';
    if(!empty($listing_module) && file_exists($listing_module_path)){
        require $listing_module_path;
    }
    // default content page
    else {
        $shop_content_data = $main->getContentData(5);

        $default_smarty->assign('title', $shop_content_data['content_heading']);

        include (DIR_WS_INCLUDES.FILENAME_CENTER_MODULES);

        $default_smarty->assign('text', str_replace('{$greeting}', xtc_customer_greeting(), $shop_content_data['content_text']));
        $default_smarty->assign('language', $_SESSION['language']);

        // set cache ID
        if (!CacheCheck()) {
            $default_smarty->caching = 0;
            $main_content = $default_smarty->fetch(CURRENT_TEMPLATE.'/module/main_content.html');
        } else {
            $default_smarty->caching = 1;
            $default_smarty->cache_lifetime = CACHE_LIFETIME;
            $default_smarty->cache_modified_check = CACHE_CHECK;
            $cache_id = $_SESSION['language'].$_SESSION['currency'].$_SESSION['customer_id'];
            $main_content = $default_smarty->fetch(CURRENT_TEMPLATE.'/module/main_content.html', $cache_id);
        }
        $smarty->assign('main_content', $main_content);
    }

    require (DIR_WS_INCLUDES.'header.php'); //web28 - 2013-01-04 - load header.php after default.php because of error handling status code
    $smarty->assign('language', $_SESSION['language']);

    $smarty->caching = 0;
    if (!defined('RM'))
        $smarty->load_filter('output', 'note');
    $smarty->display(CURRENT_TEMPLATE.'/index.html');
}
<?php
/* --------------------------------------------------------------
 $Id: column_left.php 4298 2013-01-13 20:04:19Z Tomcraft1980 $

 modified eCommerce Shopsoftware
 http://www.modified-shop.org

 Copyright (c) 2009 - 2013 [www.modified-shop.org]
 --------------------------------------------------------------
 based on:
 (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
 (c) 2002-2003 osCommerce(column_left.php,v 1.15 2002/01/11); www.oscommerce.com
 (c) 2003 nextcommerce (column_left.php,v 1.25 2003/08/19); www.nextcommerce.org
 (c) 2006 XT-Commerce (content_manager.php 1304 2005-10-12)

 Released under the GNU General Public License
 --------------------------------------------------------------*/
//#############################
// HINWEIS FÜR MODULE EINBAU
// Das muss beim Hinzufügen neuer Menüpunkte beachtet werden:
// Die Menüpunkte wurden auf array konfiguration geändert
// Die Reihenfolge weicht von einem xtc Standard ab
// Rubrik Konfiguration wurde in 2 Teile aufgeteilt
//#############################

defined( '_VALID_XTC' ) or die( 'Direct Access to this location is not allowed.' );

$admin_access_query = xtc_db_query("select * from " . TABLE_ADMIN_ACCESS . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'");
$admin_access = xtc_db_fetch_array($admin_access_query);

$generate_admin_nav_sub = function($data) use ($admin_access){
  if($_SESSION['customers_status']['customers_status_id'] != '0'){
    return '';
  }

  $array_items = array();
  foreach($data['items'] as $item){
    if(isset($item['active']) && $item['active'] == false){
      continue;
    }
    if(empty($item['admin_access_var']) || (isset($admin_access[$item['admin_access_var']]) && $admin_access[$item['admin_access_var']] == '1')){
      $array_items[] = '<li><a href="'.$item['url'].'" class="menuBoxContentLink"> -'.$item['title'].'</a></li>';
    }
  }
  if(empty($data['url']) && empty($array_items)){
    return '';
  }
  $out = '<li><div class="dataTableHeadingContent">';
  if(!empty($data['url'])){
    $out .= '<a href="'.$data['url'].'" style="width: auto;">';
  }
  $out .= '<strong>'.$data['title'].'</strong>';
  if(!empty($data['url'])){
    $out .= '</a>';
  }
  $out .= '</div>';
  if(!empty($array_items)){
    $out .= '<ul>'.implode('', $array_items).'</ul>';
  }
  $out .= '</li>';
  return $out;
};


echo '<div id="cssmenu" class="suckertreemenu">';
echo '<ul id="treemenu1">';

//---------------------------Ausgewählte Admin Sprache als Flagge
echo ('<li><div id="lang_flag">' . xtc_image('../lang/' .  $_SESSION['language'] .'/admin/images/' . 'icon.gif', $_SESSION['language']). '</div></li>');

//---------------------------STARTSEITE
echo ('<li><a href="' . xtc_href_link('start.php', '', 'NONSSL') . '" id="current"><b>' . TEXT_ADMIN_START . '</b></a></li>');

//---------------------------KUNDEN
$tmp_nav = array(
  'title' => BOX_HEADING_CUSTOMERS,
  'items' => array(
    array('admin_access_var' => 'customers', 'url' => xtc_href_link(FILENAME_CUSTOMERS, '', 'NONSSL'), 'title' => BOX_CUSTOMERS),
    array('admin_access_var' => 'customers_status', 'url' => xtc_href_link(FILENAME_CUSTOMERS_STATUS, '', 'NONSSL'), 'title' => BOX_CUSTOMERS_STATUS),
    array('admin_access_var' => 'customers_group', 'url' => xtc_href_link('customers_group.php', '', 'NONSSL'), 'title' => BOX_CUSTOMERS_GROUP, 'active' => (GROUP_CHECK == 'true')),
    array('admin_access_var' => 'orders', 'url' => xtc_href_link(FILENAME_ORDERS, '', 'NONSSL'), 'title' => BOX_ORDERS),
  )
);
echo $generate_admin_nav_sub($tmp_nav);

//---------------------------ARTIKELKATALOG
$tmp_nav = array(
  'title' => BOX_HEADING_PRODUCTS,
  'items' => array(
    array('admin_access_var' => 'categories', 'url' => xtc_href_link(FILENAME_CATEGORIES, '', 'NONSSL'), 'title' => BOX_CATEGORIES),
    array('admin_access_var' => 'new_attributes', 'url' => xtc_href_link(FILENAME_NEW_ATTRIBUTES, '', 'NONSSL'), 'title' => BOX_ATTRIBUTES_MANAGER),
    array('admin_access_var' => 'products_attributes', 'url' => xtc_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '', 'NONSSL'), 'title' => BOX_PRODUCTS_ATTRIBUTES),
    array('admin_access_var' => 'manufacturers', 'url' => xtc_href_link(FILENAME_MANUFACTURERS, '', 'NONSSL'), 'title' => BOX_MANUFACTURERS),
    array('admin_access_var' => 'reviews', 'url' => xtc_href_link(FILENAME_REVIEWS, '', 'NONSSL'), 'title' => BOX_REVIEWS),
    array('admin_access_var' => 'specials', 'url' => xtc_href_link(FILENAME_SPECIALS, '', 'NONSSL'), 'title' => BOX_SPECIALS),
    array('admin_access_var' => 'products_expected', 'url' => xtc_href_link(FILENAME_PRODUCTS_EXPECTED, '', 'NONSSL'), 'title' => BOX_PRODUCTS_EXPECTED),
  )
);
echo $generate_admin_nav_sub($tmp_nav);

/******** SHOPGATE **********/
include_once (DIR_FS_CATALOG.'includes/external/shopgate/base/admin/includes/column_left.php');
/******** SHOPGATE **********/

//---------------------------XSBOOSTER
if (defined('MODULE_XTBOOSTER_STATUS') && MODULE_XTBOOSTER_STATUS =='True') {
  $tmp_nav = array(
    'title' => BOX_HEADING_XSBOOSTER,
    'items' => array(
        array('admin_access_var' => 'configuration', 'url' => xtc_href_link(FILENAME_XTBOOSTER."?xtb_module=list", '', 'NONSSL'), 'title' => BOX_XSBOOSTER_LISTAUCTIONS),
        array('admin_access_var' => 'configuration', 'url' => xtc_href_link(FILENAME_XTBOOSTER."?xtb_module=add", '', 'NONSSL'), 'title' => BOX_XSBOOSTER_ADDAUCTIONS),
        array('admin_access_var' => 'configuration', 'url' => xtc_href_link(FILENAME_XTBOOSTER."?xtb_module=conf", '', 'NONSSL'), 'title' => BOX_XSBOOSTER_CONFIG),
    )
  );
  echo $generate_admin_nav_sub($tmp_nav);
}

//---------------------------MODULE
$tmp_nav = array(
  'title' => BOX_HEADING_MODULES,
  'items' => array(
    array('admin_access_var' => '', 'url' => xtc_href_link(FILENAME_GOOGLE_SITEMAP, 'auto=true&ping=true'), 'title' => BOX_GOOGLE_SITEMAP),
    array('admin_access_var' => 'modules', 'url' => xtc_href_link(FILENAME_MODULES, 'set=payment', 'NONSSL'), 'title' => BOX_PAYMENT),
    array('admin_access_var' => 'modules', 'url' => xtc_href_link(FILENAME_MODULES, 'set=shipping', 'NONSSL'), 'title' => BOX_SHIPPING),
    array('admin_access_var' => 'modules', 'url' => xtc_href_link(FILENAME_MODULES, 'set=ordertotal', 'NONSSL'), 'title' => BOX_ORDER_TOTAL),
    array('admin_access_var' => 'module_export', 'url' => xtc_href_link(FILENAME_MODULE_EXPORT), 'title' => BOX_MODULE_EXPORT),
    array('admin_access_var' => 'janolaw', 'url' => xtc_href_link(FILENAME_JANOLAW, '', 'NONSSL'), 'title' => BOX_JANOLAW),
    array('admin_access_var' => 'haendlerbund', 'url' => xtc_href_link(FILENAME_HAENDLERBUND, ''), 'title' => BOX_HAENDLERBUND),
  )
);
echo $generate_admin_nav_sub($tmp_nav);

//---------------------------STATISTIKEN
$tmp_nav = array(
  'title' => BOX_HEADING_STATISTICS,
  'items' => array(
    array('admin_access_var' => 'stats_products_viewed', 'url' => xtc_href_link(FILENAME_STATS_PRODUCTS_VIEWED, '', 'NONSSL'), 'title' => BOX_PRODUCTS_VIEWED),
    array('admin_access_var' => 'stats_products_purchased', 'url' => xtc_href_link(FILENAME_STATS_PRODUCTS_PURCHASED, '', 'NONSSL'), 'title' => BOX_PRODUCTS_PURCHASED),
    array('admin_access_var' => 'stats_customers', 'url' => xtc_href_link(FILENAME_STATS_CUSTOMERS, '', 'NONSSL'), 'title' => BOX_STATS_CUSTOMERS),
    array('admin_access_var' => 'stats_sales_report', 'url' => xtc_href_link(FILENAME_SALES_REPORT, '', 'NONSSL'), 'title' => BOX_SALES_REPORT),
    array('admin_access_var' => 'stats_campaigns', 'url' => xtc_href_link(FILENAME_CAMPAIGNS_REPORT, '', 'NONSSL'), 'title' => BOX_CAMPAIGNS_REPORT),
  )
);
echo $generate_admin_nav_sub($tmp_nav);

//---------------------------HILFSPROGRAMME
$tmp_nav = array(
  'title' => BOX_HEADING_TOOLS,
  'items' => array(
    array('admin_access_var' => 'module_newsletter', 'url' => xtc_href_link(FILENAME_MODULE_NEWSLETTER), 'title' => BOX_MODULE_NEWSLETTER),
    array('admin_access_var' => 'content_manager', 'url' => xtc_href_link(FILENAME_CONTENT_MANAGER), 'title' => BOX_CONTENT),
//    array('admin_access_var' => 'blacklist', 'url' => xtc_href_link(FILENAME_BLACKLIST, '', 'NONSSL'), 'title' => BOX_TOOLS_BLACKLIST),
    array('admin_access_var' => 'removeoldpics', 'url' => xtc_href_link(FILENAME_REMOVEOLDPICS, '', 'NONSSL'), 'title' => BOX_REMOVEOLDPICS),
    array('admin_access_var' => 'backup', 'url' => xtc_href_link(FILENAME_BACKUP), 'title' => BOX_BACKUP),
    array('admin_access_var' => 'banner_manager', 'url' => xtc_href_link(FILENAME_BANNER_MANAGER), 'title' => BOX_BANNER_MANAGER),
    array('admin_access_var' => 'server_info', 'url' => xtc_href_link(FILENAME_SERVER_INFO), 'title' => BOX_SERVER_INFO),
    array('admin_access_var' => 'blz_update', 'url' => xtc_href_link(FILENAME_BLZ_UPDATE), 'title' => BOX_BLZ_UPDATE),
    array('admin_access_var' => 'whos_online', 'url' => xtc_href_link(FILENAME_WHOS_ONLINE), 'title' => BOX_WHOS_ONLINE),
    array('admin_access_var' => 'csv_backend', 'url' => xtc_href_link('csv_backend.php'), 'title' => BOX_IMPORT),
    array('admin_access_var' => 'paypal', 'url' => xtc_href_link('paypal.php'), 'title' => BOX_PAYPAL),
  )
);
echo $generate_admin_nav_sub($tmp_nav);

//---------------------------GUTSCHEINE
if (ACTIVATE_GIFT_SYSTEM=='true') {
  $tmp_nav = array(
    'title' => BOX_HEADING_GV_ADMIN,
    'items' => array(
        array('admin_access_var' => 'coupon_admin', 'url' => xtc_href_link(FILENAME_COUPON_ADMIN, '', 'NONSSL'), 'title' => BOX_COUPON_ADMIN),
        array('admin_access_var' => 'gv_queue', 'url' => xtc_href_link(FILENAME_GV_QUEUE, '', 'NONSSL'), 'title' => BOX_GV_ADMIN_QUEUE),
        array('admin_access_var' => 'gv_mail', 'url' => xtc_href_link(FILENAME_GV_MAIL, '', 'NONSSL'), 'title' => BOX_GV_ADMIN_MAIL),
        array('admin_access_var' => 'gv_sent', 'url' => xtc_href_link(FILENAME_GV_SENT, '', 'NONSSL'), 'title' => BOX_GV_ADMIN_SENT),
    )
  );
  echo $generate_admin_nav_sub($tmp_nav);
}

//---------------------------LAND / STEUER
$tmp_nav = array(
  'title' => BOX_HEADING_ZONE,
  'items' => array(
    array('admin_access_var' => 'languages', 'url' => xtc_href_link(FILENAME_LANGUAGES, '', 'NONSSL'), 'title' => BOX_LANGUAGES),
    array('admin_access_var' => 'countries', 'url' => xtc_href_link(FILENAME_COUNTRIES, '', 'NONSSL'), 'title' => BOX_COUNTRIES),
    array('admin_access_var' => 'currencies', 'url' => xtc_href_link(FILENAME_CURRENCIES, '', 'NONSSL'), 'title' => BOX_CURRENCIES),
    array('admin_access_var' => 'zones', 'url' => xtc_href_link(FILENAME_ZONES, '', 'NONSSL'), 'title' => BOX_ZONES),
    array('admin_access_var' => 'geo_zones', 'url' => xtc_href_link(FILENAME_GEO_ZONES, '', 'NONSSL'), 'title' => BOX_GEO_ZONES),
    array('admin_access_var' => 'tax_classes', 'url' => xtc_href_link(FILENAME_TAX_CLASSES, '', 'NONSSL'), 'title' => BOX_TAX_CLASSES),
    array('admin_access_var' => 'tax_rates', 'url' => xtc_href_link(FILENAME_TAX_RATES, '', 'NONSSL'), 'title' => BOX_TAX_RATES),
  )
);
echo $generate_admin_nav_sub($tmp_nav);

//---------------------------KONFIGURATION
$tmp_nav = array(
  'title' => BOX_HEADING_CONFIGURATION,
  'items' => array(
    array('admin_access_var' => 'configuration', 'url' => xtc_href_link(FILENAME_CONFIGURATION, 'gID=1', 'NONSSL'), 'title' => BOX_CONFIGURATION_1),
    array('admin_access_var' => 'configuration', 'url' => xtc_href_link(FILENAME_CONFIGURATION, 'gID=1000', 'NONSSL'), 'title' => BOX_CONFIGURATION_1000),
    array('admin_access_var' => 'configuration', 'url' => xtc_href_link(FILENAME_CONFIGURATION, 'gID=2', 'NONSSL'), 'title' => BOX_CONFIGURATION_2),
    array('admin_access_var' => 'configuration', 'url' => xtc_href_link(FILENAME_CONFIGURATION, 'gID=3', 'NONSSL'), 'title' => BOX_CONFIGURATION_3),
    array('admin_access_var' => 'configuration', 'url' => xtc_href_link(FILENAME_CONFIGURATION, 'gID=4', 'NONSSL'), 'title' => BOX_CONFIGURATION_4),
    array('admin_access_var' => 'configuration', 'url' => xtc_href_link(FILENAME_CONFIGURATION, 'gID=5', 'NONSSL'), 'title' => BOX_CONFIGURATION_5),
    array('admin_access_var' => 'configuration', 'url' => xtc_href_link(FILENAME_CONFIGURATION, 'gID=7', 'NONSSL'), 'title' => BOX_CONFIGURATION_7),
    array('admin_access_var' => 'configuration', 'url' => xtc_href_link(FILENAME_CONFIGURATION, 'gID=8', 'NONSSL'), 'title' => BOX_CONFIGURATION_8),
    array('admin_access_var' => 'configuration', 'url' => xtc_href_link(FILENAME_CONFIGURATION, 'gID=9', 'NONSSL'), 'title' => BOX_CONFIGURATION_9),
    array('admin_access_var' => 'configuration', 'url' => xtc_href_link(FILENAME_CONFIGURATION, 'gID=12', 'NONSSL'), 'title' => BOX_CONFIGURATION_12),
    array('admin_access_var' => 'configuration', 'url' => xtc_href_link(FILENAME_CONFIGURATION, 'gID=13', 'NONSSL'), 'title' => BOX_CONFIGURATION_13),
    array('admin_access_var' => 'orders_status', 'url' => xtc_href_link(FILENAME_ORDERS_STATUS, '', 'NONSSL'), 'title' => BOX_ORDERS_STATUS),
    array('admin_access_var' => 'shipping_status', 'url' => xtc_href_link(FILENAME_SHIPPING_STATUS, '', 'NONSSL'), 'title' => BOX_SHIPPING_STATUS, 'active' => (ACTIVATE_SHIPPING_STATUS=='true')),
    array('admin_access_var' => 'products_vpe', 'url' => xtc_href_link(FILENAME_PRODUCTS_VPE, '', 'NONSSL'), 'title' => BOX_PRODUCTS_VPE),
    array('admin_access_var' => 'campaigns', 'url' => xtc_href_link(FILENAME_CAMPAIGNS, '', 'NONSSL'), 'title' => BOX_CAMPAIGNS),
    array('admin_access_var' => 'cross_sell_groups', 'url' => xtc_href_link(FILENAME_XSELL_GROUPS, '', 'NONSSL'), 'title' => BOX_ORDERS_XSELL_GROUP),
  )
);
echo $generate_admin_nav_sub($tmp_nav);

//---------------------------KONFIGURATION2
$tmp_nav = array(
  'title' => BOX_HEADING_CONFIGURATION2,
  'items' => array(
    array('admin_access_var' => 'shop_offline', 'url' => xtc_href_link('shop_offline.php', '', 'NONSSL'), 'title' => 'Shop online/offline'),
    array('admin_access_var' => 'configuration', 'url' => xtc_href_link(FILENAME_CONFIGURATION, 'gID=10', 'NONSSL'), 'title' => BOX_CONFIGURATION_10),
    array('admin_access_var' => 'configuration', 'url' => xtc_href_link(FILENAME_CONFIGURATION, 'gID=11', 'NONSSL'), 'title' => BOX_CONFIGURATION_11),
    array('admin_access_var' => 'configuration', 'url' => xtc_href_link(FILENAME_CONFIGURATION, 'gID=14', 'NONSSL'), 'title' => BOX_CONFIGURATION_14),
    array('admin_access_var' => 'configuration', 'url' => xtc_href_link(FILENAME_CONFIGURATION, 'gID=15', 'NONSSL'), 'title' => BOX_CONFIGURATION_15),
    array('admin_access_var' => 'configuration', 'url' => xtc_href_link(FILENAME_CONFIGURATION, 'gID=16', 'NONSSL'), 'title' => BOX_CONFIGURATION_16),
    array('admin_access_var' => 'configuration', 'url' => xtc_href_link(FILENAME_CONFIGURATION, 'gID=17', 'NONSSL'), 'title' => BOX_CONFIGURATION_17),
    array('admin_access_var' => 'configuration', 'url' => xtc_href_link(FILENAME_CONFIGURATION, 'gID=18', 'NONSSL'), 'title' => BOX_CONFIGURATION_18),
    array('admin_access_var' => 'configuration', 'url' => xtc_href_link(FILENAME_CONFIGURATION, 'gID=19', 'NONSSL'), 'title' => BOX_CONFIGURATION_19),
    array('admin_access_var' => 'configuration', 'url' => xtc_href_link(FILENAME_CONFIGURATION, 'gID=22', 'NONSSL'), 'title' => BOX_CONFIGURATION_22),
    array('admin_access_var' => 'configuration', 'url' => xtc_href_link(FILENAME_CONFIGURATION, 'gID=40', 'NONSSL'), 'title' => BOX_CONFIGURATION_40),
    array('admin_access_var' => 'configuration', 'url' => xtc_href_link(FILENAME_CONFIGURATION, 'gID=24', 'NONSSL'), 'title' => BOX_CONFIGURATION_24),
  )
);
echo $generate_admin_nav_sub($tmp_nav);


echo ('</ul>');
echo ('</div>');
<?php


$select = '';
$from = '';
$where = '';

// fsk18 lock
if ($_SESSION['customers_status']['customers_fsk18_display'] == '0') {
  $fsk_lock = ' AND p.products_fsk18!=1';
}
// group check
if (GROUP_CHECK == 'true') {
  $group_check = " AND p.group_permission_".$_SESSION['customers_status']['customers_status_id']."=1 ";
}
// sorting query
if (isset($_GET['manufacturers_id']) && isset($_GET['filter_id'])) {
  $categories_id = (int)$_GET['filter_id'];
} else {
  $categories_id = $current_category_id;
}
$sorting_query = xtDBquery("-- /includes/modules/default.php
                              SELECT products_sorting,
                                     products_sorting2
                                FROM ".TABLE_CATEGORIES."
                               WHERE categories_id='".$categories_id ."'");
$sorting_data = xtc_db_fetch_array($sorting_query,true);
if (empty($sorting_data['products_sorting'])) { //Fallback für products_sorting auf products_name
  $sorting_data['products_sorting'] = 'pd.products_name';
}
if (empty($sorting_data['products_sorting2'])) { //Fallback für products_sorting2 auf ascending
  $sorting_data['products_sorting2'] = 'ASC';
}
$sorting = ' ORDER BY '.$sorting_data['products_sorting'].' '.$sorting_data['products_sorting2'].' ';

if (isset($_GET['manufacturers_id'])) {
  // show the products of a specified manufacturer
  $select .= "m.manufacturers_name, ";
  $from   .= "LEFT JOIN ".TABLE_MANUFACTURERS." m on p.manufacturers_id = m.manufacturers_id ";
  $where  .= " AND m.manufacturers_id = '".(int) $_GET['manufacturers_id']."' ";
  if (isset($_GET['filter_id']) && xtc_not_null($_GET['filter_id'])) {
    // We are asked to show only a specific category
    $from   .= "JOIN ".TABLE_PRODUCTS_TO_CATEGORIES." p2c on p2c.products_id = pd.products_id ";
    $where  .= "AND p2c.categories_id = '".(int)$_GET['filter_id']."' ";
  } else {
    // We show them all
  }
} else {
  // show the products in a given categorie
  $from   .= "JOIN ".TABLE_PRODUCTS_TO_CATEGORIES." p2c on p2c.products_id = pd.products_id ";
  $where  .= "AND p2c.categories_id = '".$current_category_id."' ";
  if (isset($_GET['filter_id']) && xtc_not_null($_GET['filter_id'])) {
    // We are asked to show only specific catgeory
    $select .= "m.manufacturers_name, ";
    $from   .= "LEFT JOIN ".TABLE_MANUFACTURERS." m on p.manufacturers_id = m.manufacturers_id ";
    $where  .= "AND m.manufacturers_id = '".(int)$_GET['filter_id']."' ";
  } else {
    // We show them all
  }
}
$select .= 'p.products_manufacturers_model, ';
$listing_sql = "-- /includes/modules/default.php
                  SELECT ".$select."
                         p.products_id,
                         p.products_ean,
                         p.products_quantity,
                         p.products_shippingtime,
                         p.products_model,
                         p.products_image,
                         p.products_price,
                         p.products_discount_allowed,
                         p.products_weight,
                         p.products_tax_class_id,
                         p.manufacturers_id,
                         p.products_fsk18,
                         p.products_vpe,
                         p.products_vpe_status,
                         p.products_vpe_value,
                         pd.products_name,
                         pd.products_description,
                         pd.products_short_description
                    FROM ".TABLE_PRODUCTS_DESCRIPTION." pd
                    JOIN ".TABLE_PRODUCTS." p
                         ".$from."
                   WHERE p.products_status = '1'
                     AND p.products_id = pd.products_id
                     AND pd.language_id = '".(int) $_SESSION['languages_id']."'
                         ".$group_check."
                         ".$fsk_lock."
                         ".$where."
                         ".$sorting;

// optional Product List Filter
if (PRODUCT_LIST_FILTER == 'true') {
  if (isset($_GET['manufacturers_id'])) {
    $filterlist_sql = "-- /includes/modules/default.php
                         SELECT distinct c.categories_id as id,
                                         cd.categories_name as name
                                       FROM ".TABLE_PRODUCTS." p
                                       JOIN ".TABLE_PRODUCTS_TO_CATEGORIES." p2c on p2c.products_id = p.products_id
                                       JOIN ".TABLE_CATEGORIES." c on c.categories_id = p2c.categories_id
                                       JOIN ".TABLE_CATEGORIES_DESCRIPTION." cd on cd.categories_id = p2c.categories_id
                                       WHERE p.products_status = '1'
                                         AND cd.language_id = '".(int) $_SESSION['languages_id']."'
                                         AND p.manufacturers_id = '".(int) $_GET['manufacturers_id']."'
                                         ORDER BY cd.categories_name";
  } else {
    $filterlist_sql = "-- /includes/modules/default.php
                         SELECT distinct m.manufacturers_id as id,
                                         m.manufacturers_name as name
                                       FROM ".TABLE_PRODUCTS." p
                                       JOIN ".TABLE_PRODUCTS_TO_CATEGORIES." p2c on p2c.products_id = p.products_id
                                       JOIN ".TABLE_MANUFACTURERS." m on m.manufacturers_id = p.manufacturers_id
                                       WHERE p.products_status = '1'
                                         AND p2c.categories_id = '".$current_category_id."'
                                         ORDER BY m.manufacturers_name";
  }
  $filterlist_query = xtDBquery($filterlist_sql);
  if (xtc_db_num_rows($filterlist_query, true) > 1) {
    $manufacturer_dropdown = xtc_draw_form('filter', DIR_WS_CATALOG . FILENAME_DEFAULT, 'get');
    if (isset($_GET['manufacturers_id'])) {
      $manufacturer_dropdown .= xtc_draw_hidden_field('manufacturers_id', (int)$_GET['manufacturers_id']).PHP_EOL;
      $options = array (array ('id' => '', 'text' => TEXT_ALL_CATEGORIES)); // DokuMan - 2012-03-27 - added missing "id" for xtc_draw_pull_down_menu
    } else {
      $manufacturer_dropdown .= xtc_draw_hidden_field('cat', $current_category_id).PHP_EOL;
      $options = array (array ('id' => '', 'text' => TEXT_ALL_MANUFACTURERS)); // DokuMan - 2012-03-27 - added missing "id" for xtc_draw_pull_down_menu
    }
    if (isset($_GET['sort']) && !empty($_GET['sort'])) {
      $manufacturer_dropdown .= xtc_draw_hidden_field('sort', $_GET['sort']).PHP_EOL;
    }
    while ($filterlist = xtc_db_fetch_array($filterlist_query, true)) {
      $options[] = array ('id' => $filterlist['id'], 'text' => $filterlist['name']);
    }
    $manufacturer_dropdown .= xtc_draw_pull_down_menu('filter_id', $options, isset($_GET['filter_id']) ? (int)$_GET['filter_id'] : '', 'onchange="this.form.submit()"').PHP_EOL;
    $manufacturer_dropdown .= '<noscript><input type="submit" value="'.SMALL_IMAGE_BUTTON_VIEW.'" id="filter_submit" /></noscript>'.PHP_EOL;
    $manufacturer_dropdown .= xtc_hide_session_id() .PHP_EOL; //Session ID nur anhängen, wenn Cookies deaktiviert sind
    $manufacturer_dropdown .= '</form>'.PHP_EOL;
  }
}

include (DIR_WS_MODULES.FILENAME_PRODUCT_LISTING);
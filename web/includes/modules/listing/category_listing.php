<?php



if (GROUP_CHECK == 'true') {
  $group_check = "AND c.group_permission_".$_SESSION['customers_status']['customers_status_id']."=1 ";
}

$category_query = "-- /includes/modules/default.php
                     SELECT c.categories_image,
                            c.listing_template,
                            cd.categories_name,
                            cd.categories_heading_title,
                            cd.categories_description
                          FROM ".TABLE_CATEGORIES." c
                          JOIN ".TABLE_CATEGORIES_DESCRIPTION." cd on cd.categories_id = c.categories_id
                          WHERE c.categories_id = '".$current_category_id."'
                            ".$group_check."
                            AND cd.language_id = '".(int) $_SESSION['languages_id']."'";
$category_query = xtDBquery($category_query);
$category = xtc_db_fetch_array($category_query, true);

if (MAX_DISPLAY_CATEGORIES_PER_ROW > 0) {
  // check to see if there are deeper categories within the current category
  $categories_query = "-- /includes/modules/default.php
                         SELECT c.categories_id,
                                c.categories_image,
                                c.parent_id,
                                cd.categories_name,
                                cd.categories_heading_title,
                                cd.categories_description
                              FROM ".TABLE_CATEGORIES." c
                              JOIN ".TABLE_CATEGORIES_DESCRIPTION." cd on cd.categories_id = c.categories_id
                              WHERE c.categories_status = '1'
                                ".$group_check."
                                AND c.parent_id = '".$current_category_id."'
                                AND cd.language_id = '".(int) $_SESSION['languages_id']."'
                              ORDER BY sort_order, cd.categories_name";
  $categories_query = xtDBquery($categories_query);
  $categories_content = array();
  while ($categories = xtc_db_fetch_array($categories_query, true)) {
    $cPath_new = xtc_category_link($categories['categories_id'],$categories['categories_name']);
    $image = '';
    if ($categories['categories_image'] != '') {
      $image = DIR_WS_IMAGES.'categories/'.$categories['categories_image'];
      if(!file_exists($image)) $image = DIR_WS_IMAGES.'categories/noimage.gif';
    }
    $categories_content[] = array ('CATEGORIES_NAME' => $categories['categories_name'],
                                   'CATEGORIES_HEADING_TITLE' => $categories['categories_heading_title'],
                                   'CATEGORIES_IMAGE' => $image,
                                   'CATEGORIES_LINK' => xtc_href_link(FILENAME_DEFAULT, $cPath_new),
                                   'CATEGORIES_DESCRIPTION' => $categories['categories_description']);
  }
}

$new_products_category_id = $current_category_id;
include (DIR_WS_MODULES.FILENAME_NEW_PRODUCTS);

$image = '';
if ($category['categories_image'] != '') {
  $image = DIR_WS_IMAGES.'categories/'.$category['categories_image'];
  if(!file_exists($image)) $image = DIR_WS_IMAGES.'categories/noimage.gif';
}
// get default template
$template_paths = array(
  CURRENT_TEMPLATE.'/module/listing/category_listing/',
  CURRENT_TEMPLATE.'/module/categorie_listing/' // fallback for old templates
);
if ($category['listing_template'] == '' || $category['listing_template'] == 'default') {
  $files = array ();
  foreach($template_paths as $cl_dir){
    $cl_dir = DIR_FS_CATALOG.'templates/'.$cl_dir;
    if(!is_dir($cl_dir)){
      continue;
    }
    if ($dir = opendir($cl_dir)) {
      while (($file = readdir($dir)) !== false) {
        if (is_file($cl_dir.$file) && (substr($file, 0, 1) != '.') && (substr($file, -5) == '.html') && ($file != 'index.html')) {
          $files[] = $file;
        }
      }
      closedir($dir);
    }
  }
  sort($files);
  $category['listing_template'] = $files[0];
}

$max_per_row = MAX_DISPLAY_CATEGORIES_PER_ROW;
$width = $max_per_row ? intval(100 / $max_per_row).'%' : '';
$default_smarty->assign('TR_COLS', $max_per_row);
$default_smarty->assign('TD_WIDTH', $width);
$default_smarty->assign('CATEGORIES_NAME', $category['categories_name']);
$default_smarty->assign('CATEGORIES_HEADING_TITLE', $category['categories_heading_title']);
$default_smarty->assign('CATEGORIES_IMAGE', $image);
$default_smarty->assign('CATEGORIES_DESCRIPTION', $category['categories_description']);
$default_smarty->assign('language', $_SESSION['language']);
$default_smarty->assign('module_content', $categories_content);
$default_smarty->caching = 0;
$main_content = '';
foreach($template_paths as $cl_dir){
  if(file_exists(DIR_FS_CATALOG.'templates/'.$cl_dir.$category['listing_template'])){
    $main_content = $default_smarty->fetch($cl_dir.$category['listing_template']);
    break;
  }
}
$smarty->assign('main_content', $main_content);
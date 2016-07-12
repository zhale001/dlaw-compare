<?php

/**
 * @file
 * template.php
 */

/**
 * Implements template_preprocess_html().
 */
function dlaw4_bootswatch_preprocess_html(&$vars) {
  global $base_path;

  $vars["apple_touch_icon"] = '<link rel="apple-touch-icon" href="' . $base_path . drupal_get_path('theme', 'dlaw4_bootswatch') . '/apple-touch-icon.png"/>';

  if (module_exists('dlawsettings_appearance') && function_exists('dlawsettings_appearance_color_schemas')) {

    $color_variations = dlawsettings_appearance_color_schemas();
    $default_color_schema = variable_get('theme_color_schema', 'cosmo');

    if (isset($color_variations[$default_color_schema]) && count($color_variations[$default_color_schema]['css'])) {
      foreach ($color_variations[$default_color_schema]['css'] as $file) {
        drupal_add_css($file, array('weight' => 100));
      }
    }
  }

  $vars['head_title'] = str_replace(' | ', ' - ', $vars['head_title']);
}

/**
 * Implements hook_preprocess_page().
 */
function dlaw4_bootswatch_preprocess_page(&$vars) {
  // $vars['site_name'] = variable_get('site_name', 'Default');

  // footer Menu
  $menu_name = 'menu-footer-menu';
  $menu_id = 'footer-menu';
  $vars['my_custom_footer_menu'] = theme('links', array('links' => menu_navigation_links($menu_name), 'attributes' => array('id' => $menu_id, 'role' => 'navigation', 'class'=> array('links', 'inline'))));

  // Copyright_info
  $year = date("Y");
  $vars['site_copyright_info'] = str_replace("[year]", $year, strip_tags(variable_get('site_copyright_info', '&copy; [year]')) );

  $vars['mission'] = strip_tags(variable_get('mission', ''));

  $missionbg_image = variable_get('sitesettings_default_missionbg_image', '');
  $missionbg_image_fid = variable_get('sitesettings_default_missionbg_image_fid', '');

  $darken = variable_get('sitesettings_default_missionbg_darken', 0);
  $diffuse = variable_get('sitesettings_default_missionbg_diffuse', 0);

  if (!empty($darken)) {
    $vars['darken'] = $darken;
  }
  if (!empty($diffuse)) {
    $vars['diffuse'] = $diffuse;
  }

  if (!empty($missionbg_image)) {
    $vars['mission_background_state'] = $missionbg_image;
    $vars['mission_background'] = image_style_url('hero', $missionbg_image);
  } else {
    $vars['mission_background_state'] = 0;
  }

  // Column
  $dlaw4_col_class = "col-sm-12";
  $col_counter = 0;

  if (!empty($vars['page']['front_col1'])){
    $col_counter ++;
  }
  if (!empty($vars['page']['front_col2'])){
    $col_counter ++;
  }
  if (!empty($vars['page']['front_col3'])){
    $col_counter ++;
  }

  switch ($col_counter) {
    case 1:
      $dlaw4_col_class = "col-sm-12";
      break;
    
    case 2:
      $dlaw4_col_class = "col-sm-6";
      break;
    
    default:
      $dlaw4_col_class = "col-sm-4";      
      break;
  }
  $vars['dlaw4_col_class'] = $dlaw4_col_class;

  if ((arg(0) == 'topic') and is_numeric(arg(1))){
    $vars['library_listing'] = true;
  }

  _dlaw4_bootswatch_add_meta_image_for_sns();
}

/**
 * Helper for dlaw4_bootswatch_preprocess_page().
 * Add meta data (og:image, twitter:image) for social network sites.
 */
function _dlaw4_bootswatch_add_meta_image_for_sns() {
  // add open graph line for facebook
  //<meta property="og:image" content="http://YOUR_IMAGE_URL" />
  global $base_url;

  if ($node = menu_get_object() and !empty($node->type)) {
    if(!empty($node->field_image['und'][0]['uri'])){
      $img = file_create_url($node->field_image['und'][0]['uri']);
    }

    drupal_add_html_head(array(
      '#tag' => 'meta',
      '#attributes' => array(
        "property" => "og:image",
        "content" => $img,
    )),'facebook_share_image');

    drupal_add_html_head(array(
      '#tag' => 'meta',
      '#attributes' => array(
        "property" => "twitter:image",
        "content" => $img,
    )),'twitter_share_image');
  }
}

function dlaw4_bootswatch_menu_tree__menu_block__main_menu($vars) {
  return '<ul class="menu nav navbar-nav">' . $vars['tree'] . '</ul>';
}

function dlaw4_bootswatch_print_pdf_tcpdf_header($vars){
  $pdf = $vars['pdf'];
  $pdf->setHeaderMargin(2);
  $pdf->setPrintHeader(false);  
  $pdf->setPrintFooter(false);
  return $pdf;
}

function make_href_root_relative($input) {
  return preg_replace('!http(s)?://' . "$_SERVER[HTTP_HOST]" . '/!', '/', $input);
}
/**
 * Implements hook_preprocess_HOOK().
 */
function dlaw4_bootswatch_preprocess_print(&$variables) {
  $node = $variables['node'];

  $variables['node_url'] = "http://$_SERVER[HTTP_HOST]/" . drupal_get_path_alias('node/'.$node->nid);

  if ( !empty($node->field_qr_url[LANGUAGE_NONE][0]['fid']) ){
    $file = file_load( $node->field_qr_url[LANGUAGE_NONE][0]['fid'] );
    $img_arr = array(
      'path' => $file->uri,
      'width' => '',
      'height' => '',
      'alt' => $node->title,
      'title' => $node->title,
      'attributes' => array('class' => array('myclass'),'style' => 'margin-left:20px;'),
      );
     //$variables['field_qr_url_image'] = '.<img src="' . drupal_realpath($file->uri) . '"/>';
     $variables['field_qr_url_image'] = make_href_root_relative(theme('image',$img_arr));
  }
}

/**
 * Implements theme_breadcrumb().
 */
function dlaw4_bootswatch_breadcrumb($variables) {
  $output = '';

  if (arg(0) == 'node' and is_numeric(arg(1)) and !arg(2)) {
    $nid = arg(1);
    $node = node_load($nid);

    if ((!empty($node->field_category[LANGUAGE_NONE][0]['tid'])) && ($node->type != "contact")) {
      $tid = $node->field_category[LANGUAGE_NONE][0]['tid'];

      $output = theme('library_breadcrumb', array('parent_tid' => $tid));
      return $output;
    }
  }
  else if ((arg(0) == 'topics') and is_numeric(arg(1))){
      $tid = arg(1);
      $output = theme('library_breadcrumb', array('parent_tid' => $tid));
  } else{

    $breadcrumb = $variables['breadcrumb'];

    // Determine if we are to display the breadcrumb.
    $bootstrap_breadcrumb = theme_get_setting('bootstrap_breadcrumb');
    if (($bootstrap_breadcrumb == 1 || ($bootstrap_breadcrumb == 2 && arg(0) == 'admin')) && !empty($breadcrumb)) {
      $output = theme('item_list', array(
        'attributes' => array(
          'class' => array('breadcrumb'),
        ),
        'items' => $breadcrumb,
        'type' => 'ol',
      ));
    }

  }
  return $output;

}

/**
 * Theme search result information.
 * Overrides theme in apachesolr_panels module.
 * Based on G. Lekli's work in case 41390. https://urbaninsight.beanstalkapp.com/plnz-c/changesets/3733c541c8e35c78640cc47eb22b652ab4b66c40
 * Case 41389. 
 */
function dlaw4_bootswatch_apachesolr_panels_info($variables) {
  $filters = array();

  $response = $variables['response'];
  $search = $variables['search'];

  if (!empty($search['keys'])) {
    $filters[] = '<b>' . check_plain($search['keys']) . '</b>';
  }

  $query = apachesolr_current_query(variable_get('apachesolr_default_environment', 'default'));
  if ($query) {
    $searcher = $query->getSearcher();

    $current_adapter = facetapi_adapter_load($searcher);
    $active_items = $current_adapter->getAllActiveItems();

    foreach ($active_items as $key => $value) {

      if ($value['field alias'] == 'im_field_instructors') {
        $node = node_load($value['value']);
        if ($node) {
          $filters[] = check_plain($node->title);
        }
      }
      else {
        // If the filter is not the instructor, assume it's a taxonomy term.
        $term = taxonomy_term_load($value['value']);
        if ($term) {
          $filters[] = check_plain($term->name);
        }
      }

    }
  }

  if ($total = $response->response->numFound) {
    $start = $response->response->start + 1;
    $end = $response->response->start + count($response->response->docs);

    if (!empty($filters)) {
      $info = t('Results %start - %end of %total for !filters', array('%start' => $start, '%end' => $end, '%total' => $total, '!filters' => implode(', ', $filters)));
    }
    else {
      $info = t('Results %start - %end of %total', array('%start' => $start, '%end' => $end, '%total' => $total));
    }

    return $info;
  }
}

/**
 * Theme the human-readable description for a Date Repeat rule.
 *
 * TODO -
 * add in ways to store the description in the date so it isn't regenerated
 * over and over and find a way to allow description to be shown or hidden.
 */
function dlaw4_bootswatch_date_repeat_display($vars) {
  $field = $vars['field'];
  $item = $vars['item'];
  $entity = !empty($vars['node']) ? $vars['node'] : NULL;
  $output = '';
  if (!empty($item['rrule'])) {
    $output = date_repeat_rrule_description($item['rrule']);
    $output = '<div class="date-repeat-rule">' . $output . '</div>';
  }
  return $output;
}

// /**
//  * Theme function implementation for bootstrap_search_form_wrapper.
//  */
// function dlaw4_bootswatch_bootstrap_search_form_wrapper($variables) {
//   dpm($variables);
//   $output = '<div class="input-group">';
//   $output .= $variables['element']['#children'];
//   $output .= '<span class="input-group-btn">';
//   $output .= '<button type="submit" class="btn btn-default">';
//   // We can be sure that the font icons exist in CDN.
//   if (theme_get_setting('bootstrap_cdn')) {
//     $output .= _bootstrap_icon('search');
//   }
//   else {
//     $output .= t('Search');
//   }
//   $output .= '</button>';
//   $output .= '</span>';
//   $output .= '</div>';
//   return $output;
// }

// function dlaw4_bootswatch_preprocess_views_view_fields(&$vars) {
//   $view = $vars['view'];
//     if($view->name == 'contact_list_by_term') {
//       if(!empty($vars['fields']['field_contact_url'])) {
//         $search = array('">http://www.', '">https://www.', '">https://', '">http',);
//         $vars['fields']['field_contact_url']->content = str_replace($search, '">', $vars['fields']['field_contact_url']->content);
//         dpm($vars['fields']['field_contact_url']->content);

//       }
//     }
// }

// function dlaw4_bootswatch_links__menu_custom_menu(&$variables){
//  //custom theme function here
//   print_r('asd');
// }



// /**
//  * Implements template_preprocess_region().
//  */
// function dlaw4_bootswatch_preprocess_region(&$variables) {
//   $region = $variables['region'];
//   dpm($variables);
//   // Sidebars and content area need a good class to style against. You should
//   // not be using id's like #main or #main-wrapper to style contents.
//   if (in_array($region, array('front_col1')) ) {
//     $variables['classes_array'][] = 'main';
//   }
//   // // Add a "clearfix" class to certain regions to clear floated elements inside them.
//   // if (in_array($region, array('footer', 'help', 'highlight'))) {
//   //   $variables['classes_array'][] = 'clearfix';
//   // }
//   // // Add an "outer" class to the darker regions.
//   // if (in_array($region, array('header', 'footer', 'sidebar_first', 'sidebar_second'))) {
//   //   $variables['classes_array'][] = 'outer';
//   // }
// }

<?php
// $Id: template.php,v 1.6 2010/05/19 11:50:15 atrasatti Exp $

/**
 * The theme should be used with the Mobile Plugin -module.
 */

define('THEME_HIGH', '');  // Series60 running webKit, Maemo, other webKit-based browsers
define('THEME_TOUCH', '');  // Series60/5.0 and Maemo (and iPhone and others)
define('THEME_MID', '');  // currently not implemented
define('THEME_LOW', '');  // default

function phptemplate_preprocess_block(&$vars) {
  $vars['netspective_mobile_device_class'] = _netspective_mobile_device_detection();
}

/**
 * Preprocesses template variables.
 * @param vars variables for the template
 */
function phptemplate_preprocess_page(&$vars) {
  global $user;
  $vars['user'] = $user;
  $vars['tabs2'] = menu_secondary_local_tasks();
  
 
  if (module_exists('color')) {
	//	_color_page_alter($vars);
  }
  $vars['mode_user'] = false;
/*
  echo "<pre>\n";
  print_r($vars);
  echo "</pre>\n";
  die();
*/
	$vars['logo_img'] = '';
	$vars['title'] = $title = drupal_get_title();
	if ($vars['logo']) {
		$vars['logo_img'] = $logo_img = "<img src='". $vars['logo'] ."' alt='". $title ."' border='0' />";
		$text = ($vars['site_name']) ? $logo_img . $text : $logo_img;
	}
  
	// here there used to be a check for the existence of the mobileplugin. Moved down.
	// everything should be indented back of two spaces
	// Make sure we have set the proper group IF the theme settings say so
	if ((arg(0) != 'admin')) {
		_netspective_mobile_force_mobile_group();
	}
	if($user->uid) { $vars['mode_user'] = true; }
	$vars['header_text_color'] = '#ffffff';
	// make sure we are using color
	if (module_exists('color')) {
		$header_text_color = theme_get_setting('theme_header_text_color');
		$vars['header_text_color'] = $header_text_color;
	}
	$vars['doctype'] = _netspective_mobile_get_doctype();
	/*
	$vars['left'] = str_replace('user-login', 'my-user-login', $vars['left']);
	$vars['left'] = str_replace('user_login', 'my_user_login', $vars['left']);
	*/
	
	$vars['inline_scripts'] = '';

	// Theme the breadcrumb
	

    
}

function netspective_mobile_preprocess_page(&$vars) {
  // Add preprocess customizations to be executed AFTER the default preprocess_page
}

/**
 * Override local tasks theming to split the secondary tasks.
 * @return rendered local tasks
 */
function phptemplate_menu_local_tasks() {
  return menu_primary_local_tasks();
}

/**
 * Wraps comments to a stylable element.
 * @param content the comment markup
 * @param node the node commented
 * @return full comment markup
 */
function phptemplate_comment_wrapper($content, $node) {
  if (!$content || $node->type == 'forum') {
    return '<div id="comments">' . $content . '</div>';
  }
  return '<div id="comments"><h2 class="comments">' . t('Comments') . '</h2>' . $content . '</div>';
}

/**
 * Themes comment submitted info.
 * @param comment the comment
 * @return submitted info
 */
function phptemplate_comment_submitted(&$comment) {
  return t('!datetime — !username',
    array(
      '!username' => theme('username', $comment),
      '!datetime' => format_date($comment->timestamp)
    ));
}

/**
 * Themes node submitted info.
 * @param node the node
 * @return submitted info
 */
function phptemplate_node_submitted($node) {
  return t('!datetime — !username',
    array(
      '!username' => theme('username', $node),
      '!datetime' => format_date($node->created),
    ));
}

/**
 * Themes feed icon.
 * @param url a feed url
 * @param title a feed title
 * @return feed markup
 */
function phptemplate_feed_icon($url, $title) {
  if ($image = theme('image', 'misc/feed.png', t('Syndicate content'), $title)) {
    return '<div class="feed-div"><a href="'. check_url($url) .'" class="feed-icon">'. $image . ' ' . $title . '</a></div>';
  }
}

/**
 * Register original theme functions.
 * @return theme function array
 */
function netspective_mobile_theme() {
  return array(
    'toplinks' => array(
      'arguments' => array($links => array(), $attributes => array())
    ),
    'breadcrumb' => array(
      'arguments' => array('breadcrumb' => $breadcrumb)
    ),
  );
}

/**
 * Themes the top links.
 * @param links links data
 * @param attributes attributes to add for the element
 * @return top links markup
 */
function netspective_mobile_toplinks($links, $attributes = array('class' => 'links')) {
  $num_links = count($links);
  if ($num_links == 0) {
    return '';
  }
//  If you wanted the top links to be rounded buttons, you should add this class
//  $attributes['class'] .= ' nav-horizontal-rounded';  //Add mobile style name
// then change the div in an ul and all spans in lis
// Links color in the site settings should be changed to a dark color, for example "Nocturnal"
  $output = '<div'. drupal_attributes($attributes) .'>';
  $i = 1;
  foreach ($links as $key => $link) {

    // Add active or passive class to links.
    $active = (strpos($key, 'active') ? ' active' : ' passive');
    if (isset($link['attributes']) && isset($link['attributes']['class'])) {
      $link['attributes']['class'] .= ' ' . $key;
    }
    else {
      $link['attributes']['class'] = $key;
    }

    // Add first and last classes to links.
    $extra_class = '';
    if ($i == 1) {
      $extra_class .= 'first ';
    }
    if ($i == $num_links) {
      $extra_class .= 'last ';
    }
    $output .= '<span '. drupal_attributes(array('class' => $extra_class . $key . $active)) .'>';

    // Create a link or plain title.
    $html = isset($link['html']) && $link['html'];
    $link['query'] = isset($link['query']) ? $link['query'] : NULL;
    $link['fragment'] = isset($link['fragment']) ? $link['fragment'] : NULL;
    if (isset($link['href'])) {
      $output .= l($link['title'], $link['href'], $link['attributes'], $link['query'], $link['fragment'], FALSE, $html);
    } else if ($link['title']) {
      if (!$html) {
        $link['title'] = check_plain($link['title']);
      }
      $output .= '<span'. drupal_attributes($link['attributes']) .'>'. $link['title'] .'</span>';
    }
    $output .= "</span>\n";
    $i++;
  }
  $output .= '</div>';
  return $output;
}

/* netspective_mobile theme-specific changes and additions */
/**
* Return a themed breadcrumb trail. 
*
* @param $breadcrumb
*   An array containing the breadcrumb links.
* @return
*   A string containing the breadcrumb output. 
*/
function netspective_mobile_breadcrumb($breadcrumb) {
  if (!empty($breadcrumb)) {
    $b = '<ul class="breadcrumb">';
    for($i=0;$i<count($breadcrumb);$i++) {
      $entry = $breadcrumb[$i];
      if ($i==0) {
        $b .= '<li class="first">';
      } else {
        $b .= '<li>';
      }
      $b .= $entry;
      if ($i+1 < count($breadcrumb)) {
        $b .= ' > ';
      }
      $b .= '</li>';
    }
    $b .= '</ul>';
    return $b;
  }
}

/*
 * Restyle all submit buttons
 */
function netspective_mobile_button($element) {
  // Make sure not to overwrite classes.
  if (isset($element['#attributes']['class'])) {
    $element['#attributes']['class'] = 'form-'. $element['#button_type'] .' '. $element['#attributes']['class'];
  }
  else {
    $element['#attributes']['class'] = 'form-'. $element['#button_type'];
  }
  $element['#attributes']['class'] .= ' button-submit';


  $attributes = drupal_attributes($element['#attributes']);
  if(_netspective_mobile_device_detection() == THEME_HIGH || _netspective_mobile_device_detection() == THEME_TOUCH ) {
    $output = '<div class="ui-block-a"><button data-inline="true" value="'. check_plain($element['#value']) .'" '. (empty($element['#name']) ? '' : 'name="'. $element['#name'] .'" ') .'id="'. $element['#id'] .'" ' . $attributes .'><span>'. check_plain($element['#value']) .'</span></button></div> '."\n";
  } else {
    $output = '<div class="ui-block-a"><input data-inline="true" type="submit" '. (empty($element['#name']) ? '' : 'name="'. $element['#name'] .'" ') .'id="'. $element['#id'] .'" value="'. check_plain($element['#value']) .'" '. drupal_attributes($element['#attributes']) ." /></div>\n";
  }
  return $output;
//  return '<input type="submit" '. (empty($element['#name']) ? '' : 'name="'. $element['#name'] .'" ') .'id="'. $element['#id'] .'" value="'. check_plain($element['#value']) .'" '. drupal_attributes($element['#attributes']) ." />\n";
}

function phptemplate_menu_tree($tree) {
  return '<ul class="menu">'. $tree .'</ul>';
}

/*
 * Based on the User-Agent string, determine the class the device/browser falls in
 * @todo define a way to detect mid-end devices and implement
 */
function _netspective_mobile_device_detection($forced_user_agent = NULL) {
  global $netspective_mobile_device_class;
  if (is_null($forced_user_agent)) {
    $ua = $_SERVER['HTTP_USER_AGENT'];
  } else {
    $ua = $forced_user_agent;
  }
  
  // fallback for all devices
  $netspective_mobile_device_class = THEME_LOW;
  if (stristr($ua, 'mobile')) {
    // mobile low-end
    $netspective_mobile_device_class = THEME_LOW;
  }
  if ((stristr($ua, 'Series60') && stristr($ua, 'webKit')) || preg_match("/(" . _netspective_mobile_ua_contains() . ")/i", $ua) ) {
    // mobile high-end
    $netspective_mobile_device_class = THEME_HIGH;
  }
  if (stristr($ua, 'Series60/5.0') || stristr($ua, 'Maemo') || preg_match("/(" . _netspective_mobile_touch_ua_contains() . ")/i", $ua) ) {
    // mobile high-end with touch
    $netspective_mobile_device_class = THEME_TOUCH;
  }

  return $netspective_mobile_device_class;
}

function _netspective_mobile_setcookie_device_class($netspective_mobile_device_class, $touch = FALSE) {
  $mobile_detection = theme_get_setting('netspective_mobile_native');
  if ($mobile_detection) {
    // force our class into the cookie
    setcookie('mobileplugin_group', $netspective_mobile_device_class .','. ($touch ? '1' : '0'), 0, '/');
//    setcookie('mobileplugin_group', $netspective_mobile_device_class .','. ($touch ? '1' : '0'), 0, '/', $_SERVER['HTTP_HOST']);
    
		// make sure the global variable is updated
		global $mobileplugin_group;
    $mobileplugin_group = $netspective_mobile_device_class;
  }
}

/*
 * Other high-end devices with touch that we want to provide with the best theme
 */
function _netspective_mobile_touch_ua_contains() {
  return implode("|", array(
    'ipod',
    'webos',
    'iphone',
  ));
}

/*
 * Other high-end devices that we want to provide with the best theme
 */
function _netspective_mobile_ua_contains() {
  return implode("|", array(
    'android',
  ));
}

function _netspective_mobile_get_doctype() {
  $doctype = '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN"
"http://www.wapforum.org/DTD/xhtml-mobile10.dtd">';
  if (_netspective_mobile_device_detection() == THEME_HIGH
      || _netspective_mobile_device_detection() == THEME_TOUCH ) {
    $doctype = '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.2//EN"
"http://www.openmobilealliance.org/tech/DTD/xhtml-mobile12.dtd">';
  }
  
  return $doctype;
}

function netspective_mobile_settings_css_filename() {
  $theme_image_color = theme_get_setting('theme_image_colors');
  switch($theme_image_color) {
    case 1:
      return 'blue';
      break;
    case 2:
      return 'red';
      break;
    case 3:
      return 'grey';
      break;
    case 0:
    default:
      return 'green';
      break;
  }
}

function _netspective_mobile_force_mobile_group($force_update = FALSE) {
  $mobile_detection = theme_get_setting('netspective_mobile_native');
  if (!$mobile_detection) {
    // nothing to do
    return;
  }
  $mobile_device_groups = array(
    THEME_HIGH,
    THEME_TOUCH,
    THEME_MID,
    THEME_LOW,
  );
  if ($force_update) {
    $update = TRUE;
  } else if (!isset($_COOKIE['mobileplugin_group']) ) {
    $update = TRUE;
  } else if (isset($_COOKIE['mobileplugin_group']) ) {
    list($group, $touch) = explode(',', $_COOKIE['mobileplugin_group']);
    if (!in_array($group, $mobile_device_groups)) {
      $update = TRUE;
    } else {
      $update = FALSE;
    }
  } else {
    $update = FALSE;
  }

  if ($update) {
    $device_group = _netspective_mobile_device_detection();
    $has_touch = ($device_group==THEME_TOUCH) ? TRUE:FALSE;
    _netspective_mobile_setcookie_device_class($device_group, $has_touch);
  }
}

function _netspective_mobile_filter_css($css) {
	$new_css_list = array();
	foreach($css as $css_media => $css_type_array) {
		// leave any CSS specific for handhelds untouched
		if ($css_media == 'handheld') {
			$new_css_list[$css_media] = $css_type_array;
			continue;
		}
		foreach($css_type_array as $css_type=>$css_filename_array) {
			foreach($css_filename_array as $css_filename => $val) {
				// Preserve CSS set by netspective_mobile theme
				if (stristr($css_filename, 'netspective_mobile')) {
					$new_css_list[$css_media][$css_type][$css_filename] = 1;
				}
			}
		}
	}
	return drupal_get_css($new_css_list);
}

function netspective_mobile_preprocess_agent_list(&$vars) {
  
  
}

function netspective_mobile_preprocess_agent_presentation_list(&$vars) {
  
  
}

function netspective_mobile_preprocess_agent_device(&$vars) {
	drupal_add_js('modules/fluent_agent_presentation/js/'.$vars['domain'].'.js');

  
}

function _netspective_mobile_filter_js($js) {
	$new_js_list = array();
	foreach($js as $js_type => $js_filename_array) {
		if ($js_type == 'inline') {
			// leave untouched
			$new_js_list[$js_type] = $js[$js_type];
			continue;
		}
		foreach($js_filename_array as $js_filename => $vals) {
			// only allow JS that came from netspective_mobile theme
			if (stristr($js_filename, 'netspective_mobile')) {
				$new_js_list[$js_type][$js_filename] = $js[$js_type][$js_filename];
			}
		}
	}
	return drupal_get_js('header', $new_js_list);
}

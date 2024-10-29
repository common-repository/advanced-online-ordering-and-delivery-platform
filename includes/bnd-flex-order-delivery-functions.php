<?php
/**
 * Template Functions
 *
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * 
 * @since 1.0.0
 * @package Bnd_Flex_Order_Delivery
 * @subpackage Bnd_Flex_Order_Delivery/includes
 * @author BuyNowDepot
 */
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Returns the path to the templates directory
 *
 * @since 1.0.0
 * @return string
 */
function buynowdepot_get_templates_dir() {
	return BUYNOWDEPOT_PLUGIN_DIR . 'templates';
}


/**
 * Retrieves a template part
 *
 * @since 1.0
 *
 * Taken from bbPress
 *
 * @param string $slug
 * @param string $name Optional. Default null
 * @param bool   $load
 *
 * @return string
 *
 * @uses buynowdepot_locate_template()
 * @uses load_template()
 * @uses get_template_part()
 */
function buynowdepot_get_template_part( $slug, $name = null, $load = true ) {

    $template_file = BUYNOWDEPOT_TEMPLATE_DIR.$slug . '.php';
	// Return the part that is found
	return load_template($template_file, $load);
}

/**
 * Retrieve the name of the highest priority template file that exists.
 *
 * Searches in the STYLESHEETPATH before TEMPLATEPATH so that themes which
 * inherit from a parent theme can just overload one file. If the template is
 * not found in either of those, it looks in the theme-compat folder last.
 *
 * Taken from bbPress
 *
 * @since 1.0.0
 *
 * @param string|array $template_names Template file(s) to search for, in order.
 * @param bool $load If true the template file will be loaded if it is found.
 * @param bool $require_once Whether to require_once or require. Default true.
 *   Has no effect if $load is false.
 * @return string The template filename if one is located.
 */
function buynowdepot_locate_template( $template_names, $load = false, $require_once = true ) {
	// No file found yet
	$located = false;
	// Try to find a template file
	foreach ( (array) $template_names as $template_name ) {
		// Continue if template is empty
		if ( empty( $template_name ) )
			continue;

		// Trim off any slashes from the template name
		$template_name = ltrim( $template_name, '/' );
		// try locating this template file by looping through the template paths
		foreach( buynowdepot_get_theme_template_paths() as $template_path ) {
			if( file_exists( $template_path . $template_name ) ) {
				$located = $template_path . $template_name;
				break;
			}
		}

		if( $located ) {
			break;
		}
	}

	if ( ( true == $load ) && ! empty( $located ) )
		load_template( $located, $require_once );

	return $located;
}

/**
 * Returns a list of paths to check for template locations
 *
 * @since 1.0.0
 * @return mixed|void
 */
function buynowdepot_get_theme_template_paths() {

	$template_dir = buynowdepot_get_theme_template_dir_name();

	$file_paths = array(
		1 => trailingslashit( get_stylesheet_directory() ) . $template_dir,
		10 => trailingslashit( get_template_directory() ) . $template_dir,
		100 => buynowdepot_get_templates_dir()
	);
	error_log($template_dir);
	// sort the file paths based on priority
	ksort( $file_paths, SORT_NUMERIC );
	return array_map( 'trailingslashit', $file_paths );
}

/**
 * Returns the template directory name.
 *
 * Themes can filter this by using the bnd_templates_dir filter.
 *
 * @since 1.0.0
 * @return string
*/
function buynowdepot_get_theme_template_dir_name() {
	return trailingslashit( apply_filters( 'bnd_templates_dir', 'bnd_flex_order_delivery' ) );
}

function buynowdepot_get_page_url($page_name) {
    $current_options = (array)get_option('bnd_settings');
    return get_post_permalink($current_options[$page_name]);
}

function buynowdepot_get_image_url($link) {
    /*if (substr($link,0,4)=="http") {
        return $link;
    }
    $current_options = (array)get_option('bnd_settings');
    $image_base_url = $current_options["image_base_url"];
    $cdn_link="";
    if(isset( $current_options["cdn_for_images"])) {
        $cdn_link = $current_options["cdn_for_images"];
    } 
    if(isset($cdn_link) && !empty($cdn_link)) {
        $image_base_url = $cdn_link;
    }
    $image_url =  esc_url($image_base_url."/".$link);
    $headers = get_headers($image_url);
    if (strpos($headers[0],"404")) {
        $image_url = esc_url($image_base_url."/no-item.jpg");
    }
    return $image_url;*/
    $current_options = (array)get_option('bnd_settings');
    $image_base_url = $current_options["image_base_url"];
    return $image_base_url."/no-item.jpg";
}

function buynowdepot_format_price($price) {
    return "$".number_format($price/100, 2);
}

function buynowdepot_get_post_array() {
    $params = array();
    foreach ($_POST as $key => $value) {
        $params[$key]=sanitize_text_field($value);
    }
    return $params;
}

function get_lat_lng($code){
    $BndSettings = (array)get_option("bnd_settings");
    $mapsApiKey = $BndSettings["google_maps_api_key"];
    $query = "https://maps.google.com/maps/api/geocode/json?address=".urlencode($code)."&output=json&key=".$mapsApiKey;
    $mapdata = file_get_contents($query);
    // if data returned
    if($mapdata){
        // convert into readable format
        $data = json_decode($mapdata);
        if (!empty($data->results)) {
            $lat = $data->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
            $long = $data->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
            return array('lat'=>$lat,'lng'=>$long);
        }
        return false;
    }else{
        return false;
    }
}

function buynowdepot_set_error( $error_id, $error_message ) {
    $errors = buynowdepot_get_errors();
    if ( ! $errors ) {
        $errors = array();
    }
    $errors[ $error_id ] = $error_message;
    Bnd_Flex_Order_Delivery_Session::instance()->set("bnd_errors", $errors);
}

function buynowdepot_get_errors() {
    $errors = Bnd_Flex_Order_Delivery_Session::instance()->get( 'bnd_errors' );
    $errors = apply_filters( 'bnd_errors', $errors );
    return $errors;
}

/**
 * Clears all stored errors.
 */
function buynowdepot_clear_errors() {
    Bnd_Flex_Order_Delivery_Session::instance()->set( 'bnd_errors', NULL );
}

/**
 * Removes (unsets) a stored error
 */
function buynowdepot_unset_error( $error_id ) {
    $errors = buynowdepot_get_errors();
    if ( $errors ) {
        unset( $errors[ $error_id ] );
        Bnd_Flex_Order_Delivery_Session::instance()->set( 'bnd_errors', $errors );
    }
}


function buynowdepot_print_errors() {
    $errors = buynowdepot_get_errors();
    if ( $errors ) {
        
        $classes = apply_filters( 'bnd_error_class', array(
            'bnd_errors', 'bnd-alert', 'bnd-alert-error'
        ) );
        
        if ( ! empty( $errors ) ) {
            echo '<div class="' . implode( ' ', $classes ) . '">';
            // Loop error codes and display errors
            foreach ( $errors as $error_id => $error ) {
                
                echo '<p class="bnd_error" id="bnd_error_' . $error_id . '"><strong>' . __( 'Error', 'restropress' ) . '</strong>: ' . $error . '</p>';
                
            }
            
            echo '</div>';
        }
        
        buynowdepot_clear_errors();
        
    }
}

add_action( 'buynowdepot_print_errors', 'buynowdepot_print_errors' );

add_action('set_current_user', 'buynowdepot_hide_admin_bar');

function buynowdepot_hide_admin_bar() {
    if (!current_user_can('edit_posts')) {
        show_admin_bar(false);
    }
}

function prepare_message_from_template($name, $data) {
    $model = Bnd_Flex_Order_Delivery_Container::instance()->getDb();
    $messageTemplate = $model->getMessageTemplateByName("user_registration_merchant");
    $paramlist = explode(",",$messageTemplate->param_list);
    $message = $messageTemplate->template_text;
    foreach ($paramlist as $param) {
        $message = str_replace('{'.$param.'}', $data[$param], $message);
    }
    return $message;
}

function pointInPolygon($point, $polygon, $pointOnVertex = true) {
        
    // Transform string coordinates into arrays with x and y values
    $point = pointStringToCoordinates($point);
    $vertices = array();
    foreach ($polygon as $vertex) {
        $vertices[] = pointStringToCoordinates($vertex);
    }
    
    // Check if the lat lng sits exactly on a vertex
    if ($pointOnVertex == true and pointOnVertex($point, $vertices) == true) {
        return "vertex";
    }
    
    // Check if the lat lng is inside the polygon or on the boundary
    $intersections = 0;
    $vertices_count = count($vertices);
    
    for ($i=1; $i < $vertices_count; $i++) {
        $vertex1 = $vertices[$i-1];
        $vertex2 = $vertices[$i];
        if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $point['y'] and $point['x'] > min($vertex1['x'], $vertex2['x']) and $point['x'] < max($vertex1['x'], $vertex2['x'])) { // Check if point is on an horizontal polygon boundary
            return "boundary";
        }
        if ($point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] <= max($vertex1['y'], $vertex2['y']) and $point['x'] <= max($vertex1['x'], $vertex2['x']) and $vertex1['y'] != $vertex2['y']) {
            $xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x'];
            if ($xinters == $point['x']) { // Check if lat lng is on the polygon boundary (other than horizontal)
                return "boundary";
            }
            if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) {
                $intersections++;
            }
        }
    }
    // If the number of edges we passed through is odd, then it's in the polygon.
    if ($intersections % 2 != 0) {
        return "inside";
    } else {
        return "outside";
    }
}

function pointOnVertex($point, $vertices) {
    foreach($vertices as $vertex) {
        if ($point == $vertex) {
            return true;
        }
    }
    
}

function pointStringToCoordinates($pointString) {
    $coordinates = explode(" ", $pointString);
    return array("x" => $coordinates[0], "y" => $coordinates[1]);
}

/**
 * Change the custom logo URL
 */
function buynowdepot_custom_logo_link() {
    
    // The logo
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $html ="";
    // If has logo
    if ( $custom_logo_id ) {
        
        // Attr
        $custom_logo_attr = array(
            'class'    => 'custom-logo',
            'itemprop' => 'logo',
        );
        
        // Image alt
        $image_alt = get_post_meta( $custom_logo_id, '_wp_attachment_image_alt', true );
        if ( empty( $image_alt ) ) {
            $custom_logo_attr['alt'] = get_bloginfo( 'name', 'display' );
        }
        
        // Get the image
        $html = sprintf( '<a href="%1$s" class="custom-logo-link" rel="home" itemprop="url">%2$s</a>',
            esc_url(buynowdepot_get_page_url('bnd-menuitems')),
            wp_get_attachment_image( $custom_logo_id, 'full', false, $custom_logo_attr )
            );
        
    }  
    // Return
    return $html;
}

add_filter( 'get_custom_logo', 'buynowdepot_custom_logo_link' );

function unhook_parent_style() {
    wp_dequeue_style(get_current_theme() );
    wp_deregister_style(get_current_theme() );
}

add_action('wp_enqueue_styles', 'unhook_parent_style', 11);

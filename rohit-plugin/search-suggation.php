<?php
/*
Plugin Name: Search suggation Box
Description: This plugin is search suggation
Version: 1.0
Author: Rohit Maurya
*/


if(!defined('ABSPATH')){
    header('Location:/');
    die();
}

function my_plugin_activation(){
}
register_activation_hook( __FILE__, 'my_plugin_activation' );

function my_plugin_deactivation(){
    //
}
register_deactivation_hook(__FILE__, 'my_plugin_deactivation');

function my_custom_script(){
$path = plugins_url('js/main.js',__FILE__);
$depn = array('jquery');
$ver = filemtime(plugin_dir_path(__FILE__).'js/main.js');
wp_enqueue_script('my-custom-js',$path,$depn,$ver,true);
wp_add_inline_script('my-custom-js', 'var ajaxUrl ="'.admin_url('admin-ajax.php').'";','before');
}
add_action('wp_enqueue_scripts','my_custom_script');
add_action('admin_enqueue_scripts','my_custom_script');

function my_posts(){
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => '2'
    );

    $query = new WP_Query($args);
    ob_start();
    if($query->have_posts()){
    ?>
    <ul>
        <?php
        while($query->have_posts()){
            $query->the_post();

            echo '<li>'.get_the_title().'</li>';
        }
        ?>
    </ul>
    <?php
    }
    wp_reset_postdata();
    $html = ob_get_clean();
    return $html;
}

add_shortcode('my-post','my_posts');


function search_s(){
    include 'main-page.php';
}

add_shortcode('search','search_s');
add_action('wp_ajax_my_search_func','my_search_func');
add_action('wp_ajax_nopriv_my_search_func','my_search_func');
function my_search_func(){
    $search_term = $_POST['keyword'];
    $result = "";
    if(isset($search_term)){
        if((get_option('post') == '1' && get_option('page') == '1' && get_option('product') == '1' && get_option('other') == '1') or (get_option('post') == '0' && get_option('page') == '0' && get_option('product') == '0' && get_option('other') == '0')){
            $result = "";
        }
        elseif(get_option('post') == '1'){
            $result = "post";
        }elseif(get_option('page') == '1'){
            $result = "page";
        }elseif(get_option('product') == '1'){
            $result = "product";
        }elseif(get_option('other') == '1'){
            $result = "other";
        }
    $args = array(
        'post_type' => $result,
        's' => $search_term,
    );
}else{
    echo "No record found";
}
    $query = new WP_Query($args);
    ob_start();
    if($query->have_posts()){
    ?>
    <ul>
        <?php
        while($query->have_posts()){
            $query->the_post();
            
                echo '<li style="list-style:none;"><a href="'.get_permalink().'">'.get_the_title().'<img height="100px" width="100px" src="'.get_the_post_thumbnail_url().'"</a></li>';
                // echo "<h1> $result</h1>";
                echo '<p>'.$article_data = substr(get_the_content(), 0, 100).'</p>';
        
        }
        ?>
    </ul>
    <?php
    }
    wp_reset_postdata();
    $html = ob_get_clean();
    echo $html;
    die();
}


 //Settings menu

// Add a settings page to the admin menu
function my_plugin_add_settings_page() {
    add_menu_page('My Plugin Settings','My Plugin Setting','manage_options','my-plugin-settings','my_plugin_render_settings_page','',5);
   
}
add_action('admin_menu', 'my_plugin_add_settings_page');

// Callback to render the settings page
function my_plugin_render_settings_page() {
   ?>
    <div class="wrap">
        <form method="post" action="options.php">
            <?php settings_fields('my-plugin-settings-group'); ?>
            <?php do_settings_sections('my-plugin-settings'); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}


// Register settings and fields
function my_plugin_register_settings() {
    register_setting('my-plugin-settings-group', 'post'); // Setting name and option name
    register_setting('my-plugin-settings-group', 'page');
    register_setting('my-plugin-settings-group', 'product');
    register_setting('my-plugin-settings-group', 'other');
    add_settings_section(
        'my-plugin-section',    // Section ID
        'Search Setting',     // Section title
        'my_plugin_section_callback', // Callback function to render section
        'my-plugin-settings'    // Page slug
    );
    add_settings_field(
        'post',  // Field ID
        '',           // Field title
        'my_plugin_enable_feature_callback', // Callback function to render field
        'my-plugin-settings',       // Page slug
        'my-plugin-section'         // Section ID
    );

    add_settings_field(
        'page',
        '',           
        'my_plugin_enable_feature_callback2', 
        'my-plugin-settings',     
        'my-plugin-section'        
    );

    add_settings_field(
        'product',
        '',           
        'my_plugin_enable_feature_callback3', 
        'my-plugin-settings',     
        'my-plugin-section'        
    );

    add_settings_field(
        'other',
        '',           
        'my_plugin_enable_feature_callback4', 
        'my-plugin-settings',     
        'my-plugin-section'        
    );

}
add_action('admin_init', 'my_plugin_register_settings');

function my_plugin_section_callback() {
    echo '<p>Customize the feature settings here.</p>';
}

// Callback to render checkbox field
function my_plugin_enable_feature_callback() {
    $option_value = get_option('post'); // Get the current value of the checkbox
    ?>
    <label>
        <input type="checkbox" name="post" value="1" <?php checked(1, $option_value); ?> />
        Post
    </label>
    
    <?php
}

function my_plugin_enable_feature_callback2() {
    $option_value = get_option('page'); 
    ?>
    <label>
        <input type="checkbox" name="page" value="1" <?php checked(1, $option_value); ?> />
        Page
    </label>
    
    <?php
}

function my_plugin_enable_feature_callback3() {
    $option_value = get_option('product');
    ?>
    <label>
        <input type="checkbox" name="product" value="1" <?php checked(1, $option_value); ?> />
        Product
    </label>

    <?php
}

function my_plugin_enable_feature_callback4() {
    $option_value = get_option('other');

    ?>
    <label>
        <input type="checkbox" name="other" value="1" <?php checked(1, $option_value); ?> />
        Other
    </label>
    
    <?php
}

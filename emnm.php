<?php

/**
 * Plugin Name:       Easy Multilevel Nav Menu
 * Description:       Easily create multi-level responsive nav menus with simple shortcodes.
 * Version:           0.0.2
 * Requires at least: 5.0
 * Tested Up To:      6.0
 * Requires PHP:      7.0
 * Author:            Byvex Team
 * Author URI:        https://byvex.com/
 * Text Domain:       emnm
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

class EasyMultilevelNavMenu {
    protected $plugin_name;
    protected $plugin_slug;
    protected $plugin_version;

    function __construct(){
        $this->plugin_name = 'Easy Multilevel Nav Menu';
        $this->plugin_slug = 'emnm';
        $this->plugin_version = '0.0.2';

        add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array($this, 'add_action_links'));
        add_filter( 'plugin_row_meta', array($this, 'add_plugin_row_meta'), 10, 2 );
        add_shortcode( 'easy_multi_menu',  array($this, 'easy_multi_menu_shortcode') );
        add_shortcode( 'easy_multi_menu_btn',  array($this, 'easy_multi_menu_btn_shortcode') );
        add_action( 'wp_enqueue_scripts', array($this, 'enqueue_public_assets' ) );
        add_action( 'admin_menu', array($this, 'add_plugin_page' ) );
    }

    function add_action_links( $links ){
        $mylinks = array( '<a href="' . admin_url( 'themes.php?page=' . $this->plugin_slug . '-plugin-page' ) . '">Settings</a>',);
        return array_merge( $links, $mylinks );
    }

    function add_plugin_row_meta( $links, $file ){
        if ( plugin_basename( __FILE__ ) == $file ) {
            $row_meta = array(
                'contact_support' => '<a href="' . esc_url( 'https://www.byvex.com/contact' ) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr( 'Contact Support') . '">' . esc_html( 'Contact Support') . '</a>',
            );
            return array_merge( $links, $row_meta );
        }
        return (array) $links;
    }

    function easy_multi_menu_shortcode( $atts = array(), $content = null ){
        // normalize attribute keys, lowercase
        $atts = array_change_key_case( (array) $atts, CASE_LOWER );
		$tag = '';
        $html = '';
        $html_menu = '';

        // override default attributes with user attributes
        $values = shortcode_atts(
            array(
                'name' => '',
                'breakpoint' => '992',
                'z-index' => '101',
                'sidebar-width' => '285',
                'bg-color' => '#222',
                'text-color' => '#eee',
            ), $atts, $tag
        );

        // check for empty menu name
        if( trim($values['name']) == '' ){
            return '<p>Please provide menu name in shortcode.</p>';
        }

        $html_menu = wp_nav_menu(array(
            'menu' => esc_html($values['name']),
            'container_class' =>  $this->plugin_slug . '_menu_container',
            'before' => '<div class="' . $this->plugin_slug . '_link_wrap">', // text before <a> tag
            'after' => '<button class="' . $this->plugin_slug . '_link_btn" type="button" title="Open Sub Menu"><svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 448 512"><path d="M224 416c-8.188 0-16.38-3.125-22.62-9.375l-192-192c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L224 338.8l169.4-169.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-192 192C240.4 412.9 232.2 416 224 416z"/></svg></button></div>', // text after <a> tag
            'item_spacing' => 'discard',
            'echo' => false,
        ));

        $menu_slug = strtolower($values['name']);
        $menu_slug = str_replace(' ','', $menu_slug);

        $html .= '<div id="' . $this->plugin_slug . '_' . $menu_slug . '" data-breakpoint="' . $values['breakpoint'] . '" class="' . $this->plugin_slug . '_menu ' . $this->plugin_slug . '_' . $menu_slug . '" style="z-index:' . $values['z-index'] . ';">' . $html_menu . '</div>';

        $html_style = '<style type="text/css">';
        $html_style .= '.' . $this->plugin_slug . '_' . $menu_slug . ' ul{max-width:' . $values['sidebar-width'] . 'px;background-color:'. $values['bg-color'] .'}';
        $html_style .= '.' . $this->plugin_slug . '_' . $menu_slug . '.emnm_desktop ul{max-width:auto}';
        $html_style .= '.' . $this->plugin_slug . '_' . $menu_slug . ' ul li .emnm_link_wrap{background-color:' . $values['bg-color'] . '}';
        $html_style .= '.' . $this->plugin_slug . '_' . $menu_slug . ' ul li .emnm_link_wrap .emnm_link_btn{background-color:' . $values['bg-color'] . ';color:' . $values['text-color'] . '}';
        $html_style .= '.' . $this->plugin_slug . '_' . $menu_slug . ' ul li a{color:' . $values['text-color'] . '}';
        $html_style .= '</style>';
        $html .= $html_style;
        return $html;
    }

    function easy_multi_menu_btn_shortcode( $atts = array(), $content = null ){
        // normalize attribute keys, lowercase
        $atts = array_change_key_case( (array) $atts, CASE_LOWER );
		$tag = '';
        $html = '';

        // override default attributes with user attributes
        $values = shortcode_atts(
            array(
                'name' => '',
                'class' => 'wp-block-button',
                'title' => 'Open menu',
            ), $atts, $tag
        );

        // check for empty menu name
        if( trim($values['name']) == '' ){
            return '<p>Please provide menu name in shortcode.</p>';
        }

        $menu_slug = strtolower($values['name']);
        $menu_slug = str_replace(' ','', $menu_slug);

        $html .= '<button type="button" id="' . $this->plugin_slug . '_' . $menu_slug . '_btn" class="' . $this->plugin_slug . '_menu_btn '. $values['class'] . '" data-menu="' . $this->plugin_slug . '_' . $menu_slug . '" title="' . $values['title'] . '">';
        if( trim($content) == '' ){
            $html .= 'Menu';
        } else {
            $html .= $content;
        }
        $html .= '</button>';

        return $html;
    }

    function enqueue_public_assets(){
        $path = 'public/css/style.css';
        wp_enqueue_style( $this->plugin_slug . '-style', plugin_dir_url( __FILE__ ) . '' . $path, array(), filemtime( plugin_dir_path(__FILE__) . '' . $path ) );
        $path = 'public/js/main.js';
        wp_enqueue_script( $this->plugin_slug . '-script', plugin_dir_url( __FILE__ ) . '' . $path, array(), filemtime( plugin_dir_path(__FILE__) . '' . $path ), true );
    }

    function add_plugin_page(){
        add_theme_page(
            esc_html('Easy Multilevel Menu'),
            esc_html('Easy Multilevel Menu'),
            'edit_theme_options',
            $this->plugin_slug . '-plugin-page',
            array( $this, 'show_plugin_page')
        );
    }

    function show_plugin_page(){ ?>
        <div class="wrap">
            <h1><strong><?php echo $this->plugin_name; ?></strong></h1>
            <p>You can create multilevel responsive menu. To show menu, you will need 2 shortcodes; one is for the menu and another is for the button to toggle menu below responsive breakpoint.</p>
            <h2><strong>Features:</strong></h2>
            <ol>
                <li>No use !important in styles, so you can easily change styles.</li>
                <li>No use of database to increase speed.</li>
                <li>Easily customizable with shortcode.</li>
                <li>Independent menu toggle button, can be placed anywhere on page.</li>
            </ol>
            <h2><strong>Complete Shortcode Example with all attributes</strong></h2>
            <ul>
                <p>For Displaying Menu:<br /><strong><code>[easy_multi_menu name="Primary Menu" breakpoint="992" z-index="101" sidebar-width="285" bg-color="#181818" text-color="#eee"]</code></strong></p>
                <p>For Displaying Menu Button:<br /><strong><code>[easy_multi_menu_btn name="Primary Menu" class="btn btn-primary" title="Open Menu"] &lt;i class="fa-solid fa-bars"&gt;&lt;/i&gt; [/easy_multi_menu_btn]</code></strong></p>
            </ul>
            <h2>Shortcode Attributes Explanation</h2>
            <p>Only <strong><code>name</code></strong> is required in both shortcodes, every other attribute is optional.</p>
            <p><strong>For menu shortcode</strong></p>
            <ol>
                <li><strong>name</strong>: Name of the menu to be displayed</li>
                <li><strong>breakpoint</strong>: Minimum viewport width to for showing desktop version of menu in pixels.</li>
                <li><strong>z-index</strong>: CSS z-index value for parent container of menu.</li>
                <li><strong>sidebar-width</strong>: Width of sidebar mobile menu in pixels.</li>
                <li><strong>bg-color</strong>: Background color of menu, you can use hex values, rgba, etc.</li>
                <li><strong>text-color</strong>: Text color of menu, you can use hex values, rgba, etc.</li>
            </ol>
            <p><strong>For menu button shortcode</strong></p>
            <ol>
                <li><strong>name</strong>: Name of the menu to be displayed</li>
                <li><strong>class</strong>: Class to applied on button</li>
                <li><strong>title</strong>: Title attribute for the &lt;button&gt; tag.</li>
                <li>You can add content for button by using the closing tag of shortcode, like the example above uses Font Awesome icon.</li>
            </ol>
            <h2>Instructions for use in Visual Editors:</h2>
            <p>The following steps are for use in editors like, Gutenberg Editor, Elementor, Divi, WP Bakery, etc.</p>
            <ol>
                <li>Create a new menu and copy its name or copy the name of exiting menu.</li>
                <li>Paste this shortcode <strong><code>[easy_multi_menu name="Primary Menu"]</code></strong> for menu and replace the value of <strong><code>name</code></strong> with the menu name you just copied.</li>
                <li>Paste this shortcode <strong><code>[easy_multi_menu_btn class="btn btn-dark" name="Primary Menu"]</code></strong> for button to toggle menu for small screens, replace the value of <strong><code>name</code></strong> with the menu name.</li>
                <li>Make sure that value of <strong><code>name</code></strong> in both shortcode is same, otherwise button will not work for that menu.</li>
            </ol>

            <h2>Instructions for use in PHP code:</h2>
            <p>The following steps are for use in php code, like template files, hooks, filters etc.</p>
            <ol>
                <li>Use shortcode like this in php for menu <strong><code>&lt;?php echo do_shortcode('[easy_multi_menu name="Primary Menu"]'); ?&gt;</code></strong></li>
                <li>Use shortcode like this in php for menu toggle btn <strong><code>&lt;?php echo do_shortcode('[easy_multi_menu_btn name="Primary Menu"]' . '&lt;i class="fa-solid fa-bars"&gt;&lt;/i&gt;' . '[/easy_multi_menu_btn]'); ?&gt;</code></strong></li>
            </ol>

        </div>
    <?php }
}

$emnm = new EasyMultilevelNavMenu();

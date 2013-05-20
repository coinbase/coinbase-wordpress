<?php
/*
Plugin Name: Coinbase
Description: Enables a shortcode for adding Coinbase payment buttons to your WordPress site.
Version: 0.1
Author: Coinbase
Author URI: https://coinbase.com
*/

define('COINBASE_PATH', plugin_dir_path( __FILE__ ));
define('COINBASE_URL', plugins_url( '', __FILE__ ));

require_once(plugin_dir_path( __FILE__ ) . 'coinbase-php/lib/Coinbase.php');
require_once(plugin_dir_path( __FILE__ ) . 'widget.php');



class WP_Coinbase {

    private $plugin_path;
    private $plugin_url;
    private $l10n;
    private $wpsf;

    function __construct() {	
        $this->plugin_path = plugin_dir_path( __FILE__ );
        $this->plugin_url = plugin_dir_url( __FILE__ );
        $this->l10n = 'wp-settings-framework';
        add_action( 'admin_menu', array(&$this, 'admin_menu'), 99 );
        add_action('admin_enqueue_scripts', array(&$this, 'admin_styles'), 1);
        add_action('admin_enqueue_scripts', array(&$this, 'widget_scripts'));
        
        // Include and create a new WordPressSettingsFramework
        require_once( $this->plugin_path .'wp-settings-framework.php' );
        $this->wpsf = new WordPressSettingsFramework( $this->plugin_path .'settings/coinbase.php' );
        // Add an optional settings validation filter (recommended)
        add_filter( $this->wpsf->get_option_group() .'_settings_validate', array(&$this, 'validate_settings') );

        add_shortcode('coinbase_button', array(&$this, 'shortcode'));
    }
    
    function admin_menu() {
        add_submenu_page( 'options-general.php', __( 'Coinbase', $this->l10n ), __( 'Coinbase', $this->l10n ), 'update_core', 'coinbase', array(&$this, 'settings_page') );
    }
    
    function settings_page() {
	    // Your settings page
	    ?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32"></div>
			<h2>Coinbase</h2>
			<?php 
			// Output your settings form
			$this->wpsf->settings(); 
			?>
		</div>
		<?php
		
	}
	
	function validate_settings( $input ) {
		$output = $input;
		$output['coinbase_general_api_key'] = $input['coinbase_general_api_key'];
    	return $output;
	}

    function shortcode( $atts, $content = null ) {
        $defaults = array(
              'name' => 'test',
              'price_string' => '1.23',
              'price_currency_iso' => 'USD',
              'custom' => 'Order123',
              'description' => 'Sample description',
              'type' => 'buy_now',
              'style' => 'buy_now_large');

        $args = shortcode_atts($defaults, $atts);
        $api_key = wpsf_get_setting( 'coinbase', 'general', 'api_key' );

        $coinbase = new Coinbase($api_key);
        $button = $coinbase->createButtonWithOptions($args)->embedHtml;

        return $button;
    }

    public function admin_styles() {
      wp_enqueue_style( 'coinbase-admin-styles', COINBASE_URL .'/css/coinbase-admin.css', array(), '1', 'all' );
    }

    public function widget_scripts( $hook ) {
      if( 'widgets.php' != $hook )
        return;
      wp_enqueue_script( 'coinbase-widget-scripts', COINBASE_URL .'/js/coinbase-widget.js', array('jquery'), '', true );
    }

}
new WP_Coinbase();


?>
<?php
/**
 * Plugin Name: Coinbase
 * Plugin URI: https://github.com/coinbase/coinbase-wordpress
 * Description: Add Coinbase payment buttons to your WordPress site.
 * Version: 1.0
 * Author: Coinbase Inc.
 * Author URI: https://coinbase.com
 * License: GPLv2 or later
 */

/* 

Copyright (C) 2014 Coinbase Inc.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

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
    add_action( 'admin_init', array(&$this, 'admin_init'), 99 );
    add_action('admin_enqueue_scripts', array(&$this, 'admin_styles'), 1);
    add_action('admin_enqueue_scripts', array(&$this, 'widget_scripts'));

    // Include and create a new WordPressSettingsFramework
    require_once( $this->plugin_path .'wp-settings-framework.php' );
    $this->wpsf = new WordPressSettingsFramework( $this->plugin_path .'settings/coinbase.php' );

    add_shortcode('coinbase_button', array(&$this, 'shortcode'));
  }

  function admin_menu() {
    add_submenu_page( 'options-general.php', __( 'Coinbase', $this->l10n ), __( 'Coinbase', $this->l10n ), 'update_core', 'coinbase', array(&$this, 'settings_page') );
  }

  function admin_init() {
    register_setting ( 'coinbase', 'coinbase-tokens' );
  }

  function settings_page() {
    $api_key = wpsf_get_setting( 'coinbase', 'general', 'api_key' );
    $api_secret = wpsf_get_setting( 'coinbase', 'general', 'api_secret' );

    ?>
      <div class="wrap">
        <div id="icon-options-general" class="icon32"></div>
        <h2>Coinbase</h2>

    <?php
        $this->wpsf->settings();
    ?>
      </div>
    <?php
  }

  function shortcode( $atts, $content = null ) {
    $defaults = array(
          'name'               => 'test',
          'price_string'       => '1.23',
          'price_currency_iso' => 'USD',
          'custom'             => 'Order123',
          'description'        => 'Sample description',
          'type'               => 'buy_now',
          'style'              => 'buy_now_large',
          'text'               => 'Pay with Bitcoin',
          'choose_price'       => false,
          'variable_price'     => false,
          'price1'             => '0.0',
          'price2'             => '0.0',
          'price3'             => '0.0',
          'price4'             => '0.0',
          'price5'             => '0.0',
    );

    $args = shortcode_atts($defaults, $atts, 'coinbase_button');

    // Clear default price suggestions
    for ($i = 1; $i <= 5; $i++) {
      if ($args["price$i"] == '0.0') {
        unset($args["price$i"]);
      }
    }

    $transient_name = 'cb_ecc_' . md5(serialize($args));
    $cached = get_transient($transient_name);
    if($cached !== false) {
      return $cached;
    }

    $api_key = wpsf_get_setting( 'coinbase', 'general', 'api_key' );
    $api_secret = wpsf_get_setting( 'coinbase', 'general', 'api_secret' );
    if( $api_key && $api_secret ) {
      try {
        $coinbase = Coinbase::withApiKey($api_key, $api_secret);
        $button = $coinbase->createButtonWithOptions($args)->embedHtml;
      } catch (Exception $e) {
        $msg = $e->getMessage();
        error_log($msg);
        return "There was an error connecting to Coinbase: $msg. Please check your internet connection and API credentials.";
      }
      set_transient($transient_name, $button);
      return $button;
    } else {
      return "The Coinbase plugin has not been properly set up - please visit the Coinbase settings page in your administrator console.";
    }
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
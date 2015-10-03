<?php
/**
 * Plugin Name: Coinbase
 * Plugin URI: https://github.com/coinbase/coinbase-wordpress
 * Description: Add Coinbase payment buttons to your WordPress site.
 * Version: 2.0
 * Author: Coinbase Inc.
 * Author URI: https://www.coinbase.com
 * License: MIT
 */

/*

The MIT License (MIT)

Copyright (c) 2015 Coinbase Inc.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

*/

define('COINBASE_PATH', plugin_dir_path( __FILE__ ));
define('COINBASE_URL', plugins_url( '', __FILE__ ));

require_once(plugin_dir_path( __FILE__ ) . '/vendor/autoload.php');
require_once(plugin_dir_path( __FILE__ ) . 'widget.php');

use Coinbase\Wallet\Client;
use Coinbase\Wallet\Configuration;
use Coinbase\Wallet\Resource\Checkout;
use Coinbase\Wallet\Value\Money;
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

  public static function default_checkout_attributes() {
    return array(
      'name'                     => 'test',
      'amount'                   => '1.23',
      'currency'                 => 'USD',
      'description'              => 'Sample description',
      'type'                     => 'order',
      'style'                    => 'buy_now_large',
      'text'                     => '',
      'custom'                   => 'custom',
      'customer_defined_amount'  => false,
      'amount_preset_1'          => '0.0',
      'amount_preset_2'          => '0.0',
      'amount_preset_3'          => '0.0',
      'amount_preset_4'          => '0.0',
      'amount_preset_5'          => '0.0'
    );
  }

  public static function process_checkout_attributes( $args ) {
    // Transform scalar preset amounts into an array of Money
    $price_suggestions = [];
    for ($i = 1; $i <= 5; $i++) {
      if ($args["amount_preset_$i"] == '0.0') {
        // Clear default price suggestions
        unset($args["amount_preset_$i"]);
      } else {
        array_push($price_suggestions, $args["amount_preset_$i"]);
      }
    }

    $args = array_merge($args, array(
      'amount'         => new Money($args['amount'], $args['currency']),
      'metadata'       => array( 'custom' => $args['custom']),
      'amount_presets' => $price_suggestions
    ));

    return $args;
  }

  public function load_or_create_button( $args ) {
    $transient_name = 'cb_ecc_' . md5(serialize($args));
    $cached = get_transient($transient_name);
    if($cached !== false) {
      return $cached;
    }

    $api_key = wpsf_get_setting( 'coinbase', 'general', 'api_key' );
    $api_secret = wpsf_get_setting( 'coinbase', 'general', 'api_secret' );
    if( $api_key && $api_secret ) {
      try {
        $configuration = Configuration::apiKey($api_key, $api_secret);
        $client = Client::create($configuration);
        $checkout = new Checkout($args);
        $client->createCheckout($checkout);
        $button = $checkout->getEmbedHtml();
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

  public function shortcode( $atts, $content = null ) {
    $args = shortcode_atts(self::default_checkout_attributes(), $atts, 'coinbase_button');

    return $this->load_or_create_button(self::process_checkout_attributes($args));
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
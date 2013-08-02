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
        add_action( 'admin_init', array(&$this, 'admin_init'), 99 );
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
    
    function admin_init() {
        register_setting ( 'coinbase', 'coinbase-tokens' );
    }
    
    function settings_page() {
      $redirectUrl = plugins_url( 'coinbase-wordpress/coinbase-redirect.php' );
      $clientId = wpsf_get_setting( 'coinbase', 'general', 'client_id' );
      $clientSecret = wpsf_get_setting( 'coinbase', 'general', 'client_secret' );
      $coinbaseOauth = new Coinbase_OAuth($clientId, $clientSecret, $redirectUrl);
        
	    // Your settings page
      if($_GET['coinbase_code'] != "") {
        // This is a return from the OAuth redirect (coinbase-redirect.php)
        // Store tokens
        $tokens = $coinbaseOauth->getTokens($_GET['coinbase_code']);
        update_option( 'coinbase_tokens', $tokens );
        ?>
        <script type="text/javascript">
        document.location.replace(document.location.toString().split("?")[0] + "?page=coinbase");
        </script>
        <?php
      } else if($_POST['coinbase_reset_tokens']) {
        update_option( 'coinbase_tokens', false );
      }
	    ?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32"></div>
			<h2>Coinbase</h2>
      <?php
      if( get_option( 'coinbase_tokens' ) == false ) {
      ?>
      <h3>Setup</h3>
      <p>First, create an <b>OAuth2 application</b> for this plugin at <a href="https://coinbase.com/oauth/applications">https://coinbase.com/oauth/applications</a>.</p>

      <p>Enter anything in the Name box, and enter <input type="text" value="<?php echo $redirectUrl; ?>"> as the <b>redirect URI</b>.</p>

      <p>Then, copy and paste the <b>Client ID</b> and <b>Client Secret</b> below. Click the 'Save Changes' button.</p>

      <?php 
      if($clientId != "") {
      ?>
      <p>Once you have saved the Client ID and Client Secret, <b>press the button below</b> to authorize the plugin.</p>
      <?php
        $authorizeUrl = $coinbaseOauth->createAuthorizeUrl("buttons");
      ?>
      <p><a href="<?php echo $authorizeUrl; ?>" class="button"><?php _e( 'Authorize Wordpress Plugin' ); ?></a></p>
			<?php 
      }
			// Output your settings form
			$this->wpsf->settings(); 
      } else {
      ?>
      <p>Logged in.</p>
      <p>
        <form action="?page=coinbase" method="post">
          <input type="hidden" name="coinbase_reset_tokens" value="true">
          <input type="submit" value="<?php _e( 'Unlink Coinbase Account' ); ?>">
        </form>
      </p>
      <h3>How to Use</h3>
      <p>You can now use the shortcode <span style="font-family: monospace;">[coinbase_button]</span> to create buttons:</p>
      <p style="font-family: monospace;">[coinbase_button name="Socks" price_string="10.00" price_currency_iso="CAD"]</p>
      <p>You can also create a menu widget from <a href="widgets.php">the Widgets page.</a></p>
      <?php
      }
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
        
      $clientId = wpsf_get_setting( 'coinbase', 'general', 'client_id' );
      $clientSecret = wpsf_get_setting( 'coinbase', 'general', 'client_secret' );
      $coinbaseOauth = new Coinbase_OAuth($clientId, $clientSecret, '');
      $tokens = get_option( 'coinbase_tokens' );
      if($tokens) {
        $coinbase = new Coinbase($coinbaseOauth, $tokens);
        $button = $coinbase->createButtonWithOptions($args)->embedHtml;
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
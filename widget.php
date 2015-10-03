<?php

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

require_once(plugin_dir_path( __FILE__ ) . '/vendor/autoload.php');
use Coinbase\Wallet\Client;
use Coinbase\Wallet\Configuration;
use Coinbase\Wallet\Resource\Checkout;
use Coinbase\Wallet\Value\Money;
use Coinbase\Wallet\Enum\CurrencyCode;

/**
 * Registers the Coinbase Button widget
 *
 */
class Coinbase_Button extends WP_Widget {
  public static $CURRENCIES = array( 'BTC' => 'BTC', 'USD' => 'USD', '--' => '--', 'AED' => 'AED', 'AFN' => 'AFN', 'ALL' => 'ALL', 'AMD' => 'AMD', 'ANG' => 'ANG', 'AOA' => 'AOA', 'ARS' => 'ARS', 'AUD' => 'AUD', 'AWG' => 'AWG', 'AZN' => 'AZN', 'BAM' => 'BAM', 'BBD' => 'BBD', 'BDT' => 'BDT', 'BGN' => 'BGN', 'BHD' => 'BHD', 'BIF' => 'BIF', 'BMD' => 'BMD', 'BND' => 'BND', 'BOB' => 'BOB', 'BRL' => 'BRL', 'BSD' => 'BSD', 'BTN' => 'BTN', 'BWP' => 'BWP', 'BYR' => 'BYR', 'BZD' => 'BZD', 'CAD' => 'CAD', 'CDF' => 'CDF', 'CHF' => 'CHF', 'CLP' => 'CLP', 'CNY' => 'CNY', 'COP' => 'COP', 'CRC' => 'CRC', 'CUP' => 'CUP', 'CVE' => 'CVE', 'CZK' => 'CZK', 'DJF' => 'DJF', 'DKK' => 'DKK', 'DOP' => 'DOP', 'DZD' => 'DZD', 'EEK' => 'EEK', 'EGP' => 'EGP', 'ETB' => 'ETB', 'EUR' => 'EUR', 'FJD' => 'FJD', 'FKP' => 'FKP', 'GBP' => 'GBP', 'GEL' => 'GEL', 'GHS' => 'GHS', 'GIP' => 'GIP', 'GMD' => 'GMD', 'GNF' => 'GNF', 'GTQ' => 'GTQ', 'GYD' => 'GYD', 'HKD' => 'HKD', 'HNL' => 'HNL', 'HRK' => 'HRK', 'HTG' => 'HTG', 'HUF' => 'HUF', 'IDR' => 'IDR', 'ILS' => 'ILS', 'INR' => 'INR', 'IQD' => 'IQD', 'IRR' => 'IRR', 'ISK' => 'ISK', 'JMD' => 'JMD', 'JOD' => 'JOD', 'JPY' => 'JPY', 'KES' => 'KES', 'KGS' => 'KGS', 'KHR' => 'KHR', 'KMF' => 'KMF', 'KPW' => 'KPW', 'KRW' => 'KRW', 'KWD' => 'KWD', 'KYD' => 'KYD', 'KZT' => 'KZT', 'LAK' => 'LAK', 'LBP' => 'LBP', 'LKR' => 'LKR', 'LRD' => 'LRD', 'LSL' => 'LSL', 'LTL' => 'LTL', 'LVL' => 'LVL', 'LYD' => 'LYD', 'MAD' => 'MAD', 'MDL' => 'MDL', 'MGA' => 'MGA', 'MKD' => 'MKD', 'MMK' => 'MMK', 'MNT' => 'MNT', 'MOP' => 'MOP', 'MRO' => 'MRO', 'MUR' => 'MUR', 'MVR' => 'MVR', 'MWK' => 'MWK', 'MXN' => 'MXN', 'MYR' => 'MYR', 'MZN' => 'MZN', 'NAD' => 'NAD', 'NGN' => 'NGN', 'NIO' => 'NIO', 'NOK' => 'NOK', 'NPR' => 'NPR', 'NZD' => 'NZD', 'OMR' => 'OMR', 'PAB' => 'PAB', 'PEN' => 'PEN', 'PGK' => 'PGK', 'PHP' => 'PHP', 'PKR' => 'PKR', 'PLN' => 'PLN', 'PYG' => 'PYG', 'QAR' => 'QAR', 'RON' => 'RON', 'RSD' => 'RSD', 'RUB' => 'RUB', 'RWF' => 'RWF', 'SAR' => 'SAR', 'SBD' => 'SBD', 'SCR' => 'SCR', 'SDG' => 'SDG', 'SEK' => 'SEK', 'SGD' => 'SGD', 'SHP' => 'SHP', 'SLL' => 'SLL', 'SOS' => 'SOS', 'SRD' => 'SRD', 'STD' => 'STD', 'SVC' => 'SVC', 'SYP' => 'SYP', 'SZL' => 'SZL', 'THB' => 'THB', 'TJS' => 'TJS', 'TMM' => 'TMM', 'TND' => 'TND', 'TOP' => 'TOP', 'TRY' => 'TRY', 'TTD' => 'TTD', 'TWD' => 'TWD', 'TZS' => 'TZS', 'UAH' => 'UAH', 'UGX' => 'UGX', 'UYU' => 'UYU', 'UZS' => 'UZS', 'VEF' => 'VEF', 'VND' => 'VND', 'VUV' => 'VUV', 'WST' => 'WST', 'XAF' => 'XAF', 'XCD' => 'XCD', 'XOF' => 'XOF', 'XPF' => 'XPF', 'YER' => 'YER', 'ZAR' => 'ZAR', 'ZMK' => 'ZMK', 'ZWL' => 'ZWL', );
  public static $TYPES      = array('order' => 'Order', 'donation' => 'Donation');
  public static $STYLES     = array('buy_now' => 'Buy Now', 'donation' => 'Donation', 'custom' => 'Custom');
  public static $SIZES      = array('large' => 'Large', 'small' => 'Small');

  /**
   * Register widget with WordPress.
   */
  public function __construct() {
    parent::__construct(
      'coinbase_button', // Base ID
      'Coinbase Button', // Name
      array( 'description' => __( 'Displays a Coinbase button in your sidebar', 'text_domain' ), ) // Args
    );
  }

  /**
   * Front-end display of widget.
   *
   * @see WP_Widget::widget()
   *
   * @param array $args     Widget arguments.
   * @param array $instance Saved values from database.
   */
  public function widget( $args, $instance ) {
    extract( $args );
    $title = apply_filters( 'widget_title', $instance['title'] );

    echo $before_widget;
    if ( ! empty( $title ) )
      echo $before_title . $title . $after_title;

    $button_args = array();

    foreach ($instance as $k => $v) {
      if ($k != 'size' && $k != 'title')
        $button_args[$k] = $v;
    }

    $size = $instance['size'];
    $style = $instance['style'] . '_' . $size;
    $button_args['style'] = $style;

    $button_args = array_merge(WP_Coinbase::default_checkout_attributes(), $button_args);
    $button_args = WP_Coinbase::process_checkout_attributes($button_args);

    $transient_name = 'cb_ecc_' . md5(serialize($button_args));
    $cached = get_transient($transient_name);
    if($cached !== false) {
      // Cached
      echo $cached;
    } else {
      $api_key = wpsf_get_setting( 'coinbase', 'general', 'api_key' );
      $api_secret = wpsf_get_setting( 'coinbase', 'general', 'api_secret' );
      if( $api_key && $api_secret ) {
        try {
          $configuration = Configuration::apiKey($api_key, $api_secret);
          $client = Client::create($configuration);
          $checkout = new Checkout($button_args);
          $client->createCheckout($checkout);
          $button = $checkout->getEmbedHtml();
          set_transient($transient_name, $button);
          echo $button;
        } catch (Exception $e) {
          $msg = $e->getMessage();
          error_log($msg);
          echo "There was an error connecting to Coinbase: $msg. Please check your internet connection and API credentials.";
        }
      } else {
        echo "The Coinbase plugin has not been properly set up - please visit the Coinbase settings page in your administrator console.";
      }
    }

    echo $after_widget;
  }

  /**
   * Sanitize widget form values as they are saved.
   *
   * @see WP_Widget::update()
   *
   * @param array $new_instance Values just sent to be saved.
   * @param array $old_instance Previously saved values from database.
   *
   * @return array Updated safe values to be saved.
   */
  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = esc_attr(strip_tags( $new_instance['title'] ));
    $instance['name'] = esc_attr(strip_tags( $new_instance['name'] ));
    $instance['description'] = esc_attr(strip_tags( $new_instance['description'] ));
    $instance['custom'] = esc_attr(strip_tags( $new_instance['custom'] ));
    $instance['currency'] = esc_attr(strip_tags( $new_instance['currency'] ));
    $instance['type'] = esc_attr(strip_tags( $new_instance['type'] ));
    $instance['style'] = esc_attr(strip_tags( $new_instance['style'] ));
    $instance['size'] = esc_attr(strip_tags( $new_instance['size'] ));
    $instance['text'] = esc_attr(strip_tags( $new_instance['text'] ));

    $amount = $new_instance['amount'];
    if (!is_numeric(substr($amount, 0, 1)))
      $amount = substr($amount, 1);

    $instance['amount'] = (float) $amount;
    $instance['amount'] = (string) $amount;

    return $instance;
  }

  /**
   * Back-end widget form.
   *
   * @see WP_Widget::form()
   *
   * @param array $instance Previously saved values from database.
   */
  public function form( $instance ) {
    $defaults = array(
        'title' => '',
          'name' => 'Test',
          'amount' => '1.23',
          'currency' => 'USD',
          'custom' => '',
          'description' => 'Sample description',
          'text' => '',
          'type' => 'order',
          'style' => 'buy_now',
          'size' => 'large');
    extract(wp_parse_args($instance, $defaults));
    $amount = (float) $amount;

    ?>
    <p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Widget Title:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
    </p>

    <p>
    <label for="<?php echo $this->get_field_id( 'name' ); ?>"><?php _e( 'Item Name:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'name' ); ?>" name="<?php echo $this->get_field_name( 'name' ); ?>" type="text" value="<?php echo esc_attr( $name ); ?>" />
    </p>

    <p>
    <label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Description:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>" type="text" value="<?php echo esc_attr( $description ); ?>" />
    </p>

    <p>
    <label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Button text (custom style only):' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" type="text" value="<?php echo esc_attr( $text ); ?>" />
    </p>

    <p>
      <label for="<?php echo $this->get_field_id( 'currency' ); ?>"><?php _e( 'Amount:' ); ?></label>
      <span>
        <select class="widefat coinbase-currency" id="<?php echo $this->get_field_id( 'currency' ); ?>" name="<?php echo $this->get_field_name('currency'); ?>">
            <?php
            foreach (self::$CURRENCIES as $k => $v) {
              echo '<option value="' . $k . '"'
                . ( $k == $currency ? ' selected="selected"' : '' )
                . '>' . $v . "</option>\n";
            }
            ?>
        </select>

        <input class="widefat coinbase-amount" id="<?php echo $this->get_field_id( 'amount' ); ?>" name="<?php echo $this->get_field_name( 'amount' ); ?>" type="text" value="<?php echo esc_attr( $amount ); ?>" />
      </span>
    </p>

    <p>
    <label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _e( 'Button Type:' ); ?></label>
    <select class="widefat" id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name('type'); ?>">
        <?php
        foreach (self::$TYPES as $k => $v) {
          echo '<option value="' . $k . '"'
            . ( $k == $type ? ' selected="selected"' : '' )
            . '>' . $v . "</option>\n";
        }
        ?>
    </select>
    </p>

    <p>
    <label for="<?php echo $this->get_field_id( 'style' ); ?>"><?php _e( 'Button Style:' ); ?></label>
    <select class="widefat" id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo $this->get_field_name('style'); ?>">
        <?php
        foreach (self::$STYLES as $k => $v) {
          echo '<option value="' . $k . '"'
            . ( $k == $style ? ' selected="selected"' : '' )
            . '>' . $v . "</option>\n";
        }
        ?>
    </select>
    <label for="<?php echo $this->get_field_id( 'size' ); ?>"><?php _e( 'Button Size:' ); ?></label>
    <select class="widefat" id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name('size'); ?>">
        <?php
        foreach (self::$SIZES as $k => $v) {
          echo '<option value="' . $k . '"'
            . ( $k == $size ? ' selected="selected"' : '' )
            . '>' . $v . "</option>\n";
        }
        ?>
    </select>
    </p>

    <!--<a id="coinbase-toggle" class="coinbase-toggle">Show Advanced Options</a>-->
    <div id="coinbase-advanced" class="coinbase-advanced">
      <p>
      <label for="<?php echo $this->get_field_id( 'custom' ); ?>"><?php _e( 'Custom ID:' ); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'custom' ); ?>" name="<?php echo $this->get_field_name( 'custom' ); ?>" type="text" value="<?php echo esc_attr( $custom ); ?>" />
      <small>Optional.  This gets passed through in <a target="_blank" href="https://coinbase.com/docs/merchant_tools/callbacks">callbacks</a> to your site.</small>
      </p>
    </div>

    <?php
  }

} // Widget class

// register the widget
add_action( 'widgets_init', create_function( '', "register_widget( 'Coinbase_Button' );" ) );

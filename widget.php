<?php
/**
 * Registers the Coinbase Button widget
 *
 */

class Coinbase_Button extends WP_Widget { 

	private $currencies = array( 'BTC' => 'BTC', 'USD' => 'USD', '--' => '--', 'AED' => 'AED', 'AFN' => 'AFN', 'ALL' => 'ALL', 'AMD' => 'AMD', 'ANG' => 'ANG', 'AOA' => 'AOA', 'ARS' => 'ARS', 'AUD' => 'AUD', 'AWG' => 'AWG', 'AZN' => 'AZN', 'BAM' => 'BAM', 'BBD' => 'BBD', 'BDT' => 'BDT', 'BGN' => 'BGN', 'BHD' => 'BHD', 'BIF' => 'BIF', 'BMD' => 'BMD', 'BND' => 'BND', 'BOB' => 'BOB', 'BRL' => 'BRL', 'BSD' => 'BSD', 'BTN' => 'BTN', 'BWP' => 'BWP', 'BYR' => 'BYR', 'BZD' => 'BZD', 'CAD' => 'CAD', 'CDF' => 'CDF', 'CHF' => 'CHF', 'CLP' => 'CLP', 'CNY' => 'CNY', 'COP' => 'COP', 'CRC' => 'CRC', 'CUP' => 'CUP', 'CVE' => 'CVE', 'CZK' => 'CZK', 'DJF' => 'DJF', 'DKK' => 'DKK', 'DOP' => 'DOP', 'DZD' => 'DZD', 'EEK' => 'EEK', 'EGP' => 'EGP', 'ETB' => 'ETB', 'EUR' => 'EUR', 'FJD' => 'FJD', 'FKP' => 'FKP', 'GBP' => 'GBP', 'GEL' => 'GEL', 'GHS' => 'GHS', 'GIP' => 'GIP', 'GMD' => 'GMD', 'GNF' => 'GNF', 'GTQ' => 'GTQ', 'GYD' => 'GYD', 'HKD' => 'HKD', 'HNL' => 'HNL', 'HRK' => 'HRK', 'HTG' => 'HTG', 'HUF' => 'HUF', 'IDR' => 'IDR', 'ILS' => 'ILS', 'INR' => 'INR', 'IQD' => 'IQD', 'IRR' => 'IRR', 'ISK' => 'ISK', 'JMD' => 'JMD', 'JOD' => 'JOD', 'JPY' => 'JPY', 'KES' => 'KES', 'KGS' => 'KGS', 'KHR' => 'KHR', 'KMF' => 'KMF', 'KPW' => 'KPW', 'KRW' => 'KRW', 'KWD' => 'KWD', 'KYD' => 'KYD', 'KZT' => 'KZT', 'LAK' => 'LAK', 'LBP' => 'LBP', 'LKR' => 'LKR', 'LRD' => 'LRD', 'LSL' => 'LSL', 'LTL' => 'LTL', 'LVL' => 'LVL', 'LYD' => 'LYD', 'MAD' => 'MAD', 'MDL' => 'MDL', 'MGA' => 'MGA', 'MKD' => 'MKD', 'MMK' => 'MMK', 'MNT' => 'MNT', 'MOP' => 'MOP', 'MRO' => 'MRO', 'MUR' => 'MUR', 'MVR' => 'MVR', 'MWK' => 'MWK', 'MXN' => 'MXN', 'MYR' => 'MYR', 'MZN' => 'MZN', 'NAD' => 'NAD', 'NGN' => 'NGN', 'NIO' => 'NIO', 'NOK' => 'NOK', 'NPR' => 'NPR', 'NZD' => 'NZD', 'OMR' => 'OMR', 'PAB' => 'PAB', 'PEN' => 'PEN', 'PGK' => 'PGK', 'PHP' => 'PHP', 'PKR' => 'PKR', 'PLN' => 'PLN', 'PYG' => 'PYG', 'QAR' => 'QAR', 'RON' => 'RON', 'RSD' => 'RSD', 'RUB' => 'RUB', 'RWF' => 'RWF', 'SAR' => 'SAR', 'SBD' => 'SBD', 'SCR' => 'SCR', 'SDG' => 'SDG', 'SEK' => 'SEK', 'SGD' => 'SGD', 'SHP' => 'SHP', 'SLL' => 'SLL', 'SOS' => 'SOS', 'SRD' => 'SRD', 'STD' => 'STD', 'SVC' => 'SVC', 'SYP' => 'SYP', 'SZL' => 'SZL', 'THB' => 'THB', 'TJS' => 'TJS', 'TMM' => 'TMM', 'TND' => 'TND', 'TOP' => 'TOP', 'TRY' => 'TRY', 'TTD' => 'TTD', 'TWD' => 'TWD', 'TZS' => 'TZS', 'UAH' => 'UAH', 'UGX' => 'UGX', 'UYU' => 'UYU', 'UZS' => 'UZS', 'VEF' => 'VEF', 'VND' => 'VND', 'VUV' => 'VUV', 'WST' => 'WST', 'XAF' => 'XAF', 'XCD' => 'XCD', 'XOF' => 'XOF', 'XPF' => 'XPF', 'YER' => 'YER', 'ZAR' => 'ZAR', 'ZMK' => 'ZMK', 'ZWL' => 'ZWL', );

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		//global $wid, $wname;
		parent::__construct(
	 		'coinbase_button', // Base ID
			'Coinbase Button', // Name
			array( 'description' => __( 'Displays a Coinbase button in your sidebar', 'text_domain' ), ) // Args
		);
		//add_action('admin_enqueue_scripts', array(&$this, 'admin_styles'), 1);
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

		$button_defaults = array(
              'name' => '',
              'price_string' => '',
              'price_currency_iso' => '',
              'custom' => '',
              'description' => '',
              'type' => 'buy_now',
              'style' => 'buy_now_large');

		$button_args = array();

		foreach ($instance as $k => $v) {
			if ($k != 'size' && $k != 'title')
				$button_args[$k] = $v;
		}

		$size = $instance['size'];
		$style = $instance['type'] . '_' . $size;
		$button_args['style'] = $style;

		$api_key = wpsf_get_setting( 'coinbase', 'general', 'api_key' );
        $coinbase = new Coinbase($api_key);
        $button = $coinbase->createButtonWithOptions($button_args)->embedHtml;

        echo $button;

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
		$instance['price_currency_iso'] = esc_attr(strip_tags( $new_instance['price_currency_iso'] ));
		$instance['type'] = esc_attr(strip_tags( $new_instance['type'] ));
		$instance['size'] = esc_attr(strip_tags( $new_instance['size'] ));

		$price = $new_instance['price_string'];
		if (!is_numeric(substr($price, 0, 1)))
			$price = substr($price, 1);

		$instance['price_string'] = (float) $price;
		$instance['price_string'] = (string) $price;

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
              'price_string' => '1.23',
              'price_currency_iso' => 'USD',
              'custom' => '',
              'description' => 'Sample description',
              'type' => 'buy_now',
              'size' => 'buy_now_large');
        extract(wp_parse_args($instance, $defaults));
        $price_string = (float) $price_string;

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
			<label for="<?php echo $this->get_field_id( 'currency' ); ?>"><?php _e( 'Amount:' ); ?></label>
			<span>
				<select class="widefat coinbase-currency" id="<?php echo $this->get_field_id( 'price_currency_iso' ); ?>" name="<?php echo $this->get_field_name('price_currency_iso'); ?>">
						<?php
						foreach ($this->currencies as $k => $v) {
							echo '<option value="' . $k . '"'
								. ( $k == $price_currency_iso ? ' selected="selected"' : '' )
								. '>' . $v . "</option>\n";
						}
						?>
				</select>	

				<input class="widefat coinbase-price" id="<?php echo $this->get_field_id( 'price_string' ); ?>" name="<?php echo $this->get_field_name( 'price_string' ); ?>" type="text" value="<?php echo esc_attr( $price_string ); ?>" />
			</span>
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _e( 'Button Type:' ); ?></label>
		<select class="widefat" id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name('type'); ?>">
				<?php
				$types = array(
					'buy_now' => 'Buy Now',
					'donation' => 'Donation');
				foreach ($types as $k => $v) {
					echo '<option value="' . $k . '"'
						. ( $k == $type ? ' selected="selected"' : '' )
						. '>' . $v . "</option>\n";
				}
				?>
		</select>	
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'size' ); ?>"><?php _e( 'Button Size:' ); ?></label>
		<select class="widefat" id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name('size'); ?>">
				<?php
				$sizes = array(
					'large' => 'Large',
					'small' => 'Small'
					);
				foreach ($sizes as $k => $v) {
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

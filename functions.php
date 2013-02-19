<?php
if (!function_exists('coinbase_buton')) {
	function coinbase_button( $args, $api_key ) {

        $defaults = array(
              'name' => 'test',
              'price_string' => '1.23',
              'price_currency_iso' => 'USD',
              'custom' => 'Order123',
              'description' => 'Sample description',
              'type' => 'buy_now',
              'style' => 'buy_now_large');

        $args = wp_parse_args($args, $defaults);

		$api_key = base64_decode($api_key);
		$buttonargs = array('button' => $args);
        $buttonargs['api_key'] = $api_key;

        $content = json_encode($buttonargs);
		$url = "https://coinbase.com/api/v1/buttons";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Content-type: application/json"));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
        $json_response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ( $status != 200 && $status != 201 ) {
            die("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
        }
        curl_close($curl);
        $response = json_decode($json_response, true);
        $buttonhtml = "<a class='coinbase-button' data-code='" . $response['button']['code'] . "' href='https://coinbase.com/checkouts/'" . $response['button']['code'] . "'>" .$response['button']['text']. "</a>
        <script src='https://coinbase.com/assets/button.js' type='text/javascript'></script>";

        return $buttonhtml;
	}
}
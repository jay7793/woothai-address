<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       woothaiapp.com
 * @since      1.0.0
 *
 * @package    Woothai_Address
 * @subpackage Woothai_Address/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Woothai_Address
 * @subpackage Woothai_Address/public
 * @author     woothaiapp <woothaiapp@gmail.com>
 */
class Woothai_Address_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woothai_Address_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woothai_Address_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if (!is_checkout() && !is_wc_endpoint_url( 'my-account' ))
			return;

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woothai-address-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woothai_Address_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woothai_Address_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if (!is_checkout() && !is_wc_endpoint_url( 'my-account' ))
			return;

		wp_enqueue_script( $this->plugin_name.'-match-height', plugin_dir_url( __FILE__ ) . 'js/jquery.matchHeight-min.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woothai-address-public.js', array( 'jquery' ), $this->version, false );

	}

	// Our hooked in function - $fields is passed via the filter!
	public function override_checkout_fields( $fields ) {
	 	unset($fields['billing_address_2']['billing_address_2']);

	 	$fields['billing']['billing_first_name']['priority'] = 5;
	 	$fields['billing']['billing_last_name']['priority'] = 10;

		$fields['billing']['billing_email']['placeholder'] = __( 'Your Email Address', 'woothai-address' );
		$fields['billing']['billing_email']['priority'] = 15;
		$fields['billing']['billing_email']['class'] = array('form-row-first form-row-equal-height');

		$fields['billing']['billing_phone']['placeholder'] = __( 'Your Phone Number', 'woothai-address' );
		$fields['billing']['billing_phone']['priority'] = 20;
		$fields['billing']['billing_phone']['class'] = array('form-row-last form-row-equal-height');

		$fields['billing']['billing_address_1']['priority'] = 40;

		$fields['billing']['billing_country']['priority'] = 99;
		$fields['shipping']['shipping_country']['priority'] = 99;

	 return $fields;
	}

	// Our hooked in function - $address_fields is passed via the filter!
	public function override_default_address_fields( $address_fields ) {
	  unset($address_fields['address_2']);

	  // New Field
	  $address_fields['sub_district'] = array(
			'label'     => __('Sub-district / Sub-area', 'woothai-address'),
			'placeholder' => __( 'e.g. Phra Khanong', 'woothai-address'),
			'required'  => true,
			'class'     => array('form-row-first form-row-equal-height'),
			'clear'     => false,
			'priority'  => 60,
	  );

	  $address_fields['phone'] = array(
			'label'     => __('Phone', 'woocommerce'),
			'required'  => false,
			'class'     => array('form-row-wide'),
			'clear'     => true,
			'priority'  => 25,
	  );

	  // Label & Placeholder
	  $address_fields['first_name']['placeholder'] = __( 'Your First Name', 'woothai-address' );
	  $address_fields['last_name']['placeholder'] = __( 'Your Last Name', 'woothai-address' );
	  $address_fields['address_1']['label'] = __( 'Address', 'woothai-address' );
	  $address_fields['address_1']['placeholder'] = __( 'House number, Road', 'woothai-address' );
	  $address_fields['city']['label'] = __( 'District / Area', 'woothai-address' );
	  $address_fields['city']['placeholder'] = __( 'e.g. Khlong Toei', 'woothai-address' );
	  $address_fields['state']['label'] = __( 'Province', 'woothai-address' );
	  $address_fields['state']['placeholder'] = __( 'Province', 'woothai-address' );
	  $address_fields['postcode']['placeholder'] = __( 'e.g. 10400', 'woothai-address' );

	  // Priority
	  $address_fields['country']['priority'] = 99;

	  // Class
	  $address_fields['city']['class'] = array('form-row-last form-row-equal-height');
	  $address_fields['state']['class'] = array('form-row-first form-row-equal-height');
	  $address_fields['postcode']['class'] = array('form-row-last form-row-equal-height');
	  $address_fields['country']['class'] = array('form-row-wide');

	  return $address_fields;
	}

	/**
	 * Formatted order billing address for Thailand address format
	 * @param  array $address
	 * @param  object $WC_Order
	 * @return array
	 */
	public function order_formatted_billing_address( $address, $WC_Order ) {
    	$address = array(
        'first_name'    => $WC_Order->billing_first_name,
        'last_name'     => $WC_Order->billing_last_name,
        'sub_district'  => $WC_Order->billing_sub_district,
        'company'       => $WC_Order->billing_company,
        'address_1'     => $WC_Order->billing_address_1,
        'address_2'     => $WC_Order->billing_address_2,
        'city'          => $WC_Order->billing_city,
        'state'         => $WC_Order->billing_state,
        'postcode'      => $WC_Order->billing_postcode,
        'country'       => $WC_Order->billing_country
      );
    	return $address;
	}

	/**
	 * Formatted order shipping address for Thailand address format
	 * @param  array $address
	 * @param  object $WC_Order
	 * @return array
	 */
	public function order_formatted_shipping_address( $address, $WC_Order ) {
    	$address = array(
        'first_name'    => $WC_Order->shipping_first_name,
        'last_name'     => $WC_Order->shipping_last_name,
        'sub_district'  => $WC_Order->shipping_sub_district,
        'company'       => $WC_Order->shipping_company,
        'address_1'     => $WC_Order->shipping_address_1,
        'address_2'     => $WC_Order->shipping_address_2,
        'city'          => $WC_Order->shipping_city,
        'state'         => $WC_Order->shipping_state,
        'postcode'      => $WC_Order->shipping_postcode,
        'country'       => $WC_Order->shipping_country,
        'phone'       	=> $WC_Order->shipping_phone
      );
    	return $address;
	}

	/**
	 * Get custom field prepare for formatted address
	 * @param  array $args
	 * @param  number $customer_id
	 * @param  string $name
	 * @return array
	 */
	public function my_account_formatted_address($args, $customer_id, $name){
		$args['sub_district'] = get_user_meta( $customer_id, $name . '_sub_district', true );
		$args['phone'] = get_user_meta( $customer_id, $name . '_phone', true );
    	return $args;
	}

	/**
	 * Add field to address
	 * @param  array $replacements
	 * @param  array $args
	 * @return array
	 */
	public function formatted_address_replacements($replacements, $args) {
		$replacements['{sub_district}'] = $args['sub_district'];
		$replacements['{phone}'] = $args['phone'];
    	return $replacements;
	}

	/**
	 * Format address display
	 * @param  array $formats
	 * @return array
	 */
	public function localisation_address_formats( $formats ) {
		$formats[ 'TH' ]  = "{name}\n{company}\n{address_1}\n{sub_district} {city}\n{state} {postcode}\n{country}\n{phone}";
		return $formats;
	}

}

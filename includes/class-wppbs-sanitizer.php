<?php
/**
 * The class for sanitation functionality of data from the Settings API.
 *
 * @since      1.0.0
 *
 * @package    WPPBS
 * @subpackage WPPBS/includes
 * @author     Ron Pugmire <webstractions@gmail.com>
 */
class WPPBS_Sanitizer {

	/**
	 * The data to be sanitized
	 *
	 * @access 	private
	 * @since 	1.0.0
	 * @var 	string
	 */
	private $data = '';

	/**
	 * The type of data
	 *
	 * @access 	private
	 * @since 	1.0.0
	 * @var 	string
	 */
	private $type = '';

	/**
	 * Constructor
	 */
	public function __construct() {

		// Nothing to see here...

	} // __construct()
	
	/**
	*  The main settings and methods for this class
	* 
	*/

	/**
	 * Sets the data class variable
	 *
	 * @param 	mixed 		$data			The data to sanitize
	 */
	public function set_data( $data ) {

		$this->data = $data;

	} // set_data()

	/**
	 * Sets the type class variable
	 *
	 * @param 	string 		$type			The field type for this data
	 */
	public function set_type( $type ) {

		$check = '';

		if ( empty( $type ) ) {

			$check = new WP_Error( 'forgot_type', __( 'Specify the data type to sanitize.', 'wppbs' ) );

		}

		if ( is_wp_error( $check ) ) {

			wp_die( $check->get_error_message(), __( 'Forgot data type', 'wppbs' ) );

		}

		$this->type = $type;

	} // set_type()

	/**
	 * Sanitize the data.
	 *
	 * @access 	public
	 * @since 	1.0.0
	 * @return  mixed         The sanitized data
	 */
	public function clean() {

		$sanitized = '';

		/**
		 * Add additional santization before the default sanitization
		 */
		do_action( 'wppbs_pre_sanitize', $sanitized );

		switch ( $this->type ) {

			case 'color'			:
			case 'radio'			:
			case 'select'			: $sanitized = $this->sanitize_random( $this->data ); break;

			case 'date'				:
			case 'datetime'			:
			case 'datetime-local'	:
			case 'time'				:
			case 'week'				: $sanitized = strtotime( $this->data ); break;

			case 'number'			:
			case 'range'			: $sanitized = intval( $this->data ); break;

			case 'hidden'			:
			case 'month'			:
			case 'text'				: $sanitized = sanitize_text_field( $this->data ); break;

			case 'checkbox'			: $sanitized = $this->validate_checkbox( $this->data ); break;
			
			case 'email'			: $sanitized = sanitize_email( $this->data ); break;
			
			case 'file'				: $sanitized = sanitize_file_name( $this->data ); break;

			case 'phone'			: $sanitized = $this->sanitize_phone( $this->data ); break;
			
			case 'textarea'			: $sanitized = esc_textarea( $this->data ); break;

			case 'url'				: $sanitized = esc_url( $this->data ); break;

		} // switch

		/**
		 * Add additional santization after the default .
		 */
		do_action( 'wppbs_post_sanitize', $sanitized );

		return $sanitized;

	} // clean()
	
	/**
	*  Additional validation and sanitation to handle checkbox arrays
	* 
	*/
	private function validate_checkbox( $data ) {
		
		if( is_array( $data ) ) {
			
			foreach( $data as $k=>$v ) {
				
				$valid[$k] = ( isset( $k[$v] ) ? 1 : 0 );

			}
		}
		else {
			$valid = ( isset( $data ) ? 1 : 0 );
		}
		
		return $valid;
	}
	
	/**
	*  Additional validation and sanitation beyond the WordPress set of functions.
	* 
	*  You can add more functionality here. This is just a representation of what
	*  can be coded into your plugin. 
	*/

	/**
		 * Checks a date against a format to ensure its validity
		 *
		 * @link 	http://www.php.net/manual/en/function.checkdate.php
		 *
		 * @param  	string 		$date   		The date as collected from the form field
		 * @param  	string 		$format 		The format to check the date against
		 * @return 	string 		A validated, formatted date
		 */
	private function validate_date( $date, $format = 'Y-m-d H:i:s' ) {

		$version = explode( '.', phpversion() );

		if ( ( (int) $version[0] >= 5 && (int) $version[1] >= 2 && (int) $version[2] > 17 ) ) {

			$d = DateTime::createFromFormat( $format, $date );

		} else {

			$d = new DateTime( date( $format, strtotime( $date ) ) );

		}

		return $d && $d->format( $format ) == $date;

	} // validate_date()

	/**
	 * Validates a phone number
	 *
	 * @access 	private
	 * @since	1.0.0
	 * @link	http://jrtashjian.com/2009/03/code-snippet-validate-a-phone-number/
	 * @param 	string 			$phone				A phone number string
	 * @return	string|bool		$phone|FALSE		Returns the valid phone number, FALSE if not
	 */
	private function sanitize_phone( $phone ) {

		if ( empty( $phone ) ) { return FALSE; }

		if ( preg_match( '/^[+]?([0-9]?)[(|s|-|.]?([0-9]{3})[)|s|-|.]*([0-9]{3})[s|-|.]*([0-9]{4})$/', $phone ) ) {

			return trim( $phone );

		} // $phone validation

		return FALSE;

	} // sanitize_phone()

	/**
	 * Performs general cleaning functions on data
	 *
	 * @param 	mixed 	$input 		Data to be cleaned
	 * @return 	mixed 	$return 	The cleaned data
	 */
	private function sanitize_random( $input ) {

			$one	= trim( $input );
			$two	= stripslashes( $one );
			$return	= htmlspecialchars( $two );

		return $return;

	} // sanitize_random()

} // WPPBS_Sanitizer
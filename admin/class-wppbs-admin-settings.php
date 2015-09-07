<?php

/**
 * The admin-specific Settings API functionality of the plugin.
 *
 * @link       http://webstractions.com
 * @since      1.0.0
 *
 * @package    WPPBS
 * @subpackage WPPBS/admin
 */

/**
 * The admin-specific Settings API functionality of the plugin.
 *
 * @package    WPPBS
 * @subpackage WPPBS/admin
 * @author     Ron Pugmire <webstractions@gmail.com>
 */
class WPPBS_Admin_Settings {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name   The ID of this plugin.
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
	 * The plugin options.
	 *
	 * @since 		1.0.0
	 * @access 		private
	 * @var 		string 			$options    The plugin options.
	 */
	private $options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name      The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
		$this->set_options();

	}

	/**
	 * Sets the class variable $options
	 */
	private function set_options() {

		$this->options = get_option( $this->plugin_name . '-options' );

	} // set_options()

	/**
	 * Gets the class variable $options
	 */
	public function get_options() {

		return $this->options;

	} // set_options()

	/**
	 * Adds a settings page link to a menu
	 *
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function add_menu() {

		add_submenu_page(
			'options-general.php',
			apply_filters( $this->plugin_name . '-settings-menu-title', esc_html__( 'WPPB Settings Example', 'wppbs' ) ),
			apply_filters( $this->plugin_name . '-settings-page-title', esc_html__( 'WPPB Settings Example', 'wppbs' ) ),
			'manage_options',
			$this->plugin_name . '-settings',
			array( $this, 'page_options' )
		);

	} // add_menu()

	/**
	 * Creates the options page
	 *
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function page_options() {

		include( plugin_dir_path( __FILE__ ) . 'partials/wppbs-admin-settings-page.php' );

	} // page_options()
	
	/**
	 * Initializes & registers plugin settings, sections, and fields.
	 *
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function initialize_settings() {
		
		// register_setting( $option_group, $option_name, $sanitize_callback );

		register_setting(
			$this->plugin_name . '-options',
			$this->plugin_name . '-options',
			array( $this, 'validate_options' )
		);

		// pull in options configuration
		$config = $this->get_options_config();
		
		foreach( $config as $section ) {
			
			$section_id = $this->plugin_name . '-section-' . esc_html( $section['id'] );
			$section_desc = $section['desc'];
			
			// add_settings_section( $id, $title, $callback, $page )
			
			add_settings_section(
				$section_id, 
				esc_html( $section['title'] ),
				function() use ($section_desc) { $this->render_section( $section_desc ); },
				$this->plugin_name
			);
			
			if( ! empty( $section['fields'] ) ) {
				
				foreach( $section['fields'] as $field_args ) {
					
					// add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );
					
					add_settings_field(
						esc_html( $field_args['id'] ),
						esc_html( $field_args['label'] ),
						array( $this, 'render_field' ),
						$this->plugin_name,
						$section_id,
						$field_args
					);
					
				}
			}			
		}
	} // initialize_settings()

	/**
	 * Returns an array of options names, fields types, and default values
	 * within their respective sections
	 *
	 * @return 		array 			A configuration array
	 */
	public static function get_options_config() {
		
		include( plugin_dir_path( __FILE__ ) . 'wppbs-admin-settings-config.php' );
		return $sections;

	} // get_options_config()
		
	/**
	 * Validates saved options.
	 * 
	 * This is the callback function for register_setting(). When WordPress saves
	 * 
	 *
	 * @since 		1.0.0
	 * @param 		array 		$input 			array of submitted plugin options
	 * @return 		array 						array of validated plugin options
	 */
	public function validate_options( $input ) {

		$valid 		= array();
		$options 	= $this->get_options_validation_list();

		foreach ( $options as $option ) {

			if ( ! isset( $input[$option[0]] ) ) { continue; }

			$sanitizer = new WPPBS_Sanitizer();

			$sanitizer->set_data( $input[$option[0]] );
			$sanitizer->set_type( $option[1] );

			$valid[$option[0]] = $sanitizer->clean();

			if ( $valid[$option[0]] != $input[$option[0]] ) {

				add_settings_error( $option[0], $option[0] . '_error', esc_html__( $option[0] . ' error. Input = ' . $input[$option[0]] . ' and was expecting ' . $valid[$option[0]], 'wppbs' ), 'error' );

			}

			unset( $sanitizer );

		}

		return $valid;

	} // validate_options()

	/**
	 * Returns an array of options names, fields types, and default values
	 *
	 * @return 		array 			An array of option validations
	 */
	public static function get_options_validation_list() {

		$options = array();
		
		$config = self::get_options_config();
		
		foreach( $config as $section ) {
			
			if( ! empty( $section['fields'] ) ) {
				
				foreach( $section['fields'] as $field_args ) {
					
					if( empty($field_args['default']) ) $field_args['default'] = '';
					
					$options[] = array( $field_args['id'], $field_args['type'], $field_args['default'] );
			
				}

			}			
		
		}

		return $options;

	} // get_options_validation_list()

	/**
	 * Render a section description
	 *
	 * @since 		1.0.0
	 * @param       string Description to render.
	 * @return 		void
	 */
	public function render_section( $section_desc ) {
		
		echo '<p>' . $section_desc . '</p>';
	}

	/**
	 * Render a settings field
	 *
	 * @since 		1.0.0
	 * @param       array $args
	 * @return 		void
	 */
	public function render_field( $args ) {
		
		if( is_array( $args ) ) {

			if ( ! empty( $this->options[ $args['id'] ] ) && ! is_array( $this->options[ $args['id'] ] )) {
				$this->field_value = esc_attr( $this->options[ $args['id'] ] );
			}
			elseif ( ! empty( $this->options[ $args['id'] ] ) ) {
				
				unset( $this->field_value );
				$this->field_value = array();
				
				foreach( $this->options[$args['id']] as $k=>$v ) {
					$this->field_value[$k] = esc_attr( $v );
				}
			}
			else {
				$this->field_value = $this->options[ $args['id'] ] = ( ! empty ($args[ 'default' ] ) ) ? $args['default'] : '';
			}
			
			$output = new WPPBS_Form( $this->plugin_name );
			$output->render_field( $args, $this->field_value );
			unset( $output );
			
			if( ! empty( $args['desc'] ) && ! $args['desc'] == '' ) {
				
				echo '<br /><span class="description">' . esc_html( $args['desc'] ) . '</span>';
			}
		}
		else {
			echo __( 'There is an error in the arguments for add_settings_field.', 'wppbs' );
		}
		
	}

		
}

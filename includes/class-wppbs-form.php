<?php
/**
 * The class for adding Html Form functionality.
 *
 * @since      1.0.0
 *
 * @package    WPPBS
 * @subpackage WPPBS/includes
 * @author     Ron Pugmire <webstractions@gmail.com>
 */
class WPPBS_Form {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name   The ID of this plugin.
	 */
	private $plugin_name;
	
	/**
	 * The field value.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $field_value   The field value.
	 */
	private $field_value;

	/**
	 * The field id.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $this->field_id   The field id.
	 */
	private $field_id;

	/**
	 * Constructor
	 */
	public function __construct( $plugin_name ) {

		$this->plugin_name = $plugin_name;

	} // __construct()
	
	public function render_field( $args, $value ) {
		
		$method = "_render_field_{$args['type']}";
		
		$this->field_id = $this->plugin_name . '-options[' . esc_html( $args['id'] ) . ']';
		
		$this->field_value = $value;
		
		if( method_exists( $this, $method ) )
			self::{$method}( $args );
		else
			self::_render_field_none( $args );
	}
	
	private function _render_field_text( $args ) {
		
		$size = ( ! empty( $args['size'] ) ) ? " size='{$args['size']}'" : "";
		
		echo sprintf( '<input id="%s" name="%s" value="%s" %s />', $this->field_id, $this->field_id, $this->field_value, $size );

	}

	private function _render_field_textarea( $args ) {
		
		$cols = ( ! empty( $args['cols'] ) ) ? " cols='{$args['cols']}'" : " cols='80'";
		$rows = ( ! empty( $args['rows'] ) ) ? " rows='{$args['rows']}'" : " rows='5'";
		
		echo sprintf( '<textarea id="%s" name="%s"  %s %s>%s</textarea>', $this->field_id, $this->field_id, $cols, $rows, $this->field_value );
	}

	private function _render_field_number( $args ) {
		
		echo sprintf( '<input type="number" class="small-text" value="%s" id="%s" min="1" step="1" name="%s">', $this->field_value, $this->field_id, $this->field_id );
	}

	private function _render_field_checkbox( $args ) {
		
		if ( ! empty ( $args[ 'options' ] ) && ( is_array ( $args[ 'options' ] ) || is_array ( $args[ 'default' ] ) ) ) {

        	$html = '';
        	
        	$html .= '<ul>';

                if ( ! isset ( $this->field_value ) ) {
                    $this->field_value = array();
                }

                if ( ! is_array ( $this->field_value ) ) {
                    $this->field_value = array();
                }

                if ( empty ( $args[ 'options' ] ) && isset ( $args[ 'default' ] ) && is_array ( $args[ 'default' ] ) ) {
                    $args[ 'options' ] = $args[ 'default' ];
                }
				
                foreach ( $args[ 'options' ] as $k => $v ) {

                    if ( empty ( $this->field_value[ $k ] ) ) {
                        $this->field_value[ $k ] = "";
                    }

                    $html .= '<li>';
					$html .= '<label>';
                    $html .= '<input type="checkbox" value="1" 
                    	name="' . $this->field_id . '[' . $k . ']' . '" ' . 
                    	checked ( $this->field_value[ $k ], '1', false ) .
                    	' id="' . $this->field_id . '[' . $k . ']' . '" />';
                    $html .= ' ' . $v . '</label>';
                    $html .= '</li>';
                }

                $html .= '</ul>';
                
                echo $html;
                
            }
            else {
				
				echo __( 'Something wrong with checkbox configuration', 'wppbs' );
            }
            
	}

	private function _render_field_radio( $args ) {
		
		$html = '';
		
		if( ! empty( $args['options'] ) ) {
			
			$html .= '<ul>';

            foreach ( $args['options'] as $k => $v ) {
                $html .= '<li>';
                $html .= '<label for="' . $this->field_id . '_' . array_search( $k, array_keys( $args['options'] ) ) . '">';
                $html .= '<input type="radio" id="' . $this->field_id . '_' . array_search( $k, array_keys( $args['options'] ) ) . '" name="' . $this->field_id . '" value="' . $k . '" ' . checked( $this->field_value, $k, false ) . '/>';
                $html .= ' <span>' . $v . '</span>';
                $html .= '</label>';
                $html .= '</li>';
            }
			
			$html .= '</ul>';
		}
		
		echo $html;
		
	}

	private function _render_field_info( $args ) {
		
		echo sprintf( '<span>%s</span>', $args['data'] );
	}

	private function _render_field_none( $args ) { 
		
		echo "<span style='background-color:yellow;padding:.25rem;'>The rendering function for field type <em>{$args['type']}</em> has not been written yet.</span>"; 
	}

	private function _render_field_select( $args ) {
		
		$html ='';
				
		$rows = ( ! empty( $args['rows'] ) ) ? " rows='{$args['rows']}'" : " rows='6'";
		
		$html .= sprintf ( '<select id="%s" name="%s" %s>', $this->field_id, $this->field_id, $rows );

		foreach ( $args['options'] as $k => $v ) {

			if (is_array($v)) {
			    $html .= '<optgroup label="' . $k . '">';

			    foreach($v as $opt => $val) {
			        $html .= $this->_render_field_select_option($opt, $val, $k);
			    }

			    $html .= '</optgroup>';

			    continue;
			}

			$html .= $this->_render_field_select_option($k, $v );

		}

		$html .= '</select>';

		echo $html;
	}

	/**
	* Render an option for select field
	* 
	* @param mixed $id          Field ID
	* @param mixed $value       Field value
	* @param mixed $group_name  Field group name
	*/
	private function _render_field_select_option($id, $value, $group_name = '') {
		
	    if ( is_array( $this->field_value ) ) {
	        $selected = ( is_array( $this->field_value ) && in_array( $id, $this->field_value ) ) ? ' selected="selected"' : '';
	    } else {
	        $selected = selected( $this->field_value, $id, false );
	    }

	    return '<option value="' . $id . '"' . $selected . '>' . $value . '</option>';                
	}	

	
}

  


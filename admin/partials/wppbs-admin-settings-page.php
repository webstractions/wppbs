<?php

/**
 * Provides an admin area view for the plugin settings
 *
 * @since      1.0.0
 *
 * @package    WPPBS
 * @subpackage WPPBS/admin/partials
 */

?>
<div class="wrap">

	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<form class="form-settings" method="post" action="options.php">

	<?php

		settings_fields( $this->plugin_name . '-options' );

		do_settings_sections( $this->plugin_name );

		submit_button( 'Save Settings' );

	?>
	</form>
	
</div>
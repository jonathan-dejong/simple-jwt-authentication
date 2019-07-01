<form action='options.php' method='post'>
	<h1>Simple JWT Authentication</h1>
	<?php
	settings_fields( 'simple_jwt_authentication' );
	do_settings_sections( 'simple_jwt_authentication' );
	submit_button();
	?>
	<h2><?php esc_html_e( 'Getting started', 'simple-jwt-authentication' ); ?></h2>
	<p>
		<?php // Translators: %s is a link to wiki. ?>
		<?php echo sprintf( __( 'To get started check out the <a href="%s" target="_blank" rel="nofollow">documentation</a>', 'simple-jwt-authentication' ), 'https://github.com/jonathan-dejong/simple-jwt-authentication/wiki/Documentation' ); // phpcs:ignore ?>
	</p>
</form>

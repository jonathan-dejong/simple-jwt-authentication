<input type="text" name='simple_jwt_authentication_settings[secret_key]' value='<?php echo $secret_key; ?>' <?php echo ( $is_global ? 'readonly' : '' ); ?> size="50" autocomplete="off" />
<?php
if ( $is_global ) {
	echo '<br /><small>' . __( 'Defined in wp-config.php', 'simple-jwt-authentication' ) . '</small>';
} else {
	echo '<br /><small>' . __( 'Should be a long string of letters, numbers and symbols.', 'simple-jwt-authentication' ) . '</small>';
}
?>

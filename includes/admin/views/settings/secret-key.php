<?php
$description = esc_html__( 'Should be a long string of letters, numbers and symbols.', 'simple-jwt-authentication' );
$readonly    = '';
$input_value = $secret_key;
$input_type  = 'text';
// Override with hidden value if it's been defined as a constant.
if ( $is_global ) {
	$description = esc_html__( 'Defined in wp-config.php', 'simple-jwt-authentication' );
	$readonly    = 'readonly';
	$input_type  = 'password';
	$input_value = str_repeat( '*', strlen( $input_value ) );
}
?>

<input type="<?php echo $input_type; ?>" name='simple_jwt_authentication_settings[secret_key]' value='<?php echo $input_value; // phpcs:ignore ?>' <?php echo $readonly; ?> size="50" autocomplete="off" />
<br /><small><?php echo esc_html( $description ); ?></small>

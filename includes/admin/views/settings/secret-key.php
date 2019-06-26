<?php

// Only display the Secret key, if it is not set in the wp-config file.
// In Multisite Installations it could be a security issue to display a global secret to other sites admins
if ( $is_global ) {
    ?>
    <input type="text" value="<?php _e('-- HIDDEN --', 'simple-jwt-authentication'); ?>" readonly="readonly" size="50" autocomplete="off" />
    <?php
	echo '<br /><small>' . __( 'Defined in wp-config.php', 'simple-jwt-authentication' ) . '</small>';
} else {
    ?>
    <input type="text" name='simple_jwt_authentication_settings[secret_key]' value='<?php echo $secret_key; ?>' size="50" autocomplete="off" />
    <?php
	echo '<br /><small>' . __( 'Should be a long string of letters, numbers and symbols.', 'simple-jwt-authentication' ) . '</small>';
}
?>

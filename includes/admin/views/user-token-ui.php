<h2><?php _e( 'Simple JWT Authentication API Tokens', 'simple-jwt-authentication' ); ?></h2>
<table class="table widefat striped">
	<thead>
		<tr>
			<th><?php _e( 'Token UUID', 'simple-jwt-authentication' ); ?></th>
			<th><?php _e( 'Expires', 'simple-jwt-authentication' ); ?></th>
			<th><?php _e( 'Last used', 'simple-jwt-authentication' ); ?></th>
			<th><?php _e( 'By IP', 'simple-jwt-authentication' ); ?></th>
			<th><?php _e( 'Browser', 'simple-jwt-authentication' ); ?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php if ( ! empty( $tokens ) ) : ?>
			<?php
			$current_url = ( isset( $_SERVER['HTTPS'] ) ? 'https' : 'http' ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$current_url = remove_query_arg( array( 'revoked', 'removed', 'jwtupdated' ), $current_url );
			?>
			<?php foreach ( $tokens as $token ) : ?>
				<?php
				$ua_info = parse_user_agent( $token['ua'] );
				$revoke_url = add_query_arg( array(
					'revoke_token' => $token['uuid'],
				), $current_url );
				?>
				<tr>
					<td><?php echo $token['uuid']; ?></td>
					<td><?php echo date_i18n( 'Y-m-d H:i:s', $token['expires'] ); ?></td>
					<td><?php echo date_i18n( 'Y-m-d H:i:s', $token['last_used'] ); ?></td>
					<td><?php echo $token['ip']; ?> <a href="<?php echo sprintf( 'https://ipinfo.io/%s', $token['ip'] ); ?>" target="_blank" title="Look up IP location" class="button-link"><?php _e( 'Lookup', 'simple-jwt-authentication' ); ?></a></td>
					<td><?php echo sprintf( __( '<strong>Platform</strong> %1$s. <strong>Browser:</strong> %2$s. <strong>Browser version:</strong> %3$s', 'simple-jwt-authentication' ), $ua_info['platform'], $ua_info['browser'], $ua_info['version'] ); ?></td>
					<td>
              <a href="<?php echo $revoke_url; ?>" title="<?php _e( 'Revokes this token from being used any further.', 'simple-jwt-authentication' ); ?>" class="button-secondary"><?php _e( 'Revoke', 'simple-jwt-authentication' ); ?></a>
					</td>
				</tr>
			<?php endforeach; ?>
			<tr>
				<td colspan="6" align="right">
					<a href="<?php echo add_query_arg( 'revoke_all_tokens', '1', $current_url ); ?>" class="button-secondary" title="<?php _e( 'Doing this will require the user to login again on all devices.', 'simple-jwt-authentication' ); ?>"><?php _e( 'Revoke all tokens', 'simple-jwt-authentication' ); ?></a>
					<a href="<?php echo add_query_arg( 'remove_expired_tokens', '1', $current_url ); ?>" class="button-secondary" title="<?php _e( 'Doing this will not affect logged in devices for this user.', 'simple-jwt-authentication' ); ?>"><?php _e( 'Remove all expired tokens', 'simple-jwt-authentication' ); ?></a>
				</td>
			</tr>
		<?php else : ?>
			<tr>
				<td colspan="6"><?php _e( 'No tokens generated.', 'simple-jwt-authentication' ); ?></td>
			</tr>
		<?php endif; ?>
	</tbody>
</table>

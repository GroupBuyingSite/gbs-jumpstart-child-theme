<?php

/**
 * This function file is loaded after the parent theme's function file. It's a great way to override functions, e.g. add_image_size sizes.
 *
 *
 */

add_action( 'gb_deal_view', 'gb_set_signup_cookie' );
add_action( 'gb_deals_view', 'gb_set_signup_cookie' );
function gb_set_signup_cookie() {
	// for those special redirects with a query var
	if ( !headers_sent() && isset( $_GET['signup-success'] ) && $_GET['signup-success'] ) {
		$cookie_time = ( time() + 24 * 60 * 60 * 30 );
		setcookie( 'gb_subscription_process_complete', '1', $cookie_time, '/' );
		return TRUE;
	}
	return FALSE;
}

add_filter( 'gb_lightbox_custom_css', 'lb_show_lightbox' );
function lb_show_lightbox( $bool ) {
	gb_set_signup_cookie();
	if ( isset( $_GET['signup-success'] ) ) {
		return FALSE;
	}
	return $bool;
}

add_filter( 'gb_lightbox_custom_css', 'modify_gb_lightbox_custom_css' );
function modify_gb_lightbox_custom_css( $css ) {
	$css .= '#gb_light_box { position: absolute; } #gb_lightbox_subscription_form { margin: 0; } #login_link_lightbox a { border: none; }';
	return $css;
}

add_filter( 'gb_lightbox_subscription_form', 'lightbox_subscription_form', 10, 4 );
function lightbox_subscription_form( $view, $show_locations, $select_location_text, $button_text ) {
	ob_start();
?>
		<div id="gb_light_box" class="modal hide fade">
			<div id="gb_lb_header" class="top"><header><?php gb_e( 'Signup and Save!' ) ?></header></div>
				<div id="gb_lb_content_wrap">
					<form action="" id="gb_lightbox_subscription_form" method="post" class="clearfix">
						<div id="gb_lb_content">
							<span class="option email_input_wrap clearfix">
								<label for="email_address" class="email_address"><?php gb_e( 'Join today to start getting awesome daily deals!' ); ?></label>
								<input type="text" name="email_address" id="email_address" value="<?php gb_e( 'Enter your email' ); ?>" onblur="if (this.value == '')  {this.value = '<?php gb_e( 'Enter your email' ); ?>';}" onfocus="if (this.value == '<?php gb_e( 'Enter your email' ); ?>') {this.value = '';}" tabindex="11">
							</span>
							<?php
							$locations = gb_get_locations( false );
							$no_city_text = get_option( Group_Buying_List_Services::SIGNUP_CITYNAME_OPTION );
							if ( ( !empty( $locations ) || !empty( $no_city_text ) ) && $show_locations ) { ?>
								<span class="option location_options_wrap clearfix">
								<label for="locations"><?php gb_e( $select_location_text ); ?></label>
								<?php
									$current_location = null;
									if ( isset( $_COOKIE[ 'gb_location_preference' ] ) && $_COOKIE[ 'gb_location_preference' ] != '' ) {
										$current_location = $_COOKIE[ 'gb_location_preference' ];
									} elseif ( is_tax() ) {
										global $wp_query;
										$query_slug = $wp_query->get_queried_object()->slug;
										if ( isset( $query_slug ) && !empty( $query_slug ) ) {
											$current_location = $query_slug;
										}
									}
									echo '<select name="deal_location" id="deal_location" size="1" tabindex="12">';
									foreach ( $locations as $location ) {
										echo '<option value="'.$location->slug.'" '.selected( $current_location, $location->slug ).'>'.$location->name.'</option>';
									}
									if ( !empty( $no_city_text ) ) {
										echo '<option value="notfound">'.esc_attr( $no_city_text ).'</option>';
									}
									echo '</select>'; ?>
									<br/>
									<span id="login_link_lightbox"><?php printf( gb__( 'Already Registered? <a href="%s" title="Login">Login.</a>' ), wp_login_url() ) ?></span>
								</span> <?php
							} ?>
					</div>
					<div id="gb_lb_footer" class="bottom">
						<?php wp_nonce_field( 'gb_subscription' );?>
						<input type="submit" class="flat gb_lb_floatright" name="gb_subscription" id="gb_subscription" value="<?php gb_e( $button_text ); ?>" tabindex="13">
					</div>
				</form>
			</div>
		</div>
	<?php
	$view = ob_get_clean();
	return $view;
}

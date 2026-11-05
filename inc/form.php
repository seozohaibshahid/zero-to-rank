<?php
/**
 * Free visibility audit form.
 *
 * - Progressive enhancement: JS submits to the REST route; without JS the
 *   form posts to admin-post.php and redirects back with a status.
 * - Spam protection: honeypot, signed render timestamp (minimum fill time),
 *   per-IP rate limiting, link-count heuristic, plus the `ztr_audit_spam_check`
 *   filter for adding Turnstile / reCAPTCHA. Signed timestamps are used instead
 *   of nonces because page caches (e.g. LiteSpeed on Hostinger) serve stale nonces.
 * - Every valid request is stored as a private "Audit request" so a lead is
 *   never lost if email delivery fails, then emailed to the configured recipient.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

/** Minimum seconds between page render and submission. */
const ZTR_FORM_MIN_SECONDS = 3;

/** Maximum submissions per IP per hour. */
const ZTR_FORM_RATE_LIMIT = 5;

/**
 * The Fluent Form to show in the audit section: the widget's own ID, else the
 * one set in the Customizer (the setup routine fills it in). 0 means "use the
 * built-in form", which is also the fallback when Fluent Forms is inactive.
 *
 * @param int $widget_id Form ID from the widget or section args.
 * @return int
 */
function ztr_audit_form_id( $widget_id = 0 ) {
	$id = (int) $widget_id ? (int) $widget_id : (int) get_theme_mod( 'ztr_form_audit', 0 );
	return ( $id && shortcode_exists( 'fluentform' ) ) ? $id : 0;
}

/**
 * Focus options for the select field.
 *
 * @return array
 */
function ztr_audit_focus_options() {
	return array(
		'all'    => __( 'All three — full visibility audit', 'zero-to-rank' ),
		'seo'    => __( 'SEO — search engine rankings', 'zero-to-rank' ),
		'aeo'    => __( 'AEO — answer engines & snippets', 'zero-to-rank' ),
		'geo'    => __( 'GEO — AI assistants & AI search', 'zero-to-rank' ),
		'unsure' => __( 'Not sure yet — advise me', 'zero-to-rank' ),
	);
}

/* -------------------------------------------------------------------------
 * Storage: private post type for audit requests.
 * ---------------------------------------------------------------------- */

add_action(
	'init',
	function () {
		register_post_type(
			'ztr_lead',
			array(
				'labels'          => array(
					'name'          => __( 'Audit requests', 'zero-to-rank' ),
					'singular_name' => __( 'Audit request', 'zero-to-rank' ),
					'menu_name'     => __( 'Audit requests', 'zero-to-rank' ),
					'edit_item'     => __( 'Audit request', 'zero-to-rank' ),
				),
				'public'          => false,
				'show_ui'         => true,
				'show_in_menu'    => true,
				'show_in_rest'    => false,
				'menu_icon'       => 'dashicons-chart-line',
				'menu_position'   => 26,
				'supports'        => array( 'title', 'editor' ),
				'capability_type' => 'page',
				'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
				'map_meta_cap'    => true,
				'rewrite'         => false,
				'query_var'       => false,
			)
		);
	}
);

add_filter(
	'manage_ztr_lead_posts_columns',
	function ( $cols ) {
		return array(
			'cb'          => isset( $cols['cb'] ) ? $cols['cb'] : '',
			'title'       => __( 'Name', 'zero-to-rank' ),
			'ztr_email'   => __( 'Email', 'zero-to-rank' ),
			'ztr_website' => __( 'Website', 'zero-to-rank' ),
			'ztr_focus'   => __( 'Focus', 'zero-to-rank' ),
			'date'        => __( 'Received', 'zero-to-rank' ),
		);
	}
);

add_action(
	'manage_ztr_lead_posts_custom_column',
	function ( $col, $post_id ) {
		$value = get_post_meta( $post_id, '_' . $col, true );
		if ( 'ztr_email' === $col ) {
			printf( '<a href="mailto:%1$s">%1$s</a>', esc_attr( $value ) );
		} elseif ( 'ztr_website' === $col ) {
			printf( '<a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a>', esc_url( $value ), esc_html( $value ) );
		} else {
			echo esc_html( $value );
		}
	},
	10,
	2
);

/* -------------------------------------------------------------------------
 * Tokens & helpers.
 * ---------------------------------------------------------------------- */

/**
 * Signed render timestamp for the time-trap field.
 *
 * @return string
 */
function ztr_form_token() {
	$t = time();
	return $t . '.' . hash_hmac( 'sha256', (string) $t, wp_salt( 'nonce' ) );
}

/**
 * Validate the signed timestamp and minimum fill time.
 *
 * @param string $token Token.
 * @return bool
 */
function ztr_form_token_valid( $token ) {
	$parts = explode( '.', (string) $token );
	if ( 2 !== count( $parts ) || ! ctype_digit( $parts[0] ) ) {
		return false;
	}
	$expected = hash_hmac( 'sha256', $parts[0], wp_salt( 'nonce' ) );
	if ( ! hash_equals( $expected, $parts[1] ) ) {
		return false;
	}
	return ( time() - (int) $parts[0] ) >= ZTR_FORM_MIN_SECONDS;
}

/**
 * Anonymised client identifier for rate limiting (raw IPs are never stored).
 *
 * @return string
 */
function ztr_client_hash() {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';
	return substr( hash_hmac( 'sha256', $ip, wp_salt( 'auth' ) ), 0, 24 );
}

/**
 * Normalise a website address; returns '' when invalid.
 *
 * @param string $raw Input.
 * @return string
 */
function ztr_normalise_url( $raw ) {
	$raw = trim( (string) $raw );
	if ( '' === $raw ) {
		return '';
	}
	if ( ! preg_match( '#^https?://#i', $raw ) ) {
		$raw = 'https://' . $raw;
	}
	$url  = esc_url_raw( $raw, array( 'http', 'https' ) );
	$host = wp_parse_url( $url, PHP_URL_HOST );
	if ( ! $url || ! $host || false === strpos( $host, '.' ) || ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
		return '';
	}
	return $url;
}

/* -------------------------------------------------------------------------
 * Processing.
 * ---------------------------------------------------------------------- */

/**
 * Validate, spam-check, store and email an audit request.
 *
 * @param array $raw Unslashed request data.
 * @return true|WP_Error  WP_Error data carries 'status' and optional 'fields'.
 */
function ztr_process_audit( array $raw ) {
	$get = static function ( $key ) use ( $raw ) {
		return isset( $raw[ $key ] ) && is_scalar( $raw[ $key ] ) ? (string) $raw[ $key ] : '';
	};

	// Honeypot: real users never see or fill this. Pretend success so bots learn nothing.
	if ( '' !== trim( $get( 'ztr_fax' ) ) ) {
		return new WP_Error( 'ztr_spam_silent', 'ok', array( 'status' => 200 ) );
	}

	if ( ! ztr_form_token_valid( $get( 'ztr_ts' ) ) ) {
		return new WP_Error( 'ztr_token', __( 'Your session looked automated. Please wait a moment and submit again.', 'zero-to-rank' ), array( 'status' => 400 ) );
	}

	$data = array(
		'name'    => sanitize_text_field( $get( 'ztr_name' ) ),
		'email'   => sanitize_email( $get( 'ztr_email' ) ),
		'website' => ztr_normalise_url( $get( 'ztr_website' ) ),
		'company' => sanitize_text_field( $get( 'ztr_company' ) ),
		'focus'   => sanitize_key( $get( 'ztr_focus' ) ),
		'message' => sanitize_textarea_field( $get( 'ztr_message' ) ),
		'consent' => '' !== $get( 'ztr_consent' ),
		'source'  => esc_url_raw( $get( 'ztr_source' ) ),
	);

	$errors = array();
	if ( '' === $data['name'] || mb_strlen( $data['name'] ) > 100 ) {
		$errors['ztr_name'] = __( 'Please enter your name.', 'zero-to-rank' );
	}
	if ( ! is_email( $data['email'] ) ) {
		$errors['ztr_email'] = __( 'Please enter a valid email address.', 'zero-to-rank' );
	}
	if ( '' === $data['website'] ) {
		$errors['ztr_website'] = __( 'Please enter a valid website address, e.g. example.com', 'zero-to-rank' );
	}
	if ( ! array_key_exists( $data['focus'], ztr_audit_focus_options() ) ) {
		$data['focus'] = 'all';
	}
	if ( mb_strlen( $data['message'] ) > 2000 ) {
		$errors['ztr_message'] = __( 'Please keep your message under 2,000 characters.', 'zero-to-rank' );
	}
	if ( ! $data['consent'] ) {
		$errors['ztr_consent'] = __( 'Please confirm we can contact you about your audit.', 'zero-to-rank' );
	}
	if ( $errors ) {
		return new WP_Error( 'ztr_invalid', __( 'Please check the highlighted fields.', 'zero-to-rank' ), array( 'status' => 422, 'fields' => $errors ) );
	}

	// Link-stuffed messages are almost always spam.
	if ( preg_match_all( '#https?://#i', $data['message'] ) > 2 ) {
		return new WP_Error( 'ztr_spam_silent', 'ok', array( 'status' => 200 ) );
	}

	// Rate limit per anonymised client.
	$rate_key = 'ztr_rl_' . ztr_client_hash();
	$count    = (int) get_transient( $rate_key );
	if ( $count >= ZTR_FORM_RATE_LIMIT ) {
		return new WP_Error( 'ztr_rate', __( 'Too many requests. Please try again later or email us directly.', 'zero-to-rank' ), array( 'status' => 429 ) );
	}

	/**
	 * Hook for CAPTCHA / third-party spam services. Return a WP_Error to reject.
	 *
	 * @param true|WP_Error $result
	 * @param array         $data Sanitised data.
	 * @param array         $raw  Raw request.
	 */
	$check = apply_filters( 'ztr_audit_spam_check', true, $data, $raw );
	if ( is_wp_error( $check ) ) {
		return $check;
	}

	set_transient( $rate_key, $count + 1, HOUR_IN_SECONDS );

	$focus_label = ztr_audit_focus_options()[ $data['focus'] ];
	$lines       = array(
		__( 'Name', 'zero-to-rank' ) . ': ' . $data['name'],
		__( 'Email', 'zero-to-rank' ) . ': ' . $data['email'],
		__( 'Website', 'zero-to-rank' ) . ': ' . $data['website'],
		__( 'Company', 'zero-to-rank' ) . ': ' . ( $data['company'] ? $data['company'] : '—' ),
		__( 'Focus', 'zero-to-rank' ) . ': ' . $focus_label,
		__( 'Page', 'zero-to-rank' ) . ': ' . ( $data['source'] ? $data['source'] : '—' ),
		'',
		__( 'Message', 'zero-to-rank' ) . ':',
		$data['message'] ? $data['message'] : '—',
	);
	$body = implode( "\n", $lines );

	$lead_id = wp_insert_post(
		array(
			'post_type'    => 'ztr_lead',
			'post_status'  => 'private',
			'post_title'   => $data['name'] . ( $data['company'] ? ' — ' . $data['company'] : '' ),
			'post_content' => $body,
		),
		true
	);
	if ( ! is_wp_error( $lead_id ) ) {
		update_post_meta( $lead_id, '_ztr_email', $data['email'] );
		update_post_meta( $lead_id, '_ztr_website', $data['website'] );
		update_post_meta( $lead_id, '_ztr_focus', strtoupper( $data['focus'] ) );
	}

	$to = ztr_setting( 'audit_recipient' );
	$to = is_email( $to ) ? $to : get_option( 'admin_email' );
	/* translators: %s: website address. */
	$subject = sprintf( __( 'New visibility audit request: %s', 'zero-to-rank' ), wp_parse_url( $data['website'], PHP_URL_HOST ) );
	$headers = array( 'Reply-To: ' . $data['name'] . ' <' . $data['email'] . '>' );
	$sent    = wp_mail( $to, $subject, $body, $headers );

	if ( ! is_wp_error( $lead_id ) ) {
		update_post_meta( $lead_id, '_ztr_mail_sent', $sent ? 'yes' : 'no' );
	}

	/**
	 * Fires after a valid audit request is stored and emailed (CRM hooks, etc.).
	 *
	 * @param array    $data    Sanitised data.
	 * @param int|WP_Error $lead_id Stored lead ID.
	 */
	do_action( 'ztr_audit_submitted', $data, $lead_id );

	return true;
}

/* -------------------------------------------------------------------------
 * REST endpoint (JS submission).
 * ---------------------------------------------------------------------- */

add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'ztr/v1',
			'/audit',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'permission_callback' => '__return_true', // Public form; protected by the checks in ztr_process_audit().
				'callback'            => function ( WP_REST_Request $request ) {
					$result = ztr_process_audit( (array) $request->get_body_params() );

					if ( true === $result || ( is_wp_error( $result ) && 'ztr_spam_silent' === $result->get_error_code() ) ) {
						return new WP_REST_Response(
							array(
								'success' => true,
								'message' => ztr_content( 'audit' )['success'],
							),
							200
						);
					}

					$data = $result->get_error_data();
					return new WP_REST_Response(
						array(
							'success' => false,
							'message' => $result->get_error_message(),
							'fields'  => isset( $data['fields'] ) ? $data['fields'] : (object) array(),
						),
						isset( $data['status'] ) ? (int) $data['status'] : 400
					);
				},
			)
		);
	}
);

/* -------------------------------------------------------------------------
 * admin-post.php fallback (no-JS submission).
 * ---------------------------------------------------------------------- */

/**
 * Handle the classic POST and redirect back to the form.
 */
function ztr_handle_audit_post() {
	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Protected by signed timestamp + honeypot (nonces break under page caching).
	$raw    = wp_unslash( $_POST );
	$result = ztr_process_audit( is_array( $raw ) ? $raw : array() );
	$ok     = true === $result || ( is_wp_error( $result ) && 'ztr_spam_silent' === $result->get_error_code() );

	$back = isset( $raw['ztr_source'] ) ? esc_url_raw( (string) $raw['ztr_source'] ) : '';
	$back = wp_validate_redirect( $back, home_url( '/' ) );
	$back = add_query_arg( 'ztr_audit', $ok ? 'success' : 'error', remove_query_arg( 'ztr_audit', strtok( $back, '#' ) ) );

	wp_safe_redirect( $back . '#audit', 303 );
	exit;
}
add_action( 'admin_post_nopriv_ztr_audit', 'ztr_handle_audit_post' );
add_action( 'admin_post_ztr_audit', 'ztr_handle_audit_post' );

<?php
/**
 * Customizer settings: contact details, audit form routing and profiles.
 * Appearance → Customize → Zero to Rank.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

/**
 * Setting definitions: key => [label, type, sanitize callback].
 *
 * @return array
 */
function ztr_customizer_fields() {
	return array(
		'ztr_email'           => array( __( 'Public contact email', 'zero-to-rank' ), 'email', 'sanitize_email' ),
		'ztr_phone'           => array( __( 'Public phone number', 'zero-to-rank' ), 'text', 'sanitize_text_field' ),
		'ztr_location'        => array( __( 'Location / address line', 'zero-to-rank' ), 'text', 'sanitize_text_field' ),
		'ztr_area_served'     => array( __( 'Area served (schema, e.g. "United Kingdom")', 'zero-to-rank' ), 'text', 'sanitize_text_field' ),
		'ztr_audit_recipient' => array( __( 'Audit requests are emailed to (defaults to the site admin email)', 'zero-to-rank' ), 'email', 'sanitize_email' ),
		'ztr_linkedin'        => array( __( 'LinkedIn URL', 'zero-to-rank' ), 'url', 'esc_url_raw' ),
		'ztr_x'               => array( __( 'X (Twitter) URL', 'zero-to-rank' ), 'url', 'esc_url_raw' ),
		'ztr_instagram'       => array( __( 'Instagram URL', 'zero-to-rank' ), 'url', 'esc_url_raw' ),
		'ztr_facebook'        => array( __( 'Facebook URL', 'zero-to-rank' ), 'url', 'esc_url_raw' ),
	);
}

add_action(
	'customize_register',
	function ( $wp_customize ) {
		$wp_customize->add_section(
			'ztr_settings',
			array(
				'title'       => __( 'Zero to Rank', 'zero-to-rank' ),
				'description' => __( 'Contact details and profiles used by the landing page footer, audit form and structured data.', 'zero-to-rank' ),
				'priority'    => 30,
			)
		);

		foreach ( ztr_customizer_fields() as $id => $field ) {
			$wp_customize->add_setting(
				$id,
				array(
					'default'           => '',
					'sanitize_callback' => $field[2],
					'transport'         => 'refresh',
				)
			);
			$wp_customize->add_control(
				$id,
				array(
					'label'   => $field[0],
					'section' => 'ztr_settings',
					'type'    => $field[1],
				)
			);
		}
	}
);

/**
 * Read a Zero to Rank setting.
 *
 * @param string $key Setting key without the ztr_ prefix.
 * @return string
 */
function ztr_setting( $key ) {
	return (string) get_theme_mod( 'ztr_' . $key, '' );
}

/**
 * Social profile URLs that have been filled in.
 *
 * @return array label => url
 */
function ztr_social_profiles() {
	$profiles = array(
		'LinkedIn'  => ztr_setting( 'linkedin' ),
		'X'         => ztr_setting( 'x' ),
		'Instagram' => ztr_setting( 'instagram' ),
		'Facebook'  => ztr_setting( 'facebook' ),
	);
	return array_filter( $profiles );
}

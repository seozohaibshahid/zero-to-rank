<?php
/**
 * JSON-LD structured data for the landing template:
 * Organization, WebSite, WebPage, three Service entities and FAQPage.
 * Everything mirrors visible page content.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

/**
 * Build the @graph.
 *
 * @return array
 */
function ztr_schema_graph() {
	$home    = home_url( '/' );
	$page    = get_permalink( get_queried_object_id() );
	$org_id  = $home . '#ztr-organization';
	$name    = get_bloginfo( 'name' ) ? get_bloginfo( 'name' ) : 'Zero to Rank';
	$content = ztr_content();
	$graph   = array();

	$logo = has_custom_logo() ? wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'full' ) : ztr_asset( 'images/logo-mark.png' );

	$org = array(
		'@type'       => 'Organization',
		'@id'         => $org_id,
		'name'        => $name,
		'url'         => $home,
		'logo'        => $logo,
		'description' => wp_strip_all_tags( $content['footer']['blurb'] ),
		'knowsAbout'  => array( 'Search Engine Optimization', 'Answer Engine Optimization', 'Generative Engine Optimization', 'Local SEO', 'Structured data' ),
	);
	if ( ztr_setting( 'email' ) ) {
		$org['email'] = ztr_setting( 'email' );
	}
	if ( ztr_setting( 'phone' ) ) {
		$org['telephone'] = ztr_setting( 'phone' );
	}
	$same_as = array_values( ztr_social_profiles() );
	if ( $same_as ) {
		$org['sameAs'] = $same_as;
	}
	if ( ztr_setting( 'email' ) || ztr_setting( 'phone' ) ) {
		$org['contactPoint'] = array_filter(
			array(
				'@type'       => 'ContactPoint',
				'contactType' => 'sales',
				'email'       => ztr_setting( 'email' ),
				'telephone'   => ztr_setting( 'phone' ),
			)
		);
	}

	/**
	 * Skip our Organization node when an SEO plugin already outputs one.
	 * Default: include only if no SEO plugin is active.
	 */
	if ( apply_filters( 'ztr_schema_include_organization', ! ztr_seo_plugin_active() ) ) {
		$graph[] = $org;
		$graph[] = array(
			'@type'     => 'WebSite',
			'@id'       => $home . '#ztr-website',
			'url'       => $home,
			'name'      => $name,
			'publisher' => array( '@id' => $org_id ),
		);
	}

	$graph[] = array(
		'@type'       => 'WebPage',
		'@id'         => $page . '#webpage',
		'url'         => $page,
		'name'        => ztr_seo_title(),
		'description' => ztr_seo_description(),
		'about'       => array( '@id' => $org_id ),
		'inLanguage'  => get_bloginfo( 'language' ),
	);

	$area = ztr_setting( 'area_served' );
	foreach ( $content['services']['items'] as $service ) {
		$node = array(
			'@type'       => 'Service',
			'@id'         => $page . '#service-' . $service['key'],
			'name'        => $service['name'],
			'serviceType' => $service['name'],
			'description' => wp_strip_all_tags( $service['summary'] ),
			'provider'    => array( '@id' => $org_id ),
			'url'         => $page . '#service-' . $service['key'],
		);
		if ( $area ) {
			$node['areaServed'] = $area;
		}
		$graph[] = $node;
	}

	$faq = array();
	foreach ( $content['faq']['items'] as $item ) {
		$faq[] = array(
			'@type'          => 'Question',
			'name'           => wp_strip_all_tags( $item['q'] ),
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => wp_strip_all_tags( $item['a'] ),
			),
		);
	}
	if ( $faq ) {
		$graph[] = array(
			'@type'      => 'FAQPage',
			'@id'        => $page . '#faq',
			'mainEntity' => $faq,
		);
	}

	return apply_filters( 'ztr_schema_graph', $graph );
}

add_action(
	'wp_head',
	function () {
		if ( ! ztr_is_landing() ) {
			return;
		}
		$data = array(
			'@context' => 'https://schema.org',
			'@graph'   => ztr_schema_graph(),
		);
		echo "<script type=\"application/ld+json\">" . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG ) . "</script>\n";
	},
	20
);

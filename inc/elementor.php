<?php
/**
 * Elementor integration.
 *
 * Every landing section is an Elementor widget in a "Zero to Rank" category.
 * A widget renders the theme's own template part, so design and animations
 * stay exactly as built, while every text, list, link and image is an
 * editable control. Control defaults come from inc/content.php.
 *
 * Also: a one-time setup that turns the homepage into an Elementor page,
 * creates the Blog page (Elementor) and a sample post (noindex), and links
 * the Fluent Form used by the audit section.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

define( 'ZTR_SETUP_VERSION', '2.0.0' );

/**
 * Widget specs. Control keys equal the section's content keys.
 *
 * Control types: text, textarea, number, select, switcher, media (image),
 * url (link), lines (list ↔ one item per line), rows (list of records ↔
 * "a | b | c" per line, with 'cols'), repeater (with 'fields').
 *
 * @return array
 */
function ztr_widget_specs() {
	$head = array(
		'eyebrow' => array( 'type' => 'text', 'label' => 'Eyebrow' ),
		'title'   => array( 'type' => 'textarea', 'label' => 'Title', 'help' => 'Wrap words in <em>…</em> to set them in the serif highlight.' ),
		'intro'   => array( 'type' => 'textarea', 'label' => 'Intro text' ),
	);
	$anchor = array( 'anchor' => array( 'type' => 'text', 'label' => 'Section ID (for #links)' ) );
	$icons  = array( 'search' => 'Search', 'chat' => 'Chat', 'spark' => 'Spark', 'layers' => 'Layers', 'target' => 'Target', 'chart' => 'Chart', 'shield' => 'Shield', 'compass' => 'Compass', 'mic' => 'Microphone' );
	$logos  = array( '' => 'None (name only)' );
	foreach ( ztr_platform_logos() as $key => $logo ) {
		$logos[ $key ] = $logo[0];
	}

	return array(
		'ztr-hero'       => array(
			'title'    => 'ZTR Hero',
			'icon'     => 'eicon-banner',
			'part'     => 'hero',
			'controls' => array(
				'eyebrow'     => array( 'type' => 'text', 'label' => 'Eyebrow' ),
				'words'       => array(
					'type'        => 'repeater',
					'label'       => 'Headline words',
					'title_field' => '{{{ word }}}',
					'fields'      => array(
						'word'    => array( 'type' => 'text', 'label' => 'Word' ),
						'caption' => array( 'type' => 'text', 'label' => 'Caption' ),
						'accent'  => array( 'type' => 'switcher', 'label' => 'Highlight in ice blue' ),
					),
				),
				'proof'       => array(
					'type'        => 'repeater',
					'label'       => 'Result card (first two are shown)',
					'title_field' => '{{{ value }}} {{{ label }}}',
					'fields'      => array(
						'value' => array( 'type' => 'text', 'label' => 'Figure' ),
						'label' => array( 'type' => 'text', 'label' => 'Label' ),
					),
				),
				'lead_strong' => array( 'type' => 'text', 'label' => 'Lead (italic part)' ),
				'lead'        => array( 'type' => 'textarea', 'label' => 'Lead' ),
				'primary_cta' => array( 'type' => 'text', 'label' => 'CTA text' ),
				'cta_link'    => array( 'type' => 'url', 'label' => 'CTA link' ),
			),
		),
		'ztr-logos'      => array(
			'title'    => 'ZTR Logo Strip',
			'icon'     => 'eicon-logo',
			'part'     => 'logos',
			'controls' => array(
				'label' => array( 'type' => 'text', 'label' => 'Label' ),
				'items' => array(
					'type'        => 'repeater',
					'label'       => 'Logos',
					'title_field' => '{{{ name }}}',
					'fields'      => array(
						'name'  => array( 'type' => 'text', 'label' => 'Name' ),
						'logo'  => array( 'type' => 'select', 'label' => 'Built-in logo', 'options' => $logos ),
						'image' => array( 'type' => 'media', 'label' => 'Or upload a logo (overrides built-in)' ),
					),
				),
			),
		),
		'ztr-statement'  => array(
			'title'    => 'ZTR Statement',
			'icon'     => 'eicon-blockquote',
			'part'     => 'statement',
			'controls' => array(
				'eyebrow' => array( 'type' => 'text', 'label' => 'Eyebrow' ),
				'text'    => array( 'type' => 'textarea', 'label' => 'Statement', 'help' => 'Wrap the closing phrase in <em>…</em>.' ),
			),
		),
		'ztr-results'    => array(
			'title'    => 'ZTR Case Studies (stacked cards)',
			'icon'     => 'eicon-gallery-justified',
			'part'     => 'results',
			'controls' => array_merge(
				$anchor,
				$head,
				array(
					'cases'      => array(
						'type'        => 'repeater',
						'label'       => 'Case studies',
						'title_field' => '{{{ tab }}}',
						'fields'      => array(
							'key'      => array( 'type' => 'text', 'label' => 'ID (the card is #ztr-case-ID)' ),
							'tab'      => array( 'type' => 'text', 'label' => 'Short name' ),
							'sector'   => array( 'type' => 'text', 'label' => 'Sector line' ),
							'period'   => array( 'type' => 'text', 'label' => 'Period' ),
							'headline' => array( 'type' => 'textarea', 'label' => 'Headline' ),
							'metrics'  => array( 'type' => 'rows', 'cols' => array( 'value', 'label', 'detail' ), 'label' => 'Metrics (one per line: value | label | detail)' ),
							'sources'  => array( 'type' => 'rows', 'cols' => array( 'name', 'value' ), 'label' => 'AI sources (one per line: name | sessions) — optional' ),
							'footnote' => array( 'type' => 'textarea', 'label' => 'Footnote' ),
							'img1'     => array( 'type' => 'media', 'label' => 'Evidence image 1 (large)' ),
							'cap1'     => array( 'type' => 'text', 'label' => 'Caption 1' ),
							'img2'     => array( 'type' => 'media', 'label' => 'Evidence image 2' ),
							'cap2'     => array( 'type' => 'text', 'label' => 'Caption 2' ),
							'img3'     => array( 'type' => 'media', 'label' => 'Evidence image 3' ),
							'cap3'     => array( 'type' => 'text', 'label' => 'Caption 3' ),
						),
					),
					'cta_title'  => array( 'type' => 'text', 'label' => 'CTA band title' ),
					'cta_text'   => array( 'type' => 'textarea', 'label' => 'CTA band text' ),
					'cta_button' => array( 'type' => 'text', 'label' => 'CTA band button' ),
					'note'       => array( 'type' => 'textarea', 'label' => 'Small print' ),
				)
			),
		),
		'ztr-services'   => array(
			'title'    => 'ZTR Services (expanding panels)',
			'icon'     => 'eicon-slider-push',
			'part'     => 'services',
			'controls' => array_merge(
				$anchor,
				$head,
				array(
					'items' => array(
						'type'        => 'repeater',
						'label'       => 'Services',
						'title_field' => '{{{ abbr }}}',
						'fields'      => array(
							'key'          => array( 'type' => 'text', 'label' => 'Key (seo / aeo / geo — sets the icon, #link and audit focus)' ),
							'abbr'         => array( 'type' => 'text', 'label' => 'Short name' ),
							'name'         => array( 'type' => 'text', 'label' => 'Full name' ),
							'question'     => array( 'type' => 'text', 'label' => 'Question it answers' ),
							'summary'      => array( 'type' => 'textarea', 'label' => 'Summary' ),
							'deliverables' => array( 'type' => 'lines', 'label' => 'Deliverables (one per line)' ),
						),
					),
				)
			),
		),
		'ztr-shift'      => array(
			'title'    => 'ZTR Visibility Shift (comparison)',
			'icon'     => 'eicon-columns',
			'part'     => 'shift',
			'controls' => array_merge(
				$anchor,
				$head,
				array(
					'modes' => array(
						'type'        => 'repeater',
						'label'       => 'Columns (two)',
						'title_field' => '{{{ label }}}',
						'fields'      => array(
							'label'     => array( 'type' => 'text', 'label' => 'Title' ),
							'query'     => array( 'type' => 'text', 'label' => 'Example query' ),
							'steps'     => array( 'type' => 'lines', 'label' => 'How the buyer searches (one step per line)' ),
							'wins'      => array( 'type' => 'lines', 'label' => 'What earns visibility (one per line)' ),
							'summary'   => array( 'type' => 'textarea', 'label' => 'Bottom line' ),
							'highlight' => array( 'type' => 'switcher', 'label' => 'Highlight this column' ),
						),
					),
				)
			),
		),
		'ztr-process'    => array(
			'title'    => 'ZTR Process',
			'icon'     => 'eicon-time-line',
			'part'     => 'process',
			'controls' => array_merge(
				$anchor,
				$head,
				array(
					'steps' => array(
						'type'        => 'repeater',
						'label'       => 'Steps',
						'title_field' => '{{{ title }}}',
						'fields'      => array(
							'title'  => array( 'type' => 'text', 'label' => 'Title' ),
							'text'   => array( 'type' => 'textarea', 'label' => 'Text' ),
							'output' => array( 'type' => 'text', 'label' => 'Output' ),
						),
					),
				)
			),
		),
		'ztr-why'        => array(
			'title'    => 'ZTR Why Us',
			'icon'     => 'eicon-info-box',
			'part'     => 'why',
			'controls' => array_merge(
				$anchor,
				$head,
				array(
					'cta'             => array( 'type' => 'text', 'label' => 'Button text' ),
					'cta_note'        => array( 'type' => 'text', 'label' => 'Note under the button' ),
					'differentiators' => array(
						'type'        => 'repeater',
						'label'       => 'Reasons',
						'title_field' => '{{{ title }}}',
						'fields'      => array(
							'icon'  => array( 'type' => 'select', 'label' => 'Icon', 'options' => $icons ),
							'title' => array( 'type' => 'text', 'label' => 'Title' ),
							'text'  => array( 'type' => 'textarea', 'label' => 'Text' ),
						),
					),
				)
			),
		),
		'ztr-industries' => array(
			'title'    => 'ZTR Who We Help',
			'icon'     => 'eicon-tabs',
			'part'     => 'industries',
			'controls' => array_merge(
				$anchor,
				array(
					'eyebrow' => $head['eyebrow'],
					'title'   => $head['title'],
					'items'   => array(
						'type'        => 'repeater',
						'label'       => 'Industries',
						'title_field' => '{{{ name }}}',
						'fields'      => array(
							'key'       => array( 'type' => 'text', 'label' => 'ID (letters only)' ),
							'name'      => array( 'type' => 'text', 'label' => 'Name' ),
							'challenge' => array( 'type' => 'textarea', 'label' => 'The challenge' ),
							'approach'  => array( 'type' => 'lines', 'label' => 'How we approach it (one per line)' ),
							'channels'  => array( 'type' => 'lines', 'label' => 'Priority channels (seo, aeo, geo — one per line)' ),
						),
					),
				)
			),
		),
		'ztr-faq'        => array(
			'title'    => 'ZTR FAQ',
			'icon'     => 'eicon-accordion',
			'part'     => 'faq',
			'controls' => array_merge(
				$anchor,
				array(
					'eyebrow' => $head['eyebrow'],
					'title'   => $head['title'],
					'items'   => array(
						'type'        => 'repeater',
						'label'       => 'Questions',
						'title_field' => '{{{ q }}}',
						'fields'      => array(
							'q' => array( 'type' => 'text', 'label' => 'Question' ),
							'a' => array( 'type' => 'textarea', 'label' => 'Answer' ),
						),
					),
				)
			),
		),
		'ztr-audit'      => array(
			'title'    => 'ZTR Audit Form (CTA box)',
			'icon'     => 'eicon-form-horizontal',
			'part'     => 'audit',
			'controls' => array_merge(
				$anchor,
				$head,
				array(
					'form_id'  => array( 'type' => 'number', 'label' => 'Fluent Form ID (0 = the form set in Customizer → Zero to Rank)' ),
					'reassure' => array( 'type' => 'text', 'label' => 'Reassurance under the form' ),
				)
			),
		),
		'ztr-page-hero'  => array(
			'title'    => 'ZTR Page Hero',
			'icon'     => 'eicon-header',
			'part'     => 'page-hero',
			'controls' => array(
				'eyebrow' => array( 'type' => 'text', 'label' => 'Eyebrow' ),
				'title'   => array( 'type' => 'textarea', 'label' => 'Title', 'help' => 'Wrap words in <em>…</em> for the serif highlight.' ),
				'text'    => array( 'type' => 'textarea', 'label' => 'Text' ),
			),
		),
		'ztr-blog'       => array(
			'title'    => 'ZTR Blog Grid',
			'icon'     => 'eicon-posts-grid',
			'part'     => 'blog',
			'controls' => array_merge(
				$head,
				array(
					'per_page' => array( 'type' => 'number', 'label' => 'Posts per page' ),
				)
			),
		),
	);
}

/**
 * Content key for a widget's template part (page-hero → page_hero).
 *
 * @param string $part Part slug.
 * @return string
 */
function ztr_part_key( $part ) {
	return str_replace( '-', '_', $part );
}

/**
 * Theme value → Elementor setting.
 *
 * @param array $control Control spec.
 * @param mixed $val     Value.
 * @return mixed
 */
function ztr_value_to_setting( $control, $val ) {
	switch ( $control['type'] ) {
		case 'media':
			if ( is_numeric( $val ) && (int) $val ) {
				return array( 'id' => (int) $val, 'url' => (string) wp_get_attachment_url( (int) $val ) );
			}
			$img = ztr_image( $val );
			return array( 'id' => '', 'url' => $img['url'] );
		case 'url':
			return array( 'url' => (string) $val, 'is_external' => '', 'nofollow' => '' );
		case 'switcher':
			return ( $val && 'no' !== $val ) ? 'yes' : '';
		case 'lines':
			return implode( "\n", ztr_lines( $val ) );
		case 'rows':
			$lines = array();
			foreach ( (array) $val as $row ) {
				$cells = array();
				foreach ( $control['cols'] as $col ) {
					$cells[] = isset( $row[ $col ] ) ? $row[ $col ] : '';
				}
				$lines[] = rtrim( implode( ' | ', $cells ), ' |' );
			}
			return implode( "\n", $lines );
		case 'repeater':
			$items = array();
			foreach ( array_values( (array) $val ) as $n => $item ) {
				$row = array( '_id' => substr( md5( $n . wp_json_encode( $item ) ), 0, 7 ) );
				foreach ( $control['fields'] as $fk => $fc ) {
					$row[ $fk ] = ztr_value_to_setting( $fc, isset( $item[ $fk ] ) ? $item[ $fk ] : '' );
				}
				$items[] = $row;
			}
			return $items;
		default:
			return is_array( $val ) ? implode( "\n", $val ) : $val;
	}
}

/**
 * Elementor setting → theme value.
 *
 * @param array $control Control spec.
 * @param mixed $val     Setting.
 * @return mixed
 */
function ztr_setting_to_value( $control, $val ) {
	switch ( $control['type'] ) {
		case 'media':
			if ( is_array( $val ) ) {
				if ( ! empty( $val['id'] ) ) {
					return (int) $val['id'];
				}
				return isset( $val['url'] ) ? (string) $val['url'] : '';
			}
			return (string) $val;
		case 'url':
			return is_array( $val ) ? ( isset( $val['url'] ) ? (string) $val['url'] : '' ) : (string) $val;
		case 'switcher':
			return 'yes' === $val ? 'yes' : '';
		case 'number':
			return (int) $val;
		case 'lines':
			return ztr_lines( $val );
		case 'rows':
			return ztr_rows( $val, $control['cols'] );
		case 'repeater':
			$items = array();
			foreach ( (array) $val as $row ) {
				$item = array();
				foreach ( $control['fields'] as $fk => $fc ) {
					$item[ $fk ] = ztr_setting_to_value( $fc, isset( $row[ $fk ] ) ? $row[ $fk ] : '' );
				}
				$items[] = $item;
			}
			return $items;
		default:
			return (string) $val;
	}
}

/**
 * Settings for a widget built from the section defaults (for new pages).
 *
 * @param string $widget Widget name.
 * @param array  $over   Overrides.
 * @return array
 */
function ztr_widget_default_settings( $widget, $over = array() ) {
	$specs = ztr_widget_specs();
	$spec  = $specs[ $widget ];
	$data  = array_merge( ztr_content( ztr_part_key( $spec['part'] ) ), $over );
	$out   = array();
	foreach ( $spec['controls'] as $key => $control ) {
		if ( array_key_exists( $key, $data ) ) {
			$out[ $key ] = ztr_value_to_setting( $control, $data[ $key ] );
		}
	}
	return $out;
}

add_action(
	'elementor/elements/categories_registered',
	function ( $manager ) {
		$manager->add_category(
			'zero-to-rank',
			array(
				'title' => 'Zero to Rank',
				'icon'  => 'fa fa-plug',
			)
		);
	}
);

add_action(
	'elementor/widgets/register',
	function ( $widgets_manager ) {
		require_once ZTR_DIR . 'inc/elementor-widgets.php';
		foreach ( array_keys( ztr_widget_specs() ) as $name ) {
			$widgets_manager->register( ztr_make_widget( $name ) );
		}
	}
);

/* Load the theme's styles and scripts inside the Elementor editor preview. */
add_filter(
	'ztr_has_assets',
	function ( $on ) {
		return $on || ( isset( $_GET['elementor-preview'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}
);

/**
 * Elementor defaults that leave the theme in charge of colours and type.
 */
function ztr_elementor_defaults() {
	update_option( 'elementor_disable_color_schemes', 'yes' );
	update_option( 'elementor_disable_typography_schemes', 'yes' );
	$cpt = (array) get_option( 'elementor_cpt_support', array( 'page', 'post' ) );
	foreach ( array( 'page', 'post' ) as $t ) {
		if ( ! in_array( $t, $cpt, true ) ) {
			$cpt[] = $t;
		}
	}
	update_option( 'elementor_cpt_support', $cpt );
}

/* -------------------------------------------------------------------------
 * One-time setup (runs once per ZTR_SETUP_VERSION, after Elementor loads).
 * ---------------------------------------------------------------------- */

add_action( 'wp_loaded', 'ztr_maybe_setup', 20 );

/**
 * Run the setup steps once.
 */
function ztr_maybe_setup() {
	if ( get_option( 'ztr_setup_version' ) === ZTR_SETUP_VERSION || ! did_action( 'elementor/loaded' ) ) {
		return;
	}
	if ( ! apply_filters( 'ztr_auto_setup', true ) || get_transient( 'ztr_setup_lock' ) ) {
		return;
	}
	set_transient( 'ztr_setup_lock', 1, 5 * MINUTE_IN_SECONDS );

	$log   = array();
	ztr_elementor_defaults();
	$log[] = ztr_setup_form();
	$log[] = ztr_setup_home();
	$log[] = ztr_setup_blog_page();
	$log[] = ztr_setup_sample_post();

	if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
	update_option( 'ztr_setup_log', array( 'time' => current_time( 'mysql' ), 'log' => $log ), false );
	update_option( 'ztr_setup_version', ZTR_SETUP_VERSION );
	delete_transient( 'ztr_setup_lock' );
}

/**
 * Elementor data: one full-width, zero-padding container per widget.
 *
 * @param array $widgets List of array( widget name, overrides ).
 * @return array
 */
function ztr_build_elementor_data( $widgets ) {
	$zero = array( 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => true );
	$data = array();
	foreach ( $widgets as $n => $w ) {
		$data[] = array(
			'id'       => substr( md5( 'c' . $n . $w[0] ), 0, 7 ),
			'elType'   => 'container',
			'isInner'  => false,
			'settings' => array(
				'content_width' => 'full',
				'padding'       => $zero,
				'margin'        => $zero,
				'flex_gap'      => array( 'unit' => 'px', 'size' => 0, 'column' => '0', 'row' => '0', 'isLinked' => true ),
			),
			'elements' => array(
				array(
					'id'         => substr( md5( 'w' . $n . $w[0] ), 0, 7 ),
					'elType'     => 'widget',
					'widgetType' => $w[0],
					'settings'   => ztr_widget_default_settings( $w[0], isset( $w[1] ) ? $w[1] : array() ),
					'elements'   => array(),
				),
			),
		);
	}
	return $data;
}

/**
 * Save Elementor data to a post and switch it to the canvas template.
 *
 * @param int   $post_id Post ID.
 * @param array $widgets Widgets.
 */
function ztr_save_elementor_page( $post_id, $widgets ) {
	update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
	update_post_meta( $post_id, '_elementor_template_type', 'wp-page' );
	update_post_meta( $post_id, '_elementor_version', defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '3.0.0' );
	update_post_meta( $post_id, '_wp_page_template', ZTR_CANVAS );
	// Elementor stores its JSON slashed.
	update_post_meta( $post_id, '_elementor_data', wp_slash( wp_json_encode( ztr_build_elementor_data( $widgets ) ) ) );
}

/**
 * The homepage layout as widgets, in page order.
 *
 * @return array
 */
function ztr_home_widgets() {
	return array(
		array( 'ztr-hero' ),
		array( 'ztr-logos' ),
		array( 'ztr-statement' ),
		array( 'ztr-results' ),
		array( 'ztr-services' ),
		array( 'ztr-shift' ),
		array( 'ztr-process' ),
		array( 'ztr-why' ),
		array( 'ztr-industries' ),
		array( 'ztr-faq' ),
		array( 'ztr-audit' ),
	);
}

/**
 * Convert the front page (on the classic landing template, not yet built
 * with Elementor) into an Elementor page with the section widgets.
 *
 * @return string Log line.
 */
function ztr_setup_home() {
	$id = (int) get_option( 'page_on_front' );
	if ( ! $id || 'page' !== get_option( 'show_on_front' ) ) {
		return 'home: no static front page, skipped';
	}
	if ( get_post_meta( $id, '_elementor_data', true ) ) {
		return 'home: already built with Elementor, kept';
	}
	if ( ZTR_TEMPLATE !== get_post_meta( $id, '_wp_page_template', true ) ) {
		return 'home: not on the Zero to Rank template, skipped';
	}
	update_post_meta( $id, '_ztr_previous_template', ZTR_TEMPLATE );
	ztr_save_elementor_page( $id, ztr_home_widgets() );
	return 'home: converted #' . $id;
}

/**
 * Create the Blog page (Elementor: page hero + blog grid) if missing.
 *
 * @return string Log line.
 */
function ztr_setup_blog_page() {
	if ( get_page_by_path( 'blog', OBJECT, 'page' ) ) {
		return 'blog page: exists, kept';
	}
	$id = wp_insert_post(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'post_title'     => __( 'Blog', 'zero-to-rank' ),
			'post_name'      => 'blog',
			'post_content'   => '',
			'comment_status' => 'closed',
		),
		true
	);
	if ( is_wp_error( $id ) ) {
		return 'blog page: error ' . $id->get_error_message();
	}
	ztr_save_elementor_page(
		$id,
		array(
			array( 'ztr-page-hero' ),
			array( 'ztr-blog', array( 'eyebrow' => '', 'title' => '', 'intro' => '' ) ),
			array( 'ztr-audit' ),
		)
	);
	return 'blog page: created #' . $id;
}

/**
 * Publish one sample article (noindex) so the blog design can be reviewed.
 *
 * @return string Log line.
 */
function ztr_setup_sample_post() {
	$existing = get_posts(
		array(
			'post_type'   => 'post',
			'post_status' => 'any',
			'meta_key'    => '_ztr_sample', // phpcs:ignore WordPress.DB.SlowDBQuery
			'fields'      => 'ids',
			'numberposts' => 1,
		)
	);
	if ( $existing ) {
		return 'sample post: exists #' . $existing[0];
	}
	$cat = term_exists( 'AI search', 'category' );
	if ( ! $cat ) {
		$cat = wp_insert_term( 'AI search', 'category', array( 'slug' => 'ai-search' ) );
	}
	$cat_id = is_wp_error( $cat ) ? 0 : (int) ( is_array( $cat ) ? $cat['term_id'] : $cat );

	$id = wp_insert_post(
		array(
			'post_type'     => 'post',
			'post_status'   => 'publish',
			'post_title'    => __( 'SEO vs AEO vs GEO: what each one does and where to start', 'zero-to-rank' ),
			'post_name'     => 'seo-vs-aeo-vs-geo',
			'post_author'   => ztr_first_admin_id(),
			'post_excerpt'  => __( 'Search now happens on Google, in answer boxes and inside AI assistants. Here is what SEO, AEO and GEO each cover, how they overlap, and how to decide what to work on first.', 'zero-to-rank' ),
			'post_content'  => ztr_sample_post_content(),
			'post_category' => $cat_id ? array( $cat_id ) : array(),
			'meta_input'    => array(
				'_ztr_noindex' => 1,
				'_ztr_sample'  => 1,
			),
		),
		true
	);
	if ( is_wp_error( $id ) ) {
		return 'sample post: error ' . $id->get_error_message();
	}
	return 'sample post: created #' . $id . ' (noindex)';
}

/**
 * The first administrator (setup runs on a visitor request, with no user).
 *
 * @return int
 */
function ztr_first_admin_id() {
	$ids = get_users(
		array(
			'role'    => 'administrator',
			'number'  => 1,
			'orderby' => 'ID',
			'fields'  => 'ID',
		)
	);
	return $ids ? (int) $ids[0] : 0;
}

/**
 * Sample article body (block markup).
 *
 * @return string
 */
function ztr_sample_post_content() {
	$p = static function ( $t ) {
		return "<!-- wp:paragraph -->\n<p>" . $t . "</p>\n<!-- /wp:paragraph -->\n\n";
	};
	$h = static function ( $t ) {
		return "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">" . $t . "</h2>\n<!-- /wp:heading -->\n\n";
	};
	$ul = static function ( $items ) {
		$li = '';
		foreach ( $items as $i ) {
			$li .= '<!-- wp:list-item --><li>' . $i . '</li><!-- /wp:list-item -->';
		}
		return "<!-- wp:list -->\n<ul class=\"wp-block-list\">" . $li . "</ul>\n<!-- /wp:list -->\n\n";
	};
	return $p( 'Buyers no longer search in one place. They type a few words into Google, check the map, read the answer box, and then ask an AI assistant to compare the options. Each of those moments has its own rules, and each one decides who gets considered.' )
		. $p( 'That is why you now hear three terms: SEO, AEO and GEO. They are not competing ideas. They are three layers of the same goal: being the business people find, trust and choose.' )
		. $h( 'SEO: be found in the results' )
		. $p( 'Search Engine Optimization is the foundation. It makes your site crawlable, fast and relevant, and earns the authority that lets you rank for the searches closest to a buying decision.' )
		. $ul( array( 'Technical health: crawling, indexing, Core Web Vitals', 'Pages mapped to real search intent', 'Internal links and earned mentions that build authority' ) )
		. $h( 'AEO: be the answer' )
		. $p( 'Answer Engine Optimization structures your content so it can be lifted as a direct answer: featured snippets, People Also Ask, voice results and the answer panels at the top of the page.' )
		. $ul( array( 'Question research from real buyer queries', 'Answer-first sections with clear definitions', 'FAQ, HowTo and Article structured data' ) )
		. $h( 'GEO: be cited by AI' )
		. $p( 'Generative Engine Optimization helps AI assistants such as ChatGPT, Gemini, Claude and Perplexity understand, trust and recommend your brand. They favour sources that are consistent, specific and corroborated elsewhere.' )
		. $ul( array( 'A consistent entity: the same facts about you everywhere', 'Citable, evidence-rich content with original data', 'Mentions on the sources AI assistants already trust' ) )
		. $h( 'Where to start' )
		. $p( 'Start with SEO foundations, because answer engines and AI assistants draw on content that is already crawlable and trusted. Then make your key pages answer-ready, and build the entity and mention signals that earn citations. Done together, each layer makes the others stronger.' )
		. $p( 'Not sure which layer is holding you back? A visibility audit shows where you appear today across search, answer features and AI assistants, and what to fix first.' );
}

/**
 * Link the audit section to the Fluent Form named for Zero to Rank, unless a
 * form is already set in the Customizer.
 *
 * @return string Log line.
 */
function ztr_setup_form() {
	if ( (int) get_theme_mod( 'ztr_form_audit', 0 ) ) {
		return 'form: kept #' . (int) get_theme_mod( 'ztr_form_audit' );
	}
	global $wpdb;
	$table = $wpdb->prefix . 'fluentform_forms';
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery
	if ( $table !== $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) ) ) {
		return 'form: Fluent Forms not installed';
	}
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
	$id = (int) $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$table} WHERE title = %s ORDER BY id DESC LIMIT 1", 'Zero to Rank – Free Visibility Audit' ) );
	if ( $id ) {
		set_theme_mod( 'ztr_form_audit', $id );
	}
	return 'form: ' . ( $id ? 'linked #' . $id : 'not found (built-in form used)' );
}

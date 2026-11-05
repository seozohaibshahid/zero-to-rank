<?php
/**
 * Zero to Rank Elementor widget classes. Loaded only when Elementor
 * registers widgets. Controls and rendering come from ztr_widget_specs().
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;

/**
 * Shared behaviour for every section widget.
 */
abstract class ZTR_Widget extends Widget_Base {

	/** Widget name, e.g. ztr-hero. */
	const ZTR_NAME = '';

	/**
	 * This widget's spec.
	 *
	 * @return array
	 */
	protected function ztr_spec() {
		$specs = ztr_widget_specs();
		return $specs[ static::ZTR_NAME ];
	}

	public function get_name() {
		return static::ZTR_NAME;
	}

	public function get_title() {
		$s = $this->ztr_spec();
		return $s['title'];
	}

	public function get_icon() {
		$s = $this->ztr_spec();
		return $s['icon'];
	}

	public function get_categories() {
		return array( 'zero-to-rank' );
	}

	public function get_keywords() {
		return array( 'zero to rank', 'ztr', 'section' );
	}

	/**
	 * Elementor arguments for one control.
	 *
	 * @param array $control Spec.
	 * @return array
	 */
	protected function ztr_control_args( $control ) {
		$types = array(
			'text'     => Controls_Manager::TEXT,
			'textarea' => Controls_Manager::TEXTAREA,
			'lines'    => Controls_Manager::TEXTAREA,
			'rows'     => Controls_Manager::TEXTAREA,
			'number'   => Controls_Manager::NUMBER,
			'select'   => Controls_Manager::SELECT,
			'switcher' => Controls_Manager::SWITCHER,
			'media'    => Controls_Manager::MEDIA,
			'url'      => Controls_Manager::URL,
		);
		$args = array(
			'label'       => $control['label'],
			'type'        => $types[ $control['type'] ],
			'label_block' => in_array( $control['type'], array( 'text', 'textarea', 'lines', 'rows', 'url', 'media' ), true ),
		);
		if ( 'select' === $control['type'] ) {
			$args['options'] = $control['options'];
		}
		if ( in_array( $control['type'], array( 'textarea', 'lines', 'rows' ), true ) ) {
			$args['rows'] = 'textarea' === $control['type'] ? 3 : 5;
		}
		if ( 'url' === $control['type'] ) {
			$args['options'] = false;
		}
		if ( 'switcher' === $control['type'] ) {
			$args['return_value'] = 'yes';
		}
		if ( ! empty( $control['help'] ) ) {
			$args['description'] = esc_html( $control['help'] );
		}
		return $args;
	}

	protected function register_controls() {
		$spec     = $this->ztr_spec();
		$defaults = ztr_widget_default_settings( static::ZTR_NAME );

		$this->start_controls_section( 'ztr_content', array( 'label' => __( 'Content', 'zero-to-rank' ) ) );
		foreach ( $spec['controls'] as $key => $control ) {
			if ( 'repeater' === $control['type'] ) {
				$rep = new Repeater();
				foreach ( $control['fields'] as $fk => $fc ) {
					$rep->add_control( $fk, $this->ztr_control_args( $fc ) );
				}
				$this->add_control(
					$key,
					array(
						'label'       => $control['label'],
						'type'        => Controls_Manager::REPEATER,
						'fields'      => $rep->get_controls(),
						'default'     => isset( $defaults[ $key ] ) ? $defaults[ $key ] : array(),
						'title_field' => $control['title_field'],
					)
				);
				continue;
			}
			$args = $this->ztr_control_args( $control );
			if ( isset( $defaults[ $key ] ) ) {
				$args['default'] = $defaults[ $key ];
			}
			$this->add_control( $key, $args );
		}
		$this->end_controls_section();
	}

	protected function render() {
		$spec     = $this->ztr_spec();
		$settings = $this->get_settings_for_display();
		$args     = array();
		foreach ( $spec['controls'] as $key => $control ) {
			if ( array_key_exists( $key, $settings ) ) {
				$args[ $key ] = ztr_setting_to_value( $control, $settings[ $key ] );
			}
		}
		ztr_section( $spec['part'], $args );
	}
}

// phpcs:disable Generic.Files.OneObjectStructurePerFile
class ZTR_Widget_Hero extends ZTR_Widget { const ZTR_NAME = 'ztr-hero'; }
class ZTR_Widget_Logos extends ZTR_Widget { const ZTR_NAME = 'ztr-logos'; }
class ZTR_Widget_Statement extends ZTR_Widget { const ZTR_NAME = 'ztr-statement'; }
class ZTR_Widget_Results extends ZTR_Widget { const ZTR_NAME = 'ztr-results'; }
class ZTR_Widget_Services extends ZTR_Widget { const ZTR_NAME = 'ztr-services'; }
class ZTR_Widget_Shift extends ZTR_Widget { const ZTR_NAME = 'ztr-shift'; }
class ZTR_Widget_Process extends ZTR_Widget { const ZTR_NAME = 'ztr-process'; }
class ZTR_Widget_Why extends ZTR_Widget { const ZTR_NAME = 'ztr-why'; }
class ZTR_Widget_Industries extends ZTR_Widget { const ZTR_NAME = 'ztr-industries'; }
class ZTR_Widget_Faq extends ZTR_Widget { const ZTR_NAME = 'ztr-faq'; }
class ZTR_Widget_Audit extends ZTR_Widget { const ZTR_NAME = 'ztr-audit'; }
class ZTR_Widget_PageHero extends ZTR_Widget { const ZTR_NAME = 'ztr-page-hero'; }
class ZTR_Widget_Blog extends ZTR_Widget { const ZTR_NAME = 'ztr-blog'; }

/**
 * Build a widget instance by name.
 *
 * @param string $name Widget name.
 * @return ZTR_Widget
 */
function ztr_make_widget( $name ) {
	$map = array(
		'ztr-hero' => 'ZTR_Widget_Hero',
		'ztr-logos' => 'ZTR_Widget_Logos',
		'ztr-statement' => 'ZTR_Widget_Statement',
		'ztr-results' => 'ZTR_Widget_Results',
		'ztr-services' => 'ZTR_Widget_Services',
		'ztr-shift' => 'ZTR_Widget_Shift',
		'ztr-process' => 'ZTR_Widget_Process',
		'ztr-why' => 'ZTR_Widget_Why',
		'ztr-industries' => 'ZTR_Widget_Industries',
		'ztr-faq' => 'ZTR_Widget_Faq',
		'ztr-audit' => 'ZTR_Widget_Audit',
		'ztr-page-hero' => 'ZTR_Widget_PageHero',
		'ztr-blog' => 'ZTR_Widget_Blog',
	);
	return new $map[ $name ]();
}

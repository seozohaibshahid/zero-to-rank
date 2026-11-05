<?php
/**
 * 05 — Services as expanding panels: three tall columns side by side; the
 * active one widens to show what the service covers (hover, focus or tap
 * moves it — interactions in main.js). Stacked and fully open on small
 * screens and without JavaScript. Each CTA pre-selects the audit focus.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$c      = ztr_args( 'services', isset( $args ) ? $args : array() );
$items  = array_values( (array) $c['items'] );
$anchor = ! empty( $c['anchor'] ) ? $c['anchor'] : 'services';
$icons  = array(
	'seo' => 'search',
	'aeo' => 'chat',
	'geo' => 'spark',
);
?>
<section class="ztr-section ztr-services" id="<?php echo esc_attr( $anchor ); ?>" aria-labelledby="ztr-services-title" data-ztr-section="services">
	<div class="ztr-container">
		<header class="ztr-head ztr-head--split">
			<div>
				<p class="ztr-eyebrow" data-ztr-reveal><span></span><?php echo esc_html( $c['eyebrow'] ); ?></p>
				<h2 class="ztr-h2" id="ztr-services-title" data-ztr-words><?php echo ztr_kses_inline( $c['title'] ); // phpcs:ignore ?></h2>
			</div>
			<p class="ztr-lead" data-ztr-reveal><?php echo esc_html( $c['intro'] ); ?></p>
		</header>

		<div class="ztr-panels" data-ztr-panels data-ztr-reveal="card">
			<?php
			foreach ( $items as $i => $s ) :
				$key  = ! empty( $s['key'] ) ? sanitize_key( $s['key'] ) : sanitize_key( isset( $s['abbr'] ) ? $s['abbr'] : 'service-' . $i );
				$list = ztr_lines( isset( $s['deliverables'] ) ? $s['deliverables'] : array() );
				?>
				<article class="ztr-panel ztr-panel--<?php echo (int) ( $i % 3 ) + 1; ?><?php echo 0 === $i ? ' is-open' : ''; ?>" id="service-<?php echo esc_attr( $key ); ?>" data-abbr="<?php echo esc_attr( $s['abbr'] ); ?>" data-ztr-panel-item>
					<button class="ztr-panel__toggle" type="button" aria-expanded="<?php echo 0 === $i ? 'true' : 'false'; ?>" aria-controls="ztr-panel-body-<?php echo esc_attr( $key ); ?>">
						<span class="ztr-panel__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
						<span class="ztr-panel__abbr"><?php echo esc_html( $s['abbr'] ); ?></span>
						<span class="ztr-panel__name"><?php echo esc_html( $s['name'] ); ?></span>
						<span class="ztr-panel__q"><?php echo esc_html( $s['question'] ); ?></span>
						<span class="ztr-panel__ico" aria-hidden="true"><?php echo ztr_icon( isset( $icons[ $key ] ) ? $icons[ $key ] : 'target' ); // phpcs:ignore ?></span>
					</button>
					<div class="ztr-panel__body" id="ztr-panel-body-<?php echo esc_attr( $key ); ?>">
						<p class="ztr-panel__summary"><?php echo esc_html( $s['summary'] ); ?></p>
						<?php if ( $list ) : ?>
							<ul class="ztr-ticks">
								<?php foreach ( $list as $d ) : ?>
									<li><?php echo esc_html( $d ); ?></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
						<a class="ztr-btn ztr-btn--glow" href="<?php echo esc_url( ztr_link( ztr_audit_url() ) ); ?>" data-ztr-focus="<?php echo esc_attr( $key ); ?>" <?php echo ztr_track_attrs( 'cta_click', 'service_' . $key, 'Discuss ' . $s['abbr'] ); // phpcs:ignore ?>>
							<span>
							<?php
							/* translators: %s: service abbreviation. */
							printf( esc_html__( 'Get a free %s audit', 'zero-to-rank' ), esc_html( $s['abbr'] ) );
							?>
							</span>
							<span class="ztr-btn__arr" aria-hidden="true"><?php echo ztr_icon( 'arrow-right' ); // phpcs:ignore ?></span>
						</a>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
/**
 * 06 — Services: SEO, AEO and GEO cards with hover/focus expansion.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$c = ztr_content( 'services' );
?>
<section class="ztr-section ztr-services" id="services" aria-labelledby="ztr-services-title" data-ztr-section="services">
	<div class="ztr-container">
		<header class="ztr-head ztr-head--split">
			<div>
				<p class="ztr-eyebrow" data-ztr-reveal><span>06</span><?php echo esc_html( $c['eyebrow'] ); ?></p>
				<h2 class="ztr-h2" id="ztr-services-title" data-ztr-reveal><?php echo ztr_kses_inline( $c['title'] ); // phpcs:ignore ?></h2>
			</div>
			<p class="ztr-lead" data-ztr-reveal><?php echo esc_html( $c['intro'] ); ?></p>
		</header>

		<div class="ztr-svc" data-ztr-services>
			<?php foreach ( $c['items'] as $i => $s ) : ?>
				<article class="ztr-svc__card ztr-svc__card--<?php echo esc_attr( $s['key'] ); ?><?php echo 0 === $i ? ' is-open' : ''; ?>" id="service-<?php echo esc_attr( $s['key'] ); ?>" data-ztr-service="<?php echo esc_attr( $s['key'] ); ?>" data-ztr-reveal data-ztr-spot style="--d:<?php echo (int) $i; ?>">
					<div class="ztr-svc__top">
						<span class="ztr-svc__num ztr-mono">0<?php echo (int) $i + 1; ?></span>
						<span class="ztr-svc__q"><?php echo esc_html( $s['question'] ); ?></span>
					</div>

					<div class="ztr-svc__visual" aria-hidden="true">
						<?php if ( 'seo' === $s['key'] ) : ?>
							<span class="ztr-viz-bars"><i></i><i></i><i></i><i></i><i></i></span>
						<?php elseif ( 'aeo' === $s['key'] ) : ?>
							<span class="ztr-viz-answer"><i></i><i></i><i></i><b></b></span>
						<?php else : ?>
							<span class="ztr-viz-orbit"><i></i><i></i><i></i><b></b></span>
						<?php endif; ?>
					</div>

					<h3 class="ztr-svc__title">
						<button class="ztr-svc__toggle" type="button" aria-expanded="<?php echo 0 === $i ? 'true' : 'false'; ?>" aria-controls="ztr-svc-body-<?php echo esc_attr( $s['key'] ); ?>" data-ztr-service-toggle>
							<span class="ztr-svc__abbr"><?php echo esc_html( $s['abbr'] ); ?></span>
							<span class="ztr-svc__name"><?php echo esc_html( $s['name'] ); ?></span>
						</button>
					</h3>

					<div class="ztr-svc__body" id="ztr-svc-body-<?php echo esc_attr( $s['key'] ); ?>">
						<div class="ztr-svc__body-inner">
							<p class="ztr-svc__summary"><?php echo esc_html( $s['summary'] ); ?></p>
							<ul class="ztr-svc__list">
								<?php foreach ( $s['deliverables'] as $d ) : ?>
									<li><?php echo ztr_icon( 'check' ); // phpcs:ignore ?><span><?php echo esc_html( $d ); ?></span></li>
								<?php endforeach; ?>
							</ul>
							<a class="ztr-btn ztr-btn--dark ztr-btn--sm" href="<?php echo esc_url( ztr_audit_url() ); ?>" data-ztr-focus="<?php echo esc_attr( $s['key'] ); ?>" <?php echo ztr_track_attrs( 'cta_click', 'service_' . $s['key'], 'Discuss ' . $s['abbr'] ); // phpcs:ignore ?>>
								<?php
								/* translators: %s: service abbreviation. */
								printf( esc_html__( 'Get a %s audit', 'zero-to-rank' ), esc_html( $s['abbr'] ) );
								?>
								<?php echo ztr_icon( 'arrow-up-right' ); // phpcs:ignore ?>
							</a>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

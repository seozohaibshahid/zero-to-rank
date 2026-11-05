<?php
/**
 * 04 — Services: a bento grid. A large feature card sums up the three
 * channels, then one card per service with its deliverables. Cards tilt and
 * catch a spotlight under the pointer; each CTA pre-selects the audit focus.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$c     = ztr_content( 'services' );
$feat  = isset( $c['feature'] ) ? $c['feature'] : array();
$icons = array(
	'seo' => 'search',
	'aeo' => 'chat',
	'geo' => 'spark',
);
/* Card treatments, in order: brand blue, white, deep indigo. */
$looks = array( 'is-blue', 'is-wide', 'is-wide is-dark' );
?>
<section class="ztr-section ztr-services" id="services" aria-labelledby="ztr-services-title" data-ztr-section="services">
	<div class="ztr-container">
		<header class="ztr-head">
			<p class="ztr-eyebrow" data-ztr-reveal><span>04</span><?php echo esc_html( $c['eyebrow'] ); ?></p>
			<h2 class="ztr-h2" id="ztr-services-title" data-ztr-words><?php echo ztr_kses_inline( $c['title'] ); // phpcs:ignore ?></h2>
			<p class="ztr-lead" data-ztr-reveal><?php echo esc_html( $c['intro'] ); ?></p>
		</header>

		<div class="ztr-bento">
			<?php if ( $feat ) : ?>
				<article class="ztr-svc ztr-svc--feat is-dark" data-ztr-reveal="card" data-ztr-tilt>
					<span class="ztr-svc__go" aria-hidden="true"><?php echo ztr_icon( 'arrow-right' ); // phpcs:ignore ?></span>
					<span class="ztr-svc__ico" aria-hidden="true"><?php echo ztr_icon( 'layers' ); // phpcs:ignore ?></span>
					<h3 class="ztr-svc__title"><?php echo ztr_kses_inline( $feat['title'] ); // phpcs:ignore ?></h3>
					<p class="ztr-svc__text"><?php echo esc_html( $feat['text'] ); ?></p>
					<ul class="ztr-svc__tri">
						<?php foreach ( $c['items'] as $s ) : ?>
							<li>
								<a href="#service-<?php echo esc_attr( $s['key'] ); ?>">
									<b><?php echo esc_html( $s['abbr'] ); ?></b>
									<small><?php echo esc_html( $s['question'] ); ?></small>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</article>
			<?php endif; ?>

			<?php foreach ( $c['items'] as $i => $s ) : ?>
				<article class="ztr-svc <?php echo esc_attr( isset( $looks[ $i ] ) ? $looks[ $i ] : '' ); ?>" id="service-<?php echo esc_attr( $s['key'] ); ?>" data-ztr-reveal="card" data-ztr-tilt style="--d:<?php echo (int) $i + 1; ?>">
					<span class="ztr-svc__go" aria-hidden="true"><?php echo ztr_icon( 'arrow-right' ); // phpcs:ignore ?></span>
					<span class="ztr-svc__ico" aria-hidden="true"><?php echo ztr_icon( isset( $icons[ $s['key'] ] ) ? $icons[ $s['key'] ] : 'target' ); // phpcs:ignore ?></span>
					<p class="ztr-svc__abbr"><?php echo esc_html( $s['abbr'] ); ?> · <?php echo esc_html( $s['question'] ); ?></p>
					<h3 class="ztr-svc__title"><?php echo esc_html( $s['name'] ); ?></h3>
					<p class="ztr-svc__text"><?php echo esc_html( $s['summary'] ); ?></p>
					<ul class="ztr-chips">
						<?php foreach ( $s['deliverables'] as $d ) : ?>
							<li class="ztr-chip"><?php echo esc_html( $d ); ?></li>
						<?php endforeach; ?>
					</ul>
					<a class="ztr-svc__link" href="<?php echo esc_url( ztr_audit_url() ); ?>" data-ztr-focus="<?php echo esc_attr( $s['key'] ); ?>" <?php echo ztr_track_attrs( 'cta_click', 'service_' . $s['key'], 'Discuss ' . $s['abbr'] ); // phpcs:ignore ?>>
						<?php
						/* translators: %s: service abbreviation. */
						printf( esc_html__( 'Get a %s audit', 'zero-to-rank' ), esc_html( $s['abbr'] ) );
						?>
						<?php echo ztr_icon( 'arrow-right' ); // phpcs:ignore ?>
					</a>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

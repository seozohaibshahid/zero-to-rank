<?php
/**
 * 06 — Services: SEO, AEO and GEO as numbered rows that expand
 * (native <details>, animated by the accordion script).
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

		<div class="ztr-svc-list" data-ztr-accordion="service_expand">
			<?php foreach ( $c['items'] as $i => $s ) : ?>
				<details class="ztr-acc ztr-svc-row" id="service-<?php echo esc_attr( $s['key'] ); ?>" data-ztr-reveal<?php echo 0 === $i ? ' open' : ''; ?>>
					<summary class="ztr-acc__q ztr-svc-row__head">
						<span class="ztr-svc-row__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
						<h3 class="ztr-svc-row__title"><?php echo esc_html( $s['name'] ); ?> <span class="ztr-svc-row__abbr"><?php echo esc_html( $s['abbr'] ); ?></span></h3>
						<span class="ztr-svc-row__q"><?php echo esc_html( $s['question'] ); ?></span>
						<span class="ztr-acc__icon" aria-hidden="true"></span>
					</summary>
					<div class="ztr-acc__a">
						<div class="ztr-svc-row__body">
							<p><?php echo esc_html( $s['summary'] ); ?></p>
							<ul class="ztr-svc-row__list">
								<?php foreach ( $s['deliverables'] as $d ) : ?>
									<li><?php echo esc_html( $d ); ?></li>
								<?php endforeach; ?>
							</ul>
							<a class="ztr-link" href="<?php echo esc_url( ztr_audit_url() ); ?>" data-ztr-focus="<?php echo esc_attr( $s['key'] ); ?>" <?php echo ztr_track_attrs( 'cta_click', 'service_' . $s['key'], 'Discuss ' . $s['abbr'] ); // phpcs:ignore ?>>
								<?php
								/* translators: %s: service abbreviation. */
								printf( esc_html__( 'Get a %s audit', 'zero-to-rank' ), esc_html( $s['abbr'] ) );
								?>
								<?php echo ztr_icon( 'arrow-right' ); // phpcs:ignore ?>
							</a>
						</div>
					</div>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>

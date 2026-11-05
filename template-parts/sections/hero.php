<?php
/**
 * 02 — Hero: headline, CTAs and a strip of real, verifiable results that
 * links to the case studies.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$c = ztr_content( 'hero' );
?>
<section class="ztr-hero ztr-dark" aria-labelledby="ztr-hero-title" data-ztr-section="hero">
	<div class="ztr-container ztr-hero__layout">
		<div class="ztr-hero__copy">
			<p class="ztr-pill ztr-hero__eyebrow" data-ztr-intro="0">
				<span class="ztr-pill__dot" aria-hidden="true"></span>
				<?php echo esc_html( $c['eyebrow'] ); ?>
			</p>

			<h1 class="ztr-hero__title" id="ztr-hero-title">
				<?php foreach ( $c['title_lines'] as $i => $line ) : ?>
					<span class="ztr-hero__line ztr-hero__line--<?php echo (int) $i + 1; ?>"><span class="ztr-hero__line-inner" data-ztr-intro="<?php echo (int) $i + 1; ?>"><?php echo ztr_kses_inline( $line ); // phpcs:ignore ?></span></span>
				<?php endforeach; ?>
			</h1>

			<p class="ztr-hero__lead" data-ztr-intro="3">
				<strong><?php echo esc_html( $c['lead_strong'] ); ?></strong>
				<?php echo esc_html( $c['lead'] ); ?>
			</p>

			<div class="ztr-hero__ctas" data-ztr-intro="4">
				<a class="ztr-btn ztr-btn--lime ztr-btn--lg" href="<?php echo esc_url( ztr_audit_url() ); ?>" <?php echo ztr_track_attrs( 'cta_click', 'hero_primary', $c['primary_cta'] ); // phpcs:ignore ?>>
					<span><?php echo esc_html( $c['primary_cta'] ); ?></span>
					<?php echo ztr_icon( 'arrow-up-right' ); // phpcs:ignore ?>
				</a>
				<a class="ztr-btn ztr-btn--ghost ztr-btn--lg" href="#results" <?php echo ztr_track_attrs( 'cta_click', 'hero_secondary', $c['secondary_cta'] ); // phpcs:ignore ?>>
					<span><?php echo esc_html( $c['secondary_cta'] ); ?></span>
					<?php echo ztr_icon( 'arrow-down' ); // phpcs:ignore ?>
				</a>
			</div>
		</div>

		<?php if ( ! empty( $c['proof'] ) ) : ?>
			<ul class="ztr-hero__proof" data-ztr-intro="4" aria-label="<?php esc_attr_e( 'Selected client results', 'zero-to-rank' ); ?>">
				<?php foreach ( $c['proof'] as $p ) : ?>
					<li>
						<a href="<?php echo esc_url( $p['href'] ); ?>" <?php echo ztr_track_attrs( 'cta_click', 'hero_proof', $p['label'] ); // phpcs:ignore ?>>
							<span class="ztr-hero__proof-value"><?php echo esc_html( $p['value'] ); ?></span>
							<span class="ztr-hero__proof-label"><?php echo esc_html( $p['label'] ); ?></span>
							<span class="ztr-hero__proof-who ztr-mono"><?php echo esc_html( $p['who'] ); ?></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</section>

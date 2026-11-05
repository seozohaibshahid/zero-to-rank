<?php
/**
 * 02 — Hero: a full-bleed blue gradient panel with a three-word headline
 * (one word per channel), a glass card of real results and a bold CTA.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$c     = ztr_content( 'hero' );
$words = ! empty( $c['words'] ) ? $c['words'] : array();
$proof = ! empty( $c['proof'] ) ? array_slice( $c['proof'], 0, 2 ) : array();
?>
<section class="ztr-hero" aria-labelledby="ztr-hero-title" data-ztr-section="hero">
	<div class="ztr-hero__panel">
		<div class="ztr-hero__inner">
			<p class="ztr-hero__eyebrow" data-ztr-intro="0"><?php echo esc_html( $c['eyebrow'] ); ?></p>

			<h1 class="ztr-hero__words" id="ztr-hero-title">
				<?php foreach ( $words as $i => $w ) : ?>
					<span class="ztr-hero__word<?php echo ! empty( $w['accent'] ) ? ' is-accent' : ''; ?>" data-ztr-intro="<?php echo (int) $i + 1; ?>">
						<span class="ztr-hero__word-text"><?php echo esc_html( $w['word'] ); ?></span>
						<span class="ztr-hero__caption"><?php echo esc_html( $w['caption'] ); ?></span>
					</span>
				<?php endforeach; ?>
			</h1>

			<div class="ztr-hero__foot">
				<?php if ( $proof ) : ?>
					<a class="ztr-hero__card" href="#results" data-ztr-intro="4" <?php echo ztr_track_attrs( 'cta_click', 'hero_card', 'Client results' ); // phpcs:ignore ?>>
						<span class="ztr-hero__card-title"><?php esc_html_e( 'Real client results', 'zero-to-rank' ); ?></span>
						<span class="ztr-hero__card-stats">
							<?php foreach ( $proof as $p ) : ?>
								<span class="ztr-hero__card-stat">
									<b><?php echo esc_html( $p['value'] ); ?></b>
									<span><?php echo esc_html( $p['label'] ); ?></span>
								</span>
							<?php endforeach; ?>
						</span>
					</a>
				<?php endif; ?>

				<p class="ztr-hero__lead" data-ztr-intro="3">
					<strong><?php echo esc_html( $c['lead_strong'] ); ?></strong>
					<?php echo esc_html( $c['lead'] ); ?>
				</p>

				<a class="ztr-hero__cta" href="<?php echo esc_url( ztr_audit_url() ); ?>" data-ztr-intro="4" <?php echo ztr_track_attrs( 'cta_click', 'hero_primary', $c['primary_cta'] ); // phpcs:ignore ?>>
					<span><?php echo esc_html( $c['primary_cta'] ); ?></span>
					<?php echo ztr_icon( 'arrow-up-right' ); // phpcs:ignore ?>
				</a>
			</div>
		</div>
	</div>
</section>

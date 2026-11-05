<?php
/**
 * 08 — Why Zero to Rank: the argument stays pinned on the left with a CTA
 * and the working principles; four numbered reason cards scroll past on the
 * right.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$c      = ztr_args( 'why', isset( $args ) ? $args : array() );
$trust  = ztr_content( 'trust' );
$anchor = ! empty( $c['anchor'] ) ? $c['anchor'] : 'why';
?>
<section class="ztr-section ztr-why" id="<?php echo esc_attr( $anchor ); ?>" aria-labelledby="ztr-why-title" data-ztr-section="why">
	<div class="ztr-container ztr-why__grid">
		<div class="ztr-why__intro">
			<p class="ztr-eyebrow" data-ztr-reveal><span></span><?php echo esc_html( $c['eyebrow'] ); ?></p>
			<h2 class="ztr-h2" id="ztr-why-title" data-ztr-words><?php echo ztr_kses_inline( $c['title'] ); // phpcs:ignore ?></h2>
			<p class="ztr-lead" data-ztr-reveal><?php echo esc_html( $c['intro'] ); ?></p>
			<?php if ( ! empty( $c['cta'] ) ) : ?>
				<div class="ztr-why__cta" data-ztr-reveal>
					<a class="ztr-btn ztr-btn--blue ztr-btn--lg" href="<?php echo esc_url( ztr_link( ztr_audit_url() ) ); ?>" data-ztr-magnetic <?php echo ztr_track_attrs( 'cta_click', 'why', $c['cta'] ); // phpcs:ignore ?>>
						<span><?php echo esc_html( $c['cta'] ); ?></span>
						<span class="ztr-btn__arr" aria-hidden="true"><?php echo ztr_icon( 'arrow-right' ); // phpcs:ignore ?></span>
					</a>
					<?php if ( ! empty( $c['cta_note'] ) ) : ?>
						<p class="ztr-why__note"><?php echo ztr_icon( 'shield' ); // phpcs:ignore ?><?php echo esc_html( $c['cta_note'] ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $trust['principles'] ) ) : ?>
				<ul class="ztr-why__principles" data-ztr-reveal>
					<?php foreach ( $trust['principles'] as $p ) : ?>
						<li><?php echo ztr_icon( $p['icon'] ); // phpcs:ignore ?><span><?php echo esc_html( $p['text'] ); ?></span></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>

		<ol class="ztr-reasons">
			<?php foreach ( array_values( (array) $c['differentiators'] ) as $i => $d ) : ?>
				<li class="ztr-reason" data-ztr-reveal="card" data-ztr-tilt style="--d:<?php echo (int) $i % 2; ?>">
					<span class="ztr-reason__n" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
					<span class="ztr-reason__ic" aria-hidden="true"><?php echo ztr_icon( ! empty( $d['icon'] ) ? $d['icon'] : 'target' ); // phpcs:ignore ?></span>
					<h3 class="ztr-reason__title"><?php echo esc_html( $d['title'] ); ?></h3>
					<p><?php echo esc_html( $d['text'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>

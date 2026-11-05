<?php
/**
 * 07 — Why Zero to Rank: differentiators and scroll-triggered count-up metrics.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$c = ztr_content( 'why' );
?>
<section class="ztr-section ztr-why" id="why" aria-labelledby="ztr-why-title" data-ztr-section="why">
	<div class="ztr-container">
		<header class="ztr-head ztr-head--center">
			<div>
				<p class="ztr-eyebrow" data-ztr-reveal><span>07</span><?php echo esc_html( $c['eyebrow'] ); ?></p>
				<h2 class="ztr-h2" id="ztr-why-title" data-ztr-reveal><?php echo ztr_kses_inline( $c['title'] ); // phpcs:ignore ?></h2>
			</div>
			<p class="ztr-lead" data-ztr-reveal><?php echo esc_html( $c['intro'] ); ?></p>
		</header>

		<dl class="ztr-stats">
			<?php foreach ( $c['metrics'] as $i => $m ) : ?>
				<div class="ztr-stat" data-ztr-reveal style="--d:<?php echo (int) $i; ?>">
					<dt class="ztr-stat__label"><?php echo esc_html( $m['label'] ); ?></dt>
					<dd class="ztr-stat__value"><span data-ztr-count="<?php echo (int) $m['value']; ?>"><?php echo (int) $m['value']; ?></span><?php if ( $m['suffix'] ) : ?><span class="ztr-stat__suffix"><?php echo esc_html( $m['suffix'] ); ?></span><?php endif; ?></dd>
				</div>
			<?php endforeach; ?>
		</dl>

		<ul class="ztr-diff">
			<?php foreach ( $c['differentiators'] as $i => $d ) : ?>
				<li class="ztr-diff__item" data-ztr-reveal style="--d:<?php echo (int) $i; ?>">
					<span class="ztr-diff__icon"><?php echo ztr_icon( $d['icon'] ); // phpcs:ignore ?></span>
					<h3 class="ztr-h4"><?php echo esc_html( $d['title'] ); ?></h3>
					<p><?php echo esc_html( $d['text'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

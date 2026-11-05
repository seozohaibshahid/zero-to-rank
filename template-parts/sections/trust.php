<?php
/**
 * 03 — Ticker: the platforms we build visibility on, as a full-width blue
 * band. It drifts on its own and speeds up / reverses with scroll
 * (animations.js); pauses on hover. Deliberately no client logos.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$c = ztr_content( 'trust' );
?>
<section class="ztr-ticker" aria-label="<?php echo esc_attr( $c['label'] ); ?>" data-ztr-section="ticker">
	<p class="ztr-sr"><?php echo esc_html( $c['label'] ); ?>: <?php echo esc_html( implode( ', ', $c['platforms'] ) ); ?>.</p>
	<div class="ztr-ticker__viewport" aria-hidden="true" data-ztr-marquee>
		<div class="ztr-ticker__track" data-ztr-ticker>
			<?php for ( $n = 0; $n < 2; $n++ ) : ?>
				<ul class="ztr-ticker__set">
					<?php foreach ( $c['platforms'] as $p ) : ?>
						<li><?php echo esc_html( $p ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endfor; ?>
		</div>
	</div>
</section>

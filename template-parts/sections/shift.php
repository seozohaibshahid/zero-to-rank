<?php
/**
 * 05 — The visibility shift: a plain side-by-side comparison of how buyers
 * search the traditional way and with AI, and what earns visibility in each.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$c = ztr_content( 'shift' );
?>
<section class="ztr-section ztr-shift" id="shift" aria-labelledby="ztr-shift-title" data-ztr-section="shift">
	<div class="ztr-container">
		<header class="ztr-head ztr-head--center">
			<p class="ztr-eyebrow" data-ztr-reveal><span>05</span><?php echo esc_html( $c['eyebrow'] ); ?></p>
			<h2 class="ztr-h2" id="ztr-shift-title" data-ztr-reveal><?php echo ztr_kses_inline( $c['title'] ); // phpcs:ignore ?></h2>
			<p class="ztr-lead" data-ztr-reveal><?php echo esc_html( $c['intro'] ); ?></p>
		</header>

		<div class="ztr-compare">
			<?php foreach ( $c['modes'] as $key => $mode ) : ?>
				<article class="ztr-compare__col ztr-compare__col--<?php echo esc_attr( $key ); ?>" data-ztr-reveal>
					<h3 class="ztr-compare__title"><?php echo esc_html( $mode['label'] ); ?></h3>
					<p class="ztr-compare__query">“<?php echo esc_html( $mode['query'] ); ?>”</p>

					<p class="ztr-compare__label"><?php esc_html_e( 'How the buyer searches', 'zero-to-rank' ); ?></p>
					<ol class="ztr-compare__steps">
						<?php foreach ( $mode['steps'] as $step ) : ?>
							<li><?php echo esc_html( $step ); ?></li>
						<?php endforeach; ?>
					</ol>

					<p class="ztr-compare__label"><?php esc_html_e( 'What earns visibility', 'zero-to-rank' ); ?></p>
					<ul class="ztr-compare__wins">
						<?php foreach ( $mode['wins'] as $win ) : ?>
							<li><?php echo ztr_icon( 'check' ); // phpcs:ignore ?><span><?php echo esc_html( $win ); ?></span></li>
						<?php endforeach; ?>
					</ul>

					<p class="ztr-compare__summary"><?php echo esc_html( $mode['summary'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

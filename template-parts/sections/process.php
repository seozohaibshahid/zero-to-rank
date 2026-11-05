<?php
/**
 * 07 — Process: five step cards on a dark band. A progress line above them
 * fills as the section scrolls past (animations.js).
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$c      = ztr_args( 'process', isset( $args ) ? $args : array() );
$anchor = ! empty( $c['anchor'] ) ? $c['anchor'] : 'process';
?>
<section class="ztr-section ztr-process ztr-dark" id="<?php echo esc_attr( $anchor ); ?>" aria-labelledby="ztr-process-title" data-ztr-section="process">
	<div class="ztr-container">
		<header class="ztr-head">
			<p class="ztr-eyebrow" data-ztr-reveal><span></span><?php echo esc_html( $c['eyebrow'] ); ?></p>
			<h2 class="ztr-h2" id="ztr-process-title" data-ztr-words><?php echo ztr_kses_inline( $c['title'] ); // phpcs:ignore ?></h2>
			<p class="ztr-lead" data-ztr-reveal><?php echo esc_html( $c['intro'] ); ?></p>
		</header>

		<div class="ztr-steps__track" aria-hidden="true"><span class="ztr-steps__line" data-ztr-steps-line></span></div>
		<ol class="ztr-steps">
			<?php foreach ( array_values( (array) $c['steps'] ) as $i => $step ) : ?>
				<li class="ztr-step" data-ztr-reveal="card" style="--d:<?php echo (int) $i; ?>">
					<p class="ztr-step__n"><?php echo esc_html( sprintf( /* translators: %02d: step number */ __( 'Step %02d', 'zero-to-rank' ), $i + 1 ) ); ?></p>
					<h3 class="ztr-step__title"><?php echo esc_html( $step['title'] ); ?></h3>
					<p class="ztr-step__text"><?php echo esc_html( $step['text'] ); ?></p>
					<p class="ztr-step__out"><?php echo esc_html( $step['output'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ol>

		<p class="ztr-process__cta" data-ztr-reveal>
			<a class="ztr-btn ztr-btn--glow ztr-btn--lg" href="<?php echo esc_url( ztr_link( ztr_audit_url() ) ); ?>" data-ztr-magnetic <?php echo ztr_track_attrs( 'cta_click', 'process', 'Start with step 1' ); // phpcs:ignore ?>>
				<span><?php esc_html_e( 'Start with step 1 — it’s free', 'zero-to-rank' ); ?></span>
				<span class="ztr-btn__arr" aria-hidden="true"><?php echo ztr_icon( 'arrow-right' ); // phpcs:ignore ?></span>
			</a>
		</p>
	</div>
</section>

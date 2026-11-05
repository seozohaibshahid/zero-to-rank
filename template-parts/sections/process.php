<?php
/**
 * 08 — Process: five steps with a scroll-linked progress line.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$c = ztr_content( 'process' );
?>
<section class="ztr-section ztr-process ztr-dark" id="process" aria-labelledby="ztr-process-title" data-ztr-section="process">
	<div class="ztr-container ztr-process__layout">
		<header class="ztr-process__head">
			<p class="ztr-eyebrow" data-ztr-reveal><span>08</span><?php echo esc_html( $c['eyebrow'] ); ?></p>
			<h2 class="ztr-h2" id="ztr-process-title" data-ztr-reveal><?php echo ztr_kses_inline( $c['title'] ); // phpcs:ignore ?></h2>
			<p class="ztr-lead" data-ztr-reveal><?php echo esc_html( $c['intro'] ); ?></p>
			<a class="ztr-btn ztr-btn--lime" href="<?php echo esc_url( ztr_audit_url() ); ?>" data-ztr-reveal <?php echo ztr_track_attrs( 'cta_click', 'process', 'Start with step 01' ); // phpcs:ignore ?>>
				<span><?php esc_html_e( 'Start with step 01 — free', 'zero-to-rank' ); ?></span>
				<?php echo ztr_icon( 'arrow-up-right' ); // phpcs:ignore ?>
			</a>
		</header>

		<ol class="ztr-timeline" data-ztr-timeline>
			<li class="ztr-timeline__rail" aria-hidden="true"><span class="ztr-timeline__fill" data-ztr-timeline-fill></span></li>
			<?php foreach ( $c['steps'] as $i => $step ) : ?>
				<li class="ztr-timeline__step" data-ztr-step>
					<span class="ztr-timeline__dot" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
					<div class="ztr-timeline__card">
						<h3 class="ztr-h3"><span class="ztr-sr"><?php echo esc_html( sprintf( /* translators: %d: step number */ __( 'Step %d:', 'zero-to-rank' ), $i + 1 ) ); ?> </span><?php echo esc_html( $step['title'] ); ?></h3>
						<p><?php echo esc_html( $step['text'] ); ?></p>
						<p class="ztr-timeline__output ztr-mono"><?php echo ztr_icon( 'arrow-right' ); // phpcs:ignore ?><?php echo esc_html( $step['output'] ); ?></p>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>

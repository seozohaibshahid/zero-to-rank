<?php
/**
 * 08 — Process: five steps as a simple card row.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$c = ztr_content( 'process' );
?>
<section class="ztr-section ztr-process ztr-band" id="process" aria-labelledby="ztr-process-title" data-ztr-section="process">
	<div class="ztr-container">
		<header class="ztr-head ztr-head--center">
			<p class="ztr-eyebrow" data-ztr-reveal><span>08</span><?php echo esc_html( $c['eyebrow'] ); ?></p>
			<h2 class="ztr-h2" id="ztr-process-title" data-ztr-reveal><?php echo ztr_kses_inline( $c['title'] ); // phpcs:ignore ?></h2>
			<p class="ztr-lead" data-ztr-reveal><?php echo esc_html( $c['intro'] ); ?></p>
		</header>

		<ol class="ztr-steps">
			<?php foreach ( $c['steps'] as $i => $step ) : ?>
				<li class="ztr-step" data-ztr-reveal style="--d:<?php echo (int) $i; ?>">
					<p class="ztr-step__n"><?php echo esc_html( sprintf( /* translators: %d: step number */ __( 'Step %d', 'zero-to-rank' ), $i + 1 ) ); ?></p>
					<h3 class="ztr-step__title"><?php echo esc_html( $step['title'] ); ?></h3>
					<p class="ztr-step__text"><?php echo esc_html( $step['text'] ); ?></p>
					<p class="ztr-step__out"><?php echo esc_html( $step['output'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ol>

		<p class="ztr-center" data-ztr-reveal>
			<a class="ztr-btn ztr-btn--lime" href="<?php echo esc_url( ztr_audit_url() ); ?>" <?php echo ztr_track_attrs( 'cta_click', 'process', 'Start with step 1' ); // phpcs:ignore ?>>
				<span><?php esc_html_e( 'Start with step 1 — it’s free', 'zero-to-rank' ); ?></span>
				<?php echo ztr_icon( 'arrow-up-right' ); // phpcs:ignore ?>
			</a>
		</p>
	</div>
</section>

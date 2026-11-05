<?php
/**
 * Statement: a large point-of-view line between the trust bar and results.
 *
 * With JS and motion allowed, animations.js splits the text into words and
 * fills them in as the reader scrolls. Without JS (or with reduced motion)
 * it is simply a large, fully legible paragraph.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$c = ztr_content( 'statement' );
if ( empty( $c['text'] ) ) {
	return;
}
?>
<section class="ztr-statement" aria-labelledby="ztr-statement-label" data-ztr-section="statement">
	<div class="ztr-container ztr-statement__inner">
		<p class="ztr-statement__eyebrow ztr-mono" id="ztr-statement-label">
			<span class="ztr-statement__tick" aria-hidden="true"></span>
			<?php echo esc_html( $c['eyebrow'] ); ?>
		</p>
		<p class="ztr-statement__text" data-ztr-scrub-text><?php echo ztr_kses_inline( $c['text'] ); // phpcs:ignore ?></p>
	</div>
</section>

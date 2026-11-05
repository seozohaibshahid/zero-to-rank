<?php
/**
 * Optional: content written in the WordPress editor for this page.
 * Renders only when the page has content, so the template stays editable.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

if ( '' === trim( (string) get_the_content() ) ) {
	return;
}
?>
<section class="ztr-section ztr-editor" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
	<div class="ztr-container ztr-container--narrow ztr-prose">
		<?php the_content(); ?>
	</div>
</section>

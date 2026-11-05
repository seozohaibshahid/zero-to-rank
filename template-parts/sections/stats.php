<?php
/**
 * Results strip: four real client figures (from the hero proof list) that
 * count up on view, each linking to its case study, plus the working
 * principles underneath.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$hero  = ztr_content( 'hero' );
$trust = ztr_content( 'trust' );
$proof = ! empty( $hero['proof'] ) ? array_slice( $hero['proof'], 0, 4 ) : array();
if ( ! $proof ) {
	return;
}
?>
<section class="ztr-section ztr-stats" aria-label="<?php esc_attr_e( 'Client results', 'zero-to-rank' ); ?>" data-ztr-section="stats">
	<div class="ztr-container">
		<ul class="ztr-nums" style="--n:<?php echo count( $proof ); ?>">
			<?php foreach ( $proof as $i => $p ) : ?>
				<li class="ztr-num" data-ztr-reveal style="--d:<?php echo (int) $i; ?>">
					<a href="<?php echo esc_url( $p['href'] ); ?>" <?php echo ztr_track_attrs( 'cta_click', 'stats', $p['who'] ); // phpcs:ignore ?>>
						<b class="ztr-num__value" data-ztr-countup><?php echo esc_html( $p['value'] ); ?></b>
						<span class="ztr-num__label"><?php echo esc_html( $p['label'] ); ?></span>
						<span class="ztr-num__who"><?php echo esc_html( $p['who'] ); ?> <?php echo ztr_icon( 'arrow-right' ); // phpcs:ignore ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>

		<?php if ( ! empty( $trust['principles'] ) ) : ?>
			<ul class="ztr-principles">
				<?php foreach ( $trust['principles'] as $i => $p ) : ?>
					<li data-ztr-reveal style="--d:<?php echo (int) $i; ?>">
						<?php echo ztr_icon( $p['icon'] ); // phpcs:ignore ?>
						<span><?php echo esc_html( $p['text'] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</section>

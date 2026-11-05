<?php
/**
 * 09 — Who we help: industry tabs with tailored challenges and approach.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$c     = ztr_content( 'industries' );
$names = array(
	'seo' => 'SEO',
	'aeo' => 'AEO',
	'geo' => 'GEO',
);
?>
<section class="ztr-section ztr-industries" id="industries" aria-labelledby="ztr-industries-title" data-ztr-section="industries">
	<div class="ztr-container">
		<header class="ztr-head ztr-head--center">
			<p class="ztr-eyebrow" data-ztr-reveal><span>09</span><?php echo esc_html( $c['eyebrow'] ); ?></p>
			<h2 class="ztr-h2" id="ztr-industries-title" data-ztr-reveal><?php echo ztr_kses_inline( $c['title'] ); // phpcs:ignore ?></h2>
		</header>

		<div class="ztr-ind" data-ztr-tabs="industries" data-ztr-reveal>
			<div class="ztr-ind__tabs" role="tablist" aria-label="<?php esc_attr_e( 'Industries', 'zero-to-rank' ); ?>">
				<?php foreach ( $c['items'] as $i => $item ) : ?>
					<button class="ztr-ind__tab<?php echo 0 === $i ? ' is-active' : ''; ?>" type="button" role="tab" id="ztr-ind-tab-<?php echo esc_attr( $item['key'] ); ?>" aria-controls="ztr-ind-panel-<?php echo esc_attr( $item['key'] ); ?>" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>" tabindex="<?php echo 0 === $i ? '0' : '-1'; ?>" data-ztr-tab>
						<?php echo esc_html( $item['name'] ); ?>
					</button>
				<?php endforeach; ?>
			</div>

			<?php foreach ( $c['items'] as $i => $item ) : ?>
				<div class="ztr-ind__panel" id="ztr-ind-panel-<?php echo esc_attr( $item['key'] ); ?>" role="tabpanel" aria-labelledby="ztr-ind-tab-<?php echo esc_attr( $item['key'] ); ?>" tabindex="0" data-ztr-panel<?php echo 0 !== $i ? ' hidden' : ''; ?>>
					<div class="ztr-ind__challenge">
						<p class="ztr-mono"><?php esc_html_e( 'The challenge', 'zero-to-rank' ); ?></p>
						<h3 class="ztr-h3"><?php echo esc_html( $item['challenge'] ); ?></h3>
						<div class="ztr-ind__channels">
							<span class="ztr-mono"><?php esc_html_e( 'Priority channels', 'zero-to-rank' ); ?></span>
							<?php foreach ( $names as $key => $label ) : ?>
								<span class="ztr-chip<?php echo in_array( $key, $item['channels'], true ) ? ' ztr-chip--lime' : ' ztr-chip--muted'; ?>"><?php echo esc_html( $label ); ?></span>
							<?php endforeach; ?>
						</div>
					</div>
					<div class="ztr-ind__approach">
						<p class="ztr-mono"><?php esc_html_e( 'How we approach it', 'zero-to-rank' ); ?></p>
						<ol>
							<?php foreach ( $item['approach'] as $j => $a ) : ?>
								<li style="--i:<?php echo (int) $j; ?>"><span class="ztr-ind__n"><?php echo (int) $j + 1; ?></span><?php echo esc_html( $a ); ?></li>
							<?php endforeach; ?>
						</ol>
						<a class="ztr-link" href="<?php echo esc_url( ztr_audit_url() ); ?>" <?php echo ztr_track_attrs( 'cta_click', 'industry_' . $item['key'], 'Industry audit' ); // phpcs:ignore ?>><?php esc_html_e( 'Get an audit for your business', 'zero-to-rank' ); ?><?php echo ztr_icon( 'arrow-right' ); // phpcs:ignore ?></a>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
/**
 * 04 — Case studies: real client results with the source reports as evidence.
 *
 * One tab per case. Each panel shows the headline metrics, the AI-assistant
 * sources (when GA4 data is available) and the original report screenshots,
 * which open full size in a new tab.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$c     = ztr_content( 'results' );
$cases = isset( $c['cases'] ) ? (array) $c['cases'] : array();
if ( ! $cases ) {
	return;
}

/**
 * Resolve an evidence image path (theme-relative or absolute URL).
 *
 * @param string $src Path or URL.
 * @return string
 */
$ztr_img = static function ( $src ) {
	return preg_match( '#^https?://#', $src ) ? $src : ztr_asset( $src );
};
?>
<section class="ztr-section ztr-results ztr-dark" id="results" aria-labelledby="ztr-results-title" data-ztr-section="results">
	<div class="ztr-container">
		<header class="ztr-head ztr-head--split">
			<div>
				<p class="ztr-eyebrow" data-ztr-reveal><span>05</span><?php echo esc_html( $c['eyebrow'] ); ?></p>
				<h2 class="ztr-h2" id="ztr-results-title" data-ztr-words><?php echo ztr_kses_inline( $c['title'] ); // phpcs:ignore ?></h2>
			</div>
			<p class="ztr-lead" data-ztr-reveal><?php echo esc_html( $c['intro'] ); ?></p>
		</header>

		<div class="ztr-cases" data-ztr-tabs="case_studies" data-ztr-reveal>
			<div class="ztr-cases__tabs" role="tablist" aria-label="<?php esc_attr_e( 'Case studies', 'zero-to-rank' ); ?>">
				<?php foreach ( $cases as $i => $case ) : ?>
					<button class="ztr-cases__tab<?php echo 0 === $i ? ' is-active' : ''; ?>" type="button" role="tab" id="ztr-case-tab-<?php echo esc_attr( $case['key'] ); ?>" aria-controls="ztr-case-<?php echo esc_attr( $case['key'] ); ?>" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>" tabindex="<?php echo 0 === $i ? '0' : '-1'; ?>" data-ztr-tab>
						<span class="ztr-cases__tab-n ztr-mono"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
						<?php echo esc_html( $case['tab'] ); ?>
					</button>
				<?php endforeach; ?>
			</div>

			<?php foreach ( $cases as $i => $case ) : ?>
				<article class="ztr-case" id="ztr-case-<?php echo esc_attr( $case['key'] ); ?>" role="tabpanel" aria-labelledby="ztr-case-tab-<?php echo esc_attr( $case['key'] ); ?>" tabindex="0" data-ztr-panel<?php echo 0 !== $i ? ' hidden' : ''; ?>>
					<div class="ztr-case__summary">
						<p class="ztr-case__meta ztr-mono">
							<span><?php echo esc_html( $case['sector'] ); ?></span>
							<span><?php echo esc_html( $case['period'] ); ?></span>
						</p>
						<h3 class="ztr-case__headline"><?php echo esc_html( $case['headline'] ); ?></h3>

						<dl class="ztr-case__metrics">
							<?php foreach ( (array) $case['metrics'] as $m ) : ?>
								<div class="ztr-case__metric">
									<dt><?php echo esc_html( $m['label'] ); ?></dt>
									<dd>
										<span class="ztr-case__value"><?php echo esc_html( $m['value'] ); ?></span>
										<?php if ( ! empty( $m['detail'] ) ) : ?>
											<span class="ztr-case__detail"><?php echo esc_html( $m['detail'] ); ?></span>
										<?php endif; ?>
									</dd>
								</div>
							<?php endforeach; ?>
						</dl>

						<?php if ( ! empty( $case['sources'] ) ) : ?>
							<?php $max = max( wp_list_pluck( $case['sources'], 'value' ) ); ?>
							<div class="ztr-case__sources">
								<p class="ztr-mono"><?php esc_html_e( 'AI-assistant sessions by source (GA4)', 'zero-to-rank' ); ?></p>
								<ul>
									<?php foreach ( $case['sources'] as $src ) : ?>
										<li style="--w:<?php echo esc_attr( round( $src['value'] / max( 1, $max ) * 100, 1 ) ); ?>%">
											<span class="ztr-case__src-name"><?php echo esc_html( $src['name'] ); ?></span>
											<span class="ztr-case__src-bar" aria-hidden="true"><i></i></span>
											<span class="ztr-case__src-val"><?php echo esc_html( number_format_i18n( $src['value'] ) ); ?></span>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $case['footnote'] ) ) : ?>
							<p class="ztr-case__footnote"><?php echo esc_html( $case['footnote'] ); ?></p>
						<?php endif; ?>
					</div>

					<div class="ztr-case__evidence">
						<p class="ztr-mono ztr-case__evidence-label"><?php esc_html_e( 'Evidence', 'zero-to-rank' ); ?></p>
						<?php foreach ( (array) $case['evidence'] as $j => $ev ) : ?>
							<figure class="ztr-proof<?php echo 0 === $j ? ' ztr-proof--main' : ''; ?>">
								<a href="<?php echo esc_url( $ztr_img( $ev['src'] ) ); ?>" target="_blank" rel="noopener" <?php echo ztr_track_attrs( 'evidence_open', 'case_' . $case['key'], $ev['caption'] ); // phpcs:ignore ?>>
									<img src="<?php echo esc_url( $ztr_img( $ev['src'] ) ); ?>" width="<?php echo (int) $ev['w']; ?>" height="<?php echo (int) $ev['h']; ?>" loading="lazy" decoding="async" alt="<?php echo esc_attr( $ev['caption'] ); ?>">
									<span class="ztr-sr"><?php esc_html_e( '(opens full size in a new tab)', 'zero-to-rank' ); ?></span>
								</a>
								<figcaption><?php echo esc_html( $ev['caption'] ); ?></figcaption>
							</figure>
						<?php endforeach; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>

		<p class="ztr-note" data-ztr-reveal><?php echo esc_html( $c['note'] ); ?></p>
	</div>
</section>

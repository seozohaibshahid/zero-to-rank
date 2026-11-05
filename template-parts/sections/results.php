<?php
/**
 * 04 — Results: interactive reporting cards (what we measure) and, when
 * supplied, real client-approved case studies.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$c       = ztr_content( 'results' );
$metrics = $c['metrics'];
?>
<section class="ztr-section ztr-results" id="results" aria-labelledby="ztr-results-title" data-ztr-section="results">
	<div class="ztr-container">
		<header class="ztr-head ztr-head--split">
			<div>
				<p class="ztr-eyebrow" data-ztr-reveal><span>04</span><?php echo esc_html( $c['eyebrow'] ); ?></p>
				<h2 class="ztr-h2" id="ztr-results-title" data-ztr-reveal><?php echo ztr_kses_inline( $c['title'] ); // phpcs:ignore ?></h2>
			</div>
			<p class="ztr-lead" data-ztr-reveal><?php echo esc_html( $c['intro'] ); ?></p>
		</header>

		<?php if ( ! empty( $c['case_studies'] ) ) : ?>
			<div class="ztr-cases">
				<?php foreach ( $c['case_studies'] as $i => $case ) : ?>
					<article class="ztr-case" data-ztr-reveal style="--d:<?php echo (int) $i; ?>">
						<p class="ztr-mono ztr-case__sector"><?php echo esc_html( $case['sector'] ); ?></p>
						<h3 class="ztr-h4"><?php echo esc_html( $case['client'] ); ?></h3>
						<p><?php echo esc_html( $case['summary'] ); ?></p>
						<dl class="ztr-case__metrics">
							<?php foreach ( (array) $case['metrics'] as $m ) : ?>
								<div><dt><?php echo esc_html( $m['label'] ); ?></dt><dd><?php echo esc_html( $m['value'] ); ?></dd></div>
							<?php endforeach; ?>
						</dl>
						<?php if ( ! empty( $case['url'] ) ) : ?>
							<a class="ztr-link" href="<?php echo esc_url( $case['url'] ); ?>"><?php esc_html_e( 'Read the case study', 'zero-to-rank' ); ?><?php echo ztr_icon( 'arrow-right' ); // phpcs:ignore ?></a>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<div class="ztr-dash" data-ztr-dash data-ztr-reveal>
			<div class="ztr-dash__list" role="tablist" aria-label="<?php esc_attr_e( 'Reporting metrics', 'zero-to-rank' ); ?>" aria-orientation="vertical">
				<?php foreach ( $metrics as $i => $m ) : ?>
					<button class="ztr-dash__item<?php echo 0 === $i ? ' is-active' : ''; ?>" type="button" role="tab"
						id="ztr-dash-tab-<?php echo esc_attr( $m['key'] ); ?>"
						aria-controls="ztr-dash-panel"
						aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"
						tabindex="<?php echo 0 === $i ? '0' : '-1'; ?>"
						data-ztr-dash-tab="<?php echo esc_attr( $m['key'] ); ?>">
						<span class="ztr-dash__chan ztr-mono"><?php echo esc_html( $m['channel'] ); ?></span>
						<span class="ztr-dash__title"><?php echo esc_html( $m['title'] ); ?></span>
						<span class="ztr-dash__desc"><span><?php echo esc_html( $m['desc'] ); ?></span></span>
					</button>
				<?php endforeach; ?>
			</div>

			<div class="ztr-dash__panel" id="ztr-dash-panel" role="tabpanel" aria-labelledby="ztr-dash-tab-<?php echo esc_attr( $metrics[0]['key'] ); ?>" tabindex="0">
				<div class="ztr-dash__panel-head">
					<div>
						<p class="ztr-mono ztr-dash__panel-label"><?php esc_html_e( 'Reporting view', 'zero-to-rank' ); ?></p>
						<p class="ztr-dash__panel-title" data-ztr-dash-title><?php echo esc_html( $metrics[0]['title'] ); ?></p>
					</div>
					<span class="ztr-chip ztr-chip--outline"><?php esc_html_e( 'Illustrative', 'zero-to-rank' ); ?></span>
				</div>

				<figure class="ztr-chart" aria-hidden="true">
					<svg viewBox="0 0 500 170" class="ztr-chart__svg">
						<defs>
							<linearGradient id="ztr-chart-fill" x1="0" y1="0" x2="0" y2="1">
								<stop offset="0" stop-color="#B9F12F" stop-opacity=".35"/>
								<stop offset="1" stop-color="#B9F12F" stop-opacity="0"/>
							</linearGradient>
						</defs>
						<g class="ztr-chart__grid">
							<line x1="0" y1="40" x2="500" y2="40"/><line x1="0" y1="85" x2="500" y2="85"/><line x1="0" y1="130" x2="500" y2="130"/>
						</g>
						<path class="ztr-chart__area" d="<?php echo esc_attr( $metrics[0]['curve'] ); ?> L500,170 L0,170 Z" data-ztr-chart-area/>
						<path class="ztr-chart__line" d="<?php echo esc_attr( $metrics[0]['curve'] ); ?>" pathLength="1" data-ztr-chart-line/>
					</svg>
					<div class="ztr-chart__axis ztr-mono"><span><?php esc_html_e( 'Baseline', 'zero-to-rank' ); ?></span><span><?php esc_html_e( 'Month 3', 'zero-to-rank' ); ?></span><span><?php esc_html_e( 'Month 6', 'zero-to-rank' ); ?></span><span><?php esc_html_e( 'Month 12', 'zero-to-rank' ); ?></span></div>
				</figure>

				<div class="ztr-dash__signals">
					<p class="ztr-mono"><?php esc_html_e( 'What we report', 'zero-to-rank' ); ?></p>
					<ul data-ztr-dash-signals>
						<?php foreach ( $metrics[0]['signals'] as $s ) : ?>
							<li><?php echo ztr_icon( 'check' ); // phpcs:ignore ?><span><?php echo esc_html( $s ); ?></span></li>
						<?php endforeach; ?>
					</ul>
				</div>

				<?php /* Data for the other tabs, read by interactions.js. */ ?>
				<script type="application/json" data-ztr-dash-data><?php echo wp_json_encode( $metrics, JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE ); ?></script>
			</div>
		</div>
		<p class="ztr-note" data-ztr-reveal><?php echo esc_html( $c['note'] ); ?></p>
	</div>
</section>

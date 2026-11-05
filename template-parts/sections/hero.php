<?php
/**
 * 02 — Hero: headline, CTAs and the animated SEO / AEO / GEO visibility visual.
 *
 * The visual is real, accessible markup (tabs + tab panels) that shows the
 * same business winning in each channel: ranking, being the answer, being cited.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$c        = ztr_content( 'hero' );
$channels = $c['channels'];
$brand    = 'yourbusiness.com';
?>
<section class="ztr-hero ztr-dark" aria-labelledby="ztr-hero-title" data-ztr-section="hero">
	<div class="ztr-hero__bg" aria-hidden="true">
		<div class="ztr-hero__grid-lines"></div>
		<div class="ztr-hero__glow ztr-hero__glow--a" data-ztr-parallax="-0.15"></div>
		<div class="ztr-hero__glow ztr-hero__glow--b" data-ztr-parallax="0.1"></div>
	</div>

	<div class="ztr-container ztr-hero__layout">
		<div class="ztr-hero__copy">
			<p class="ztr-pill ztr-hero__eyebrow" data-ztr-intro="0">
				<span class="ztr-pill__dot" aria-hidden="true"></span>
				<?php echo esc_html( $c['eyebrow'] ); ?>
			</p>

			<h1 class="ztr-hero__title" id="ztr-hero-title">
				<?php foreach ( $c['title_lines'] as $i => $line ) : ?>
					<span class="ztr-hero__line ztr-hero__line--<?php echo (int) $i + 1; ?>"><span class="ztr-hero__line-inner" data-ztr-intro="<?php echo (int) $i + 1; ?>"><?php echo ztr_kses_inline( $line ); // phpcs:ignore ?></span></span>
				<?php endforeach; ?>
			</h1>

			<p class="ztr-hero__lead" data-ztr-intro="3">
				<strong><?php echo esc_html( $c['lead_strong'] ); ?></strong>
				<?php echo esc_html( $c['lead'] ); ?>
			</p>

			<div class="ztr-hero__ctas" data-ztr-intro="4">
				<a class="ztr-btn ztr-btn--lime ztr-btn--lg" href="<?php echo esc_url( ztr_audit_url() ); ?>" <?php echo ztr_track_attrs( 'cta_click', 'hero_primary', $c['primary_cta'] ); // phpcs:ignore ?>>
					<span><?php echo esc_html( $c['primary_cta'] ); ?></span>
					<?php echo ztr_icon( 'arrow-up-right' ); // phpcs:ignore ?>
				</a>
				<a class="ztr-btn ztr-btn--ghost ztr-btn--lg" href="#services" <?php echo ztr_track_attrs( 'cta_click', 'hero_secondary', $c['secondary_cta'] ); // phpcs:ignore ?>>
					<span><?php echo esc_html( $c['secondary_cta'] ); ?></span>
					<?php echo ztr_icon( 'arrow-down' ); // phpcs:ignore ?>
				</a>
			</div>
		</div>

		<div class="ztr-hero__visual" data-ztr-intro="2">
			<div class="ztr-eco" data-ztr-eco>
				<div class="ztr-eco__head">
					<span class="ztr-mono"><?php echo esc_html( $c['panel_label'] ); ?></span>
					<span class="ztr-eco__live" aria-hidden="true"><i></i><?php esc_html_e( 'Live view', 'zero-to-rank' ); ?></span>
				</div>

				<div class="ztr-eco__query" aria-hidden="true">
					<?php echo ztr_icon( 'search' ); // phpcs:ignore ?>
					<span class="ztr-eco__query-text" data-ztr-eco-query><?php echo esc_html( $channels[0]['query'] ); ?></span><span class="ztr-eco__caret"></span>
				</div>

				<div class="ztr-eco__stage">
					<?php /* SEO — ranking in organic results. */ ?>
					<div class="ztr-eco__panel is-active" id="ztr-eco-panel-seo" role="tabpanel" aria-labelledby="ztr-eco-tab-seo" data-ztr-eco-panel="seo" data-query="<?php echo esc_attr( $channels[0]['query'] ); ?>">
						<p class="ztr-sr"><?php esc_html_e( 'Illustration: your website moving to the top of the organic search results.', 'zero-to-rank' ); ?></p>
						<ol class="ztr-serp" aria-hidden="true" data-ztr-serp>
							<?php
							$rows = array( 'competitor-a.com', 'directory-site.com', 'competitor-b.co', $brand, 'review-hub.net' );
							foreach ( $rows as $i => $domain ) :
								$is_you = $domain === $brand;
								?>
								<li class="ztr-serp__row<?php echo $is_you ? ' is-you' : ''; ?>" style="--i:<?php echo (int) $i; ?>" data-start="<?php echo (int) $i; ?>">
									<span class="ztr-serp__fav"></span>
									<span class="ztr-serp__text">
										<span class="ztr-serp__domain"><?php echo esc_html( $domain ); ?></span>
										<span class="ztr-serp__bar"></span>
									</span>
									<?php if ( $is_you ) : ?>
										<span class="ztr-serp__rank" data-ztr-rank>#4</span>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ol>
					</div>

					<?php /* AEO — chosen as the direct answer. */ ?>
					<div class="ztr-eco__panel" id="ztr-eco-panel-aeo" role="tabpanel" aria-labelledby="ztr-eco-tab-aeo" data-ztr-eco-panel="aeo" data-query="<?php echo esc_attr( $channels[1]['query'] ); ?>">
						<p class="ztr-sr"><?php esc_html_e( 'Illustration: your content shown as the featured answer, with your site as the source.', 'zero-to-rank' ); ?></p>
						<div class="ztr-answer" aria-hidden="true">
							<span class="ztr-chip ztr-chip--lime"><?php echo ztr_icon( 'spark' ); // phpcs:ignore ?><?php esc_html_e( 'Featured answer', 'zero-to-rank' ); ?></span>
							<p class="ztr-answer__text"><?php esc_html_e( 'Most offices benefit from a deep clean every 3–6 months, with high-traffic areas reviewed monthly…', 'zero-to-rank' ); ?></p>
							<span class="ztr-answer__source"><span class="ztr-serp__fav"></span><?php echo esc_html( $brand ); ?></span>
						</div>
						<ul class="ztr-paa" aria-hidden="true">
							<li><?php esc_html_e( 'What does a deep clean include?', 'zero-to-rank' ); ?><?php echo ztr_icon( 'plus' ); // phpcs:ignore ?></li>
							<li><?php esc_html_e( 'How long does an office deep clean take?', 'zero-to-rank' ); ?><?php echo ztr_icon( 'plus' ); // phpcs:ignore ?></li>
						</ul>
					</div>

					<?php /* GEO — recommended and cited by AI. */ ?>
					<div class="ztr-eco__panel" id="ztr-eco-panel-geo" role="tabpanel" aria-labelledby="ztr-eco-tab-geo" data-ztr-eco-panel="geo" data-query="<?php echo esc_attr( $channels[2]['query'] ); ?>">
						<p class="ztr-sr"><?php esc_html_e( 'Illustration: an AI assistant recommending your business and citing your website.', 'zero-to-rank' ); ?></p>
						<div class="ztr-ai" aria-hidden="true">
							<div class="ztr-ai__avatar"><?php echo ztr_icon( 'spark' ); // phpcs:ignore ?></div>
							<div class="ztr-ai__body">
								<span class="ztr-ai__line"><?php esc_html_e( 'Based on reviews and service detail, strong options include:', 'zero-to-rank' ); ?></span>
								<ol class="ztr-ai__list">
									<li class="is-you"><strong><?php esc_html_e( 'Your Business', 'zero-to-rank' ); ?></strong> <span class="ztr-ai__cite">1</span></li>
									<li><span class="ztr-ai__skeleton"></span> <span class="ztr-ai__cite">2</span></li>
									<li><span class="ztr-ai__skeleton ztr-ai__skeleton--short"></span> <span class="ztr-ai__cite">3</span></li>
								</ol>
								<div class="ztr-ai__sources">
									<span class="ztr-ai__source is-you"><span class="ztr-ai__cite">1</span><?php echo esc_html( $brand ); ?></span>
									<span class="ztr-ai__source"><span class="ztr-ai__cite">2</span>&hellip;</span>
								</div>
							</div>
						</div>
					</div>
				</div>

				<p class="ztr-eco__outcome" aria-live="polite">
					<?php echo ztr_icon( 'check' ); // phpcs:ignore ?>
					<span data-ztr-eco-outcome><?php echo esc_html( $channels[0]['outcome'] ); ?></span>
				</p>

				<div class="ztr-eco__tabs" role="tablist" aria-label="<?php esc_attr_e( 'Visibility channels', 'zero-to-rank' ); ?>">
					<?php foreach ( $channels as $i => $ch ) : ?>
						<button class="ztr-eco__tab<?php echo 0 === $i ? ' is-active' : ''; ?>" type="button" role="tab"
							id="ztr-eco-tab-<?php echo esc_attr( $ch['key'] ); ?>"
							aria-controls="ztr-eco-panel-<?php echo esc_attr( $ch['key'] ); ?>"
							aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"
							tabindex="<?php echo 0 === $i ? '0' : '-1'; ?>"
							data-ztr-eco-tab="<?php echo esc_attr( $ch['key'] ); ?>"
							data-outcome="<?php echo esc_attr( $ch['outcome'] ); ?>">
							<span class="ztr-eco__tab-abbr"><?php echo esc_html( $ch['abbr'] ); ?></span>
							<span class="ztr-eco__tab-label"><?php echo esc_html( $ch['label'] ); ?></span>
							<span class="ztr-eco__tab-progress" aria-hidden="true"><i></i></span>
						</button>
					<?php endforeach; ?>
				</div>
			</div>
			<p class="ztr-hero__tagline ztr-mono"><?php echo esc_html( $c['tagline'] ); ?></p>
		</div>
	</div>
</section>

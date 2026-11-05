<?php
/**
 * 04 — Case studies as stacked cards: each card sticks under the nav and the
 * next one slides up over it, while the covered card sinks back slightly
 * (animations.js). Each card shows the headline metrics, AI-assistant
 * sources (when GA4 data exists) and the original report screenshots as
 * evidence, which open full size. A CTA band follows the stack.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$c     = ztr_args( 'results', isset( $args ) ? $args : array() );
$cases = array_values( (array) $c['cases'] );
if ( ! $cases ) {
	return;
}
$anchor = ! empty( $c['anchor'] ) ? $c['anchor'] : 'results';
?>
<section class="ztr-section ztr-results ztr-dark" id="<?php echo esc_attr( $anchor ); ?>" aria-labelledby="ztr-results-title" data-ztr-section="results">
	<div class="ztr-container">
		<header class="ztr-head ztr-head--split">
			<div>
				<p class="ztr-eyebrow" data-ztr-reveal><span></span><?php echo esc_html( $c['eyebrow'] ); ?></p>
				<h2 class="ztr-h2" id="ztr-results-title" data-ztr-words><?php echo ztr_kses_inline( $c['title'] ); // phpcs:ignore ?></h2>
			</div>
			<p class="ztr-lead" data-ztr-reveal><?php echo esc_html( $c['intro'] ); ?></p>
		</header>

		<div class="ztr-stack" data-ztr-stack>
			<?php
			foreach ( $cases as $i => $case ) :
				$key      = ! empty( $case['key'] ) ? sanitize_title( $case['key'] ) : sanitize_title( isset( $case['tab'] ) ? $case['tab'] : 'case-' . ( $i + 1 ) );
				$metrics  = ztr_rows( isset( $case['metrics'] ) ? $case['metrics'] : array(), array( 'value', 'label', 'detail' ) );
				$sources  = ztr_rows( isset( $case['sources'] ) ? $case['sources'] : array(), array( 'name', 'value' ) );
				$evidence = array();
				for ( $n = 1; $n <= 3; $n++ ) {
					if ( ! empty( $case[ 'img' . $n ] ) ) {
						$img = ztr_image( $case[ 'img' . $n ] );
						if ( $img['url'] ) {
							$img['caption'] = isset( $case[ 'cap' . $n ] ) ? $case[ 'cap' . $n ] : '';
							$evidence[]     = $img;
						}
					}
				}
				$max = 1;
				foreach ( $sources as $src ) {
					$max = max( $max, (float) $src['value'] );
				}
				?>
				<article class="ztr-scard ztr-scard--<?php echo (int) ( $i % 3 ) + 1; ?>" id="ztr-case-<?php echo esc_attr( $key ); ?>" style="--i:<?php echo (int) $i; ?>" data-ztr-scard>
					<div class="ztr-scard__copy">
						<p class="ztr-scard__top">
							<span class="ztr-scard__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
							<span class="ztr-mono"><?php echo esc_html( isset( $case['sector'] ) ? $case['sector'] : '' ); ?></span>
						</p>
						<h3 class="ztr-scard__headline"><?php echo esc_html( isset( $case['headline'] ) ? $case['headline'] : '' ); ?></h3>
						<?php if ( ! empty( $case['period'] ) ) : ?>
							<p class="ztr-scard__period"><?php echo esc_html( $case['period'] ); ?></p>
						<?php endif; ?>

						<?php if ( $metrics ) : ?>
							<dl class="ztr-scard__metrics">
								<?php foreach ( $metrics as $m ) : ?>
									<div class="ztr-scard__metric">
										<dt><?php echo esc_html( $m['label'] ); ?></dt>
										<dd>
											<span class="ztr-scard__value"><?php echo esc_html( $m['value'] ); ?></span>
											<?php if ( '' !== $m['detail'] ) : ?>
												<span class="ztr-scard__detail"><?php echo esc_html( $m['detail'] ); ?></span>
											<?php endif; ?>
										</dd>
									</div>
								<?php endforeach; ?>
							</dl>
						<?php endif; ?>

						<?php if ( $sources ) : ?>
							<div class="ztr-scard__sources">
								<p class="ztr-mono"><?php esc_html_e( 'AI-assistant sessions by source (GA4)', 'zero-to-rank' ); ?></p>
								<ul>
									<?php foreach ( $sources as $src ) : ?>
										<li style="--w:<?php echo esc_attr( round( (float) $src['value'] / $max * 100, 1 ) ); ?>%">
											<span><?php echo esc_html( $src['name'] ); ?></span>
											<span class="ztr-scard__bar" aria-hidden="true"><i></i></span>
											<span class="ztr-scard__val"><?php echo esc_html( is_numeric( $src['value'] ) ? number_format_i18n( (float) $src['value'] ) : $src['value'] ); ?></span>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $case['footnote'] ) ) : ?>
							<p class="ztr-scard__footnote"><?php echo esc_html( $case['footnote'] ); ?></p>
						<?php endif; ?>
					</div>

					<?php if ( $evidence ) : ?>
						<div class="ztr-scard__vis">
							<?php foreach ( $evidence as $j => $ev ) : ?>
								<figure class="ztr-proof<?php echo 0 === $j ? ' ztr-proof--main' : ''; ?>">
									<a href="<?php echo esc_url( $ev['url'] ); ?>" target="_blank" rel="noopener" <?php echo ztr_track_attrs( 'evidence_open', 'case_' . $key, $ev['caption'] ); // phpcs:ignore ?>>
										<?php if ( 0 === $j ) : ?>
											<span class="ztr-proof__chrome" aria-hidden="true"><i></i><i></i><i></i></span>
										<?php endif; ?>
										<img src="<?php echo esc_url( $ev['url'] ); ?>"<?php echo $ev['width'] ? ' width="' . (int) $ev['width'] . '" height="' . (int) $ev['height'] . '"' : ''; ?> loading="lazy" decoding="async" alt="<?php echo esc_attr( $ev['caption'] ); ?>">
										<span class="ztr-sr"><?php esc_html_e( '(opens full size in a new tab)', 'zero-to-rank' ); ?></span>
									</a>
									<?php if ( $ev['caption'] ) : ?>
										<figcaption><?php echo esc_html( $ev['caption'] ); ?></figcaption>
									<?php endif; ?>
								</figure>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>

		<?php if ( ! empty( $c['cta_title'] ) ) : ?>
			<div class="ztr-cta-band" data-ztr-reveal>
				<div>
					<p class="ztr-cta-band__title"><?php echo esc_html( $c['cta_title'] ); ?></p>
					<?php if ( ! empty( $c['cta_text'] ) ) : ?>
						<p class="ztr-cta-band__text"><?php echo esc_html( $c['cta_text'] ); ?></p>
					<?php endif; ?>
				</div>
				<a class="ztr-btn ztr-btn--glow ztr-btn--lg" href="<?php echo esc_url( ztr_link( ztr_audit_url() ) ); ?>" data-ztr-magnetic <?php echo ztr_track_attrs( 'cta_click', 'results_band', $c['cta_button'] ); // phpcs:ignore ?>>
					<span><?php echo esc_html( $c['cta_button'] ); ?></span>
					<span class="ztr-btn__arr" aria-hidden="true"><?php echo ztr_icon( 'arrow-right' ); // phpcs:ignore ?></span>
				</a>
			</div>
		<?php endif; ?>

		<p class="ztr-note" data-ztr-reveal><?php echo esc_html( $c['note'] ); ?></p>
	</div>
</section>

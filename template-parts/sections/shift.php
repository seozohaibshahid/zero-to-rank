<?php
/**
 * 05 — The visibility shift: traditional search vs AI-driven discovery.
 * A two-state toggle; the buyer journey, the result surface and the
 * "what wins" list all change together so the difference is felt, not just read.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$c     = ztr_content( 'shift' );
$modes = $c['modes'];
?>
<section class="ztr-section ztr-shift ztr-dark" id="shift" aria-labelledby="ztr-shift-title" data-ztr-section="shift">
	<div class="ztr-container">
		<header class="ztr-head ztr-head--center">
			<p class="ztr-eyebrow" data-ztr-reveal><span>05</span><?php echo esc_html( $c['eyebrow'] ); ?></p>
			<h2 class="ztr-h2" id="ztr-shift-title" data-ztr-reveal><?php echo ztr_kses_inline( $c['title'] ); // phpcs:ignore ?></h2>
			<p class="ztr-lead" data-ztr-reveal><?php echo esc_html( $c['intro'] ); ?></p>
		</header>

		<div class="ztr-shift__ui" data-ztr-shift data-mode="traditional" data-ztr-reveal>
			<div class="ztr-switch" role="radiogroup" aria-label="<?php esc_attr_e( 'Compare how buyers search', 'zero-to-rank' ); ?>">
				<?php $first = true; foreach ( $modes as $key => $mode ) : ?>
					<button class="ztr-switch__opt<?php echo $first ? ' is-active' : ''; ?>" type="button" role="radio" aria-checked="<?php echo $first ? 'true' : 'false'; ?>" tabindex="<?php echo $first ? '0' : '-1'; ?>" data-ztr-shift-mode="<?php echo esc_attr( $key ); ?>">
						<?php echo esc_html( $mode['label'] ); ?>
					</button>
				<?php $first = false; endforeach; ?>
				<span class="ztr-switch__thumb" aria-hidden="true"></span>
			</div>

			<div class="ztr-shift__grid">
				<?php foreach ( $modes as $key => $mode ) : ?>
					<div class="ztr-shift__mode" data-ztr-shift-panel="<?php echo esc_attr( $key ); ?>"<?php echo 'traditional' !== $key ? ' hidden' : ''; ?>>
						<div class="ztr-shift__screen" aria-hidden="true">
							<div class="ztr-shift__search">
								<?php echo ztr_icon( 'traditional' === $key ? 'search' : 'chat' ); // phpcs:ignore ?>
								<span><?php echo esc_html( $mode['query'] ); ?></span>
							</div>
							<?php if ( 'traditional' === $key ) : ?>
								<ul class="ztr-shift__links">
									<?php for ( $i = 0; $i < 6; $i++ ) : ?>
										<li style="--i:<?php echo (int) $i; ?>"><i></i><span></span><span></span></li>
									<?php endfor; ?>
								</ul>
							<?php else : ?>
								<div class="ztr-shift__answer">
									<span class="ztr-shift__answer-line" style="--i:0"></span>
									<span class="ztr-shift__answer-line" style="--i:1"></span>
									<span class="ztr-shift__answer-line ztr-shift__answer-line--short" style="--i:2"></span>
									<div class="ztr-shift__cards">
										<span class="is-you" style="--i:3"><?php esc_html_e( 'Cited', 'zero-to-rank' ); ?></span>
										<span style="--i:4"></span>
										<span style="--i:5"></span>
									</div>
								</div>
							<?php endif; ?>
							<div class="ztr-shift__foot">
								<?php if ( 'traditional' === $key ) : ?>
									<span><?php esc_html_e( 'Page 1 of many', 'zero-to-rank' ); ?></span>
									<span class="ztr-shift__pages"><i></i><i></i><i></i><i></i></span>
								<?php else : ?>
									<span class="ztr-shift__followup"><?php esc_html_e( 'Ask a follow-up…', 'zero-to-rank' ); ?></span>
								<?php endif; ?>
							</div>
						</div>

						<div class="ztr-shift__info">
							<p class="ztr-mono ztr-shift__kicker"><?php esc_html_e( 'The buyer journey', 'zero-to-rank' ); ?></p>
							<ol class="ztr-shift__steps">
								<?php foreach ( $mode['steps'] as $i => $step ) : ?>
									<li style="--i:<?php echo (int) $i; ?>"><span class="ztr-shift__num"><?php echo (int) $i + 1; ?></span><?php echo esc_html( $step ); ?></li>
								<?php endforeach; ?>
							</ol>
							<p class="ztr-mono ztr-shift__kicker"><?php esc_html_e( 'What earns visibility', 'zero-to-rank' ); ?></p>
							<ul class="ztr-shift__wins">
								<?php foreach ( $mode['wins'] as $win ) : ?>
									<li><?php echo esc_html( $win ); ?></li>
								<?php endforeach; ?>
							</ul>
							<p class="ztr-shift__summary"><?php echo esc_html( $mode['summary'] ); ?></p>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<p class="ztr-shift__bridge" data-ztr-reveal>
			<?php esc_html_e( 'Winning now means doing both — and that is exactly how we plan.', 'zero-to-rank' ); ?>
			<a class="ztr-link ztr-link--lime" href="#services"><?php esc_html_e( 'See how', 'zero-to-rank' ); ?><?php echo ztr_icon( 'arrow-right' ); // phpcs:ignore ?></a>
		</p>
	</div>
</section>

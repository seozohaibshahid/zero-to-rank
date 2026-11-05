<?php
/**
 * 11 — FAQs: accessible accordion built on <details>/<summary>,
 * enhanced with smooth height animation in interactions.js.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$c = ztr_content( 'faq' );
?>
<section class="ztr-section ztr-faq" id="faq" aria-labelledby="ztr-faq-title" data-ztr-section="faq">
	<div class="ztr-container ztr-container--narrow">
		<div class="ztr-faq__card">
			<header class="ztr-head ztr-head--center">
				<p class="ztr-eyebrow" data-ztr-reveal><span>11</span><?php echo esc_html( $c['eyebrow'] ); ?></p>
				<h2 class="ztr-h2" id="ztr-faq-title" data-ztr-reveal><?php echo ztr_kses_inline( $c['title'] ); // phpcs:ignore ?></h2>
				<p class="ztr-lead" data-ztr-reveal><?php esc_html_e( 'Still unsure? The audit is free and comes with a real conversation, not a sales script.', 'zero-to-rank' ); ?></p>
			</header>

			<div class="ztr-accordion" data-ztr-accordion>
				<?php foreach ( $c['items'] as $i => $item ) : ?>
					<details class="ztr-acc ztr-faq__item" data-ztr-reveal<?php echo 0 === $i ? ' open' : ''; ?>>
						<summary class="ztr-acc__q">
							<h3 class="ztr-acc__title"><?php echo esc_html( $item['q'] ); ?></h3>
							<span class="ztr-acc__icon" aria-hidden="true"></span>
						</summary>
						<div class="ztr-acc__a"><p><?php echo esc_html( $item['a'] ); ?></p></div>
					</details>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

<?php
/**
 * 08 — Why Zero to Rank: the argument on the left (with the platforms we
 * work across), the differentiators as a ruled list on the right.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$c     = ztr_content( 'why' );
$trust = ztr_content( 'trust' );
?>
<section class="ztr-section ztr-why" id="why" aria-labelledby="ztr-why-title" data-ztr-section="why">
	<div class="ztr-container ztr-why__grid">
		<div class="ztr-why__intro">
			<p class="ztr-eyebrow" data-ztr-reveal><span>08</span><?php echo esc_html( $c['eyebrow'] ); ?></p>
			<h2 class="ztr-h2" id="ztr-why-title" data-ztr-words><?php echo ztr_kses_inline( $c['title'] ); // phpcs:ignore ?></h2>
			<p class="ztr-lead" data-ztr-reveal><?php echo esc_html( $c['intro'] ); ?></p>
			<?php if ( ! empty( $trust['platforms'] ) ) : ?>
				<ul class="ztr-plats" aria-label="<?php esc_attr_e( 'Where we build visibility', 'zero-to-rank' ); ?>">
					<?php foreach ( $trust['platforms'] as $i => $p ) : ?>
						<li class="ztr-plat" data-ztr-reveal style="--d:<?php echo (int) $i; ?>"><?php echo esc_html( $p ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>

		<ul class="ztr-why__list">
			<?php foreach ( $c['differentiators'] as $i => $d ) : ?>
				<li class="ztr-why__item" data-ztr-reveal style="--d:<?php echo (int) $i; ?>">
					<span class="ztr-why__ic" aria-hidden="true"><?php echo ztr_icon( $d['icon'] ); // phpcs:ignore ?></span>
					<div>
						<h3 class="ztr-why__title"><?php echo esc_html( $d['title'] ); ?></h3>
						<p><?php echo esc_html( $d['text'] ); ?></p>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

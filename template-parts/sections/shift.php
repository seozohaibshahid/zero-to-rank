<?php
/**
 * 06 — The visibility shift: a head-to-head comparison. Two columns with
 * aligned rows (the query, how the buyer searches, what earns visibility,
 * the bottom line) and a "vs" marker between them; the highlighted column is
 * the dark one. Stacks into two cards on small screens.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$c      = ztr_args( 'shift', isset( $args ) ? $args : array() );
$modes  = array_slice( array_values( (array) $c['modes'] ), 0, 2 );
$anchor = ! empty( $c['anchor'] ) ? $c['anchor'] : 'shift';
?>
<section class="ztr-section ztr-shift" id="<?php echo esc_attr( $anchor ); ?>" aria-labelledby="ztr-shift-title" data-ztr-section="shift">
	<div class="ztr-container">
		<header class="ztr-head ztr-head--center">
			<p class="ztr-eyebrow" data-ztr-reveal><span></span><?php echo esc_html( $c['eyebrow'] ); ?></p>
			<h2 class="ztr-h2" id="ztr-shift-title" data-ztr-words><?php echo ztr_kses_inline( $c['title'] ); // phpcs:ignore ?></h2>
			<p class="ztr-lead" data-ztr-reveal><?php echo esc_html( $c['intro'] ); ?></p>
		</header>

		<div class="ztr-vs" data-ztr-reveal="card">
			<?php
			foreach ( $modes as $i => $mode ) :
				$hl    = ! empty( $mode['highlight'] ) && 'no' !== $mode['highlight'];
				$steps = ztr_lines( isset( $mode['steps'] ) ? $mode['steps'] : array() );
				$wins  = ztr_lines( isset( $mode['wins'] ) ? $mode['wins'] : array() );
				?>
				<article class="ztr-vs__col<?php echo $hl ? ' is-dark is-hl' : ''; ?>">
					<header class="ztr-vs__cell ztr-vs__top">
						<?php if ( $hl ) : ?>
							<span class="ztr-vs__badge"><?php esc_html_e( 'Where buyers are moving', 'zero-to-rank' ); ?></span>
						<?php endif; ?>
						<h3 class="ztr-vs__title"><?php echo esc_html( $mode['label'] ); ?></h3>
						<p class="ztr-vs__query"><span class="ztr-vs__caret" aria-hidden="true"><?php echo ztr_icon( $hl ? 'chat' : 'search' ); // phpcs:ignore ?></span>“<?php echo esc_html( $mode['query'] ); ?>”</p>
					</header>

					<div class="ztr-vs__cell">
						<p class="ztr-vs__label"><?php esc_html_e( 'How the buyer searches', 'zero-to-rank' ); ?></p>
						<ol class="ztr-vs__flow">
							<?php foreach ( $steps as $step ) : ?>
								<li><?php echo esc_html( $step ); ?></li>
							<?php endforeach; ?>
						</ol>
					</div>

					<div class="ztr-vs__cell">
						<p class="ztr-vs__label"><?php esc_html_e( 'What earns visibility', 'zero-to-rank' ); ?></p>
						<ul class="ztr-vs__wins">
							<?php foreach ( $wins as $win ) : ?>
								<li><?php echo ztr_icon( 'check' ); // phpcs:ignore ?><span><?php echo esc_html( $win ); ?></span></li>
							<?php endforeach; ?>
						</ul>
					</div>

					<p class="ztr-vs__cell ztr-vs__summary"><?php echo esc_html( $mode['summary'] ); ?></p>
				</article>
				<?php if ( 0 === $i && count( $modes ) > 1 ) : ?>
					<span class="ztr-vs__vs" aria-hidden="true">vs</span>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>
</section>

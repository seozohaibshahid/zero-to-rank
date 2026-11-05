<?php
/**
 * 01 — Navigation: a floating glass pill. Logo left, section links in the
 * middle, CTA right. Hides on scroll down, returns on scroll up; full-screen
 * menu on small screens.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;
?>
<header class="ztr-nav" data-ztr-nav>
	<div class="ztr-container ztr-nav__wrap">
		<div class="ztr-nav__inner">
			<a class="ztr-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Zero to Rank — home', 'zero-to-rank' ); ?>">
				<?php echo ztr_logo_mark(); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
				<span class="ztr-logo__text">Zero <span class="ztr-logo__to">to</span> Rank</span>
			</a>

			<nav class="ztr-nav__menu" id="ztr-menu" aria-label="<?php esc_attr_e( 'Primary', 'zero-to-rank' ); ?>" data-ztr-menu>
				<?php
				if ( has_nav_menu( 'ztr_primary' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'ztr_primary',
							'container'      => false,
							'depth'          => 1,
							'items_wrap'     => '<ul class="ztr-nav__list">%3$s</ul>',
							'fallback_cb'    => false,
						)
					);
				} else {
					echo '<ul class="ztr-nav__list">';
					foreach ( ztr_content( 'nav' ) as $item ) {
						printf( '<li><a href="%1$s">%2$s</a></li>', esc_url( ztr_link( $item['url'] ) ), esc_html( $item['label'] ) );
					}
					echo '</ul>';
				}
				?>
				<span class="ztr-nav__indicator" aria-hidden="true" data-ztr-nav-indicator></span>
				<a class="ztr-btn ztr-btn--glow ztr-btn--lg ztr-nav__menu-cta" href="<?php echo esc_url( ztr_link( ztr_audit_url() ) ); ?>" <?php echo ztr_track_attrs( 'cta_click', 'nav_mobile', 'Free visibility audit' ); // phpcs:ignore ?>>
					<span><?php esc_html_e( 'Get your free visibility audit', 'zero-to-rank' ); ?></span>
					<span class="ztr-btn__arr" aria-hidden="true"><?php echo ztr_icon( 'arrow-right' ); // phpcs:ignore ?></span>
				</a>
			</nav>

			<div class="ztr-nav__actions">
				<a class="ztr-btn ztr-btn--glow ztr-nav__cta" href="<?php echo esc_url( ztr_link( ztr_audit_url() ) ); ?>" data-ztr-magnetic <?php echo ztr_track_attrs( 'cta_click', 'nav', 'Free audit' ); // phpcs:ignore ?>>
					<span><?php esc_html_e( 'Free audit', 'zero-to-rank' ); ?></span>
					<span class="ztr-btn__arr" aria-hidden="true"><?php echo ztr_icon( 'arrow-right' ); // phpcs:ignore ?></span>
				</a>
				<button class="ztr-nav__toggle" type="button" aria-expanded="false" aria-controls="ztr-menu" data-ztr-menu-toggle>
					<span class="ztr-nav__toggle-label" data-ztr-menu-label><?php esc_html_e( 'Menu', 'zero-to-rank' ); ?></span>
					<span class="ztr-nav__burger" aria-hidden="true"><span></span><span></span></span>
				</button>
			</div>
		</div>
	</div>
</header>

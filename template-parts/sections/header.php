<?php
/**
 * 01 — Navigation: sticky header with scroll-aware background and mobile menu.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;
?>
<header class="ztr-nav" data-ztr-nav>
	<div class="ztr-container ztr-nav__inner">
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
					printf( '<li><a href="%1$s">%2$s</a></li>', esc_url( $item['url'] ), esc_html( $item['label'] ) );
				}
				echo '</ul>';
			}
			?>
			<span class="ztr-nav__indicator" aria-hidden="true" data-ztr-nav-indicator></span>
			<a class="ztr-btn ztr-btn--lime ztr-nav__menu-cta" href="<?php echo esc_url( ztr_audit_url() ); ?>" <?php echo ztr_track_attrs( 'cta_click', 'nav_mobile', 'Free visibility audit' ); // phpcs:ignore ?>>
				<span><?php esc_html_e( 'Get your free visibility audit', 'zero-to-rank' ); ?></span>
				<?php echo ztr_icon( 'arrow-up-right' ); // phpcs:ignore ?>
			</a>
		</nav>

		<div class="ztr-nav__actions">
			<a class="ztr-btn ztr-btn--lime ztr-btn--sm ztr-nav__cta" href="<?php echo esc_url( ztr_audit_url() ); ?>" <?php echo ztr_track_attrs( 'cta_click', 'nav', 'Free audit' ); // phpcs:ignore ?>>
				<span><?php esc_html_e( 'Free audit', 'zero-to-rank' ); ?></span>
				<?php echo ztr_icon( 'arrow-up-right' ); // phpcs:ignore ?>
			</a>
			<button class="ztr-nav__toggle" type="button" aria-expanded="false" aria-controls="ztr-menu" data-ztr-menu-toggle>
				<span class="ztr-sr"><?php esc_html_e( 'Menu', 'zero-to-rank' ); ?></span>
				<span class="ztr-nav__burger" aria-hidden="true"><span></span><span></span></span>
			</button>
		</div>
	</div>
</header>

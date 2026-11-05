<?php
/**
 * 12 — Footer: brand and audit link, navigation, contact details and a
 * giant wordmark whose letters rise as the page ends.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$c        = ztr_content( 'footer' );
$services = ztr_content( 'services' );
$email    = ztr_setting( 'email' );
$phone    = ztr_setting( 'phone' );
$location = ztr_setting( 'location' );
$profiles = ztr_social_profiles();
?>
<footer class="ztr-footer ztr-dark" aria-label="<?php esc_attr_e( 'Site footer', 'zero-to-rank' ); ?>">
	<div class="ztr-container">
		<div class="ztr-footer__grid">
			<div class="ztr-footer__brand">
				<a class="ztr-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php echo ztr_logo_mark(); // phpcs:ignore ?>
					<span class="ztr-logo__text">Zero <span class="ztr-logo__to">to</span> Rank</span>
				</a>
				<p><?php echo esc_html( $c['blurb'] ); ?></p>
				<a class="ztr-btn ztr-btn--ghost ztr-footer__cta" href="<?php echo esc_url( ztr_link( ztr_audit_url() ) ); ?>" data-ztr-magnetic <?php echo ztr_track_attrs( 'cta_click', 'footer', 'Free visibility audit' ); // phpcs:ignore ?>>
					<span><?php esc_html_e( 'Get a free audit', 'zero-to-rank' ); ?></span>
					<span class="ztr-btn__arr" aria-hidden="true"><?php echo ztr_icon( 'arrow-right' ); // phpcs:ignore ?></span>
				</a>
			</div>

			<nav class="ztr-footer__col" aria-label="<?php esc_attr_e( 'Services', 'zero-to-rank' ); ?>">
				<p class="ztr-footer__heading"><?php esc_html_e( 'Services', 'zero-to-rank' ); ?></p>
				<ul>
					<?php foreach ( $services['items'] as $s ) : ?>
						<li><a href="<?php echo esc_url( ztr_link( '#service-' . $s['key'] ) ); ?>"><?php echo esc_html( $s['name'] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</nav>

			<nav class="ztr-footer__col" aria-label="<?php esc_attr_e( 'Footer', 'zero-to-rank' ); ?>">
				<p class="ztr-footer__heading"><?php esc_html_e( 'Explore', 'zero-to-rank' ); ?></p>
				<?php
				if ( has_nav_menu( 'ztr_footer' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'ztr_footer',
							'container'      => false,
							'depth'          => 1,
							'fallback_cb'    => false,
						)
					);
				} else {
					echo '<ul>';
					foreach ( ztr_content( 'nav' ) as $item ) {
						printf( '<li><a href="%1$s">%2$s</a></li>', esc_url( ztr_link( $item['url'] ) ), esc_html( $item['label'] ) );
					}
					printf( '<li><a href="%1$s">%2$s</a></li>', esc_url( ztr_link( '#shift' ) ), esc_html__( 'The visibility shift', 'zero-to-rank' ) );
					echo '</ul>';
				}
				?>
			</nav>

			<div class="ztr-footer__col">
				<p class="ztr-footer__heading"><?php esc_html_e( 'Contact', 'zero-to-rank' ); ?></p>
				<ul class="ztr-footer__contact">
					<?php if ( $email ) : ?>
						<li><?php echo ztr_icon( 'mail' ); // phpcs:ignore ?><a href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>" <?php echo ztr_track_attrs( 'contact_click', 'footer', 'email' ); // phpcs:ignore ?>><?php echo esc_html( antispambot( $email ) ); ?></a></li>
					<?php endif; ?>
					<?php if ( $phone ) : ?>
						<li><?php echo ztr_icon( 'phone' ); // phpcs:ignore ?><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" <?php echo ztr_track_attrs( 'contact_click', 'footer', 'phone' ); // phpcs:ignore ?>><?php echo esc_html( $phone ); ?></a></li>
					<?php endif; ?>
					<?php if ( $location ) : ?>
						<li><?php echo ztr_icon( 'pin' ); // phpcs:ignore ?><span><?php echo esc_html( $location ); ?></span></li>
					<?php endif; ?>
					<li><?php echo ztr_icon( 'arrow-up-right' ); // phpcs:ignore ?><a href="<?php echo esc_url( ztr_link( ztr_audit_url() ) ); ?>"><?php esc_html_e( 'Request a free audit', 'zero-to-rank' ); ?></a></li>
				</ul>
				<?php if ( $profiles ) : ?>
					<ul class="ztr-footer__social">
						<?php foreach ( $profiles as $label => $url ) : ?>
							<li><a href="<?php echo esc_url( $url ); ?>" rel="noopener me" target="_blank"><?php echo esc_html( $label ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		</div>

		<p class="ztr-footer__wordmark" aria-hidden="true" data-ztr-wordmark><?php
		foreach ( preg_split( '//u', 'Zero to Rank', -1, PREG_SPLIT_NO_EMPTY ) as $ch ) {
			echo ' ' === $ch ? '<span class="is-space"> </span>' : '<span>' . esc_html( $ch ) . '</span>';
		}
		?></p>

		<div class="ztr-footer__legal">
			<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ? get_bloginfo( 'name' ) : 'Zero to Rank' ); ?>. <?php esc_html_e( 'All rights reserved.', 'zero-to-rank' ); ?></p>
			<ul>
				<?php if ( get_privacy_policy_url() ) : ?>
					<li><a href="<?php echo esc_url( get_privacy_policy_url() ); ?>"><?php esc_html_e( 'Privacy policy', 'zero-to-rank' ); ?></a></li>
				<?php endif; ?>
				<li><a href="#ztr-top" data-ztr-to-top><?php esc_html_e( 'Back to top', 'zero-to-rank' ); ?> &uarr;</a></li>
			</ul>
		</div>
	</div>
</footer>

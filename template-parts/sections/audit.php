<?php
/**
 * 10 — Conversion: free visibility audit form.
 * Processing, validation and spam protection live in inc/form.php.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$c = ztr_content( 'audit' );

// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Display-only status flag after redirect.
$status = isset( $_GET['ztr_audit'] ) ? sanitize_key( wp_unslash( $_GET['ztr_audit'] ) ) : '';
$email  = ztr_setting( 'email' );
?>
<section class="ztr-section ztr-audit ztr-band" id="audit" aria-labelledby="ztr-audit-title" data-ztr-section="audit">
	<div class="ztr-container ztr-audit__layout">
		<div class="ztr-audit__copy">
			<p class="ztr-eyebrow" data-ztr-reveal><span>10</span><?php echo esc_html( $c['eyebrow'] ); ?></p>
			<h2 class="ztr-h2" id="ztr-audit-title" data-ztr-reveal><?php echo ztr_kses_inline( $c['title'] ); // phpcs:ignore ?></h2>
			<p class="ztr-lead" data-ztr-reveal><?php echo esc_html( $c['intro'] ); ?></p>
			<p class="ztr-audit__kicker" data-ztr-reveal><?php esc_html_e( 'Your audit covers', 'zero-to-rank' ); ?></p>
			<ul class="ztr-audit__list">
				<?php foreach ( $c['includes'] as $i => $item ) : ?>
					<li data-ztr-reveal style="--d:<?php echo (int) $i; ?>"><?php echo ztr_icon( 'check' ); // phpcs:ignore ?><span><?php echo esc_html( $item ); ?></span></li>
				<?php endforeach; ?>
			</ul>
			<p class="ztr-audit__reassure" data-ztr-reveal><?php echo ztr_icon( 'shield' ); // phpcs:ignore ?><span><?php echo esc_html( $c['reassure'] ); ?></span></p>
		</div>

		<div class="ztr-audit__card" data-ztr-reveal>
			<div class="ztr-audit__card-head">
				<p class="ztr-h3"><?php esc_html_e( 'Request your free audit', 'zero-to-rank' ); ?></p>
				<span class="ztr-chip ztr-chip--lime"><?php esc_html_e( 'Takes about a minute', 'zero-to-rank' ); ?></span>
			</div>
			<form class="ztr-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" novalidate data-ztr-form>
				<input type="hidden" name="action" value="ztr_audit">
				<input type="hidden" name="ztr_ts" value="<?php echo esc_attr( ztr_form_token() ); ?>">
				<input type="hidden" name="ztr_source" value="<?php echo esc_url( get_permalink() ); ?>">

				<?php /* Honeypot — hidden from people and assistive tech. */ ?>
				<div class="ztr-hp" aria-hidden="true">
					<label for="ztr_fax"><?php esc_html_e( 'Leave this field empty', 'zero-to-rank' ); ?></label>
					<input type="text" id="ztr_fax" name="ztr_fax" tabindex="-1" autocomplete="off" value="">
				</div>

				<div class="ztr-form__status" role="status" aria-live="polite" data-ztr-form-status<?php echo $status ? '' : ' hidden'; ?> data-state="<?php echo esc_attr( $status ); ?>">
					<?php
					if ( 'success' === $status ) {
						echo esc_html( $c['success'] );
					} elseif ( 'error' === $status ) {
						esc_html_e( 'We could not send your request. Please check your details and try again.', 'zero-to-rank' );
					}
					?>
				</div>

				<div class="ztr-form__grid">
					<div class="ztr-field">
						<label for="ztr_name"><?php esc_html_e( 'Your name', 'zero-to-rank' ); ?> <span aria-hidden="true">*</span></label>
						<input type="text" id="ztr_name" name="ztr_name" autocomplete="name" required maxlength="100" aria-describedby="ztr_name-error">
						<p class="ztr-field__error" id="ztr_name-error" data-ztr-error></p>
					</div>
					<div class="ztr-field">
						<label for="ztr_email"><?php esc_html_e( 'Work email', 'zero-to-rank' ); ?> <span aria-hidden="true">*</span></label>
						<input type="email" id="ztr_email" name="ztr_email" autocomplete="email" required maxlength="150" aria-describedby="ztr_email-error">
						<p class="ztr-field__error" id="ztr_email-error" data-ztr-error></p>
					</div>
					<div class="ztr-field">
						<label for="ztr_website"><?php esc_html_e( 'Website', 'zero-to-rank' ); ?> <span aria-hidden="true">*</span></label>
						<input type="text" id="ztr_website" name="ztr_website" inputmode="url" autocomplete="url" placeholder="example.com" required maxlength="200" aria-describedby="ztr_website-error" data-ztr-url>
						<p class="ztr-field__error" id="ztr_website-error" data-ztr-error></p>
					</div>
					<div class="ztr-field">
						<label for="ztr_company"><?php esc_html_e( 'Company', 'zero-to-rank' ); ?> <span class="ztr-field__opt"><?php esc_html_e( '(optional)', 'zero-to-rank' ); ?></span></label>
						<input type="text" id="ztr_company" name="ztr_company" autocomplete="organization" maxlength="120">
					</div>
					<div class="ztr-field ztr-field--full">
						<label for="ztr_focus"><?php esc_html_e( 'What should we focus on?', 'zero-to-rank' ); ?></label>
						<div class="ztr-select">
							<select id="ztr_focus" name="ztr_focus" data-ztr-focus-select>
								<?php foreach ( ztr_audit_focus_options() as $value => $label ) : ?>
									<option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="ztr-field ztr-field--full">
						<label for="ztr_message"><?php esc_html_e( 'Anything we should know?', 'zero-to-rank' ); ?> <span class="ztr-field__opt"><?php esc_html_e( '(optional)', 'zero-to-rank' ); ?></span></label>
						<textarea id="ztr_message" name="ztr_message" rows="3" maxlength="2000" placeholder="<?php esc_attr_e( 'Goals, key competitors, markets you serve…', 'zero-to-rank' ); ?>" aria-describedby="ztr_message-error"></textarea>
						<p class="ztr-field__error" id="ztr_message-error" data-ztr-error></p>
					</div>
					<div class="ztr-field ztr-field--full ztr-field--check">
						<input type="checkbox" id="ztr_consent" name="ztr_consent" value="1" required aria-describedby="ztr_consent-error">
						<label for="ztr_consent">
							<?php
							$privacy = get_privacy_policy_url();
							if ( $privacy ) {
								printf(
									/* translators: %s: privacy policy link. */
									esc_html__( 'You can contact me about my audit. I have read the %s.', 'zero-to-rank' ),
									'<a href="' . esc_url( $privacy ) . '">' . esc_html__( 'privacy policy', 'zero-to-rank' ) . '</a>'
								);
							} else {
								esc_html_e( 'You can contact me about my audit.', 'zero-to-rank' );
							}
							?>
						</label>
						<p class="ztr-field__error" id="ztr_consent-error" data-ztr-error></p>
					</div>
				</div>

				<button class="ztr-btn ztr-btn--lime ztr-btn--lg ztr-btn--block" type="submit" data-ztr-submit>
					<span class="ztr-btn__label"><?php echo esc_html( $c['button'] ); ?></span>
					<?php echo ztr_icon( 'arrow-up-right' ); // phpcs:ignore ?>
					<span class="ztr-btn__spinner" aria-hidden="true"></span>
				</button>

				<?php if ( $email ) : ?>
					<p class="ztr-form__alt">
						<?php esc_html_e( 'Prefer email?', 'zero-to-rank' ); ?>
						<a href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>" <?php echo ztr_track_attrs( 'contact_click', 'audit', 'email' ); // phpcs:ignore ?>><?php echo esc_html( antispambot( $email ) ); ?></a>
					</p>
				<?php endif; ?>
			</form>

			<div class="ztr-form__success" data-ztr-form-success hidden tabindex="-1">
				<span class="ztr-form__success-icon" aria-hidden="true"><?php echo ztr_icon( 'check' ); // phpcs:ignore ?></span>
				<h3 class="ztr-h3"><?php esc_html_e( 'Request received.', 'zero-to-rank' ); ?></h3>
				<p data-ztr-success-text><?php echo esc_html( $c['success'] ); ?></p>
			</div>
		</div>
	</div>
</section>

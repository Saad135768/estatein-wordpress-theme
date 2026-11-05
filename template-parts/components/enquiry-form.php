<?php
/**
 * Enquiry form card used on Properties, Property Details and Contact.
 *
 * Expects $args['type'] (property|inquiry|contact) and, for inquiries, $args['property_id'].
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

$estatein_type    = $args['type'];
$estatein_fields  = estatein_enquiry_fields( $estatein_type );
$estatein_options = estatein_enquiry_options();
$estatein_message = estatein_enquiry_message();
$estatein_prefix  = 'enquiry-' . $estatein_type . '-';
?>
<form class="enquiry enquiry--<?php echo esc_attr( $estatein_type ); ?>" id="enquiry-<?php echo esc_attr( $estatein_type ); ?>" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
	<input type="hidden" name="action" value="estatein_enquiry">
	<input type="hidden" name="enquiry_type" value="<?php echo esc_attr( $estatein_type ); ?>">
	<?php wp_nonce_field( 'estatein_enquiry_' . $estatein_type, 'estatein_enquiry_nonce' ); ?>
	<input class="newsletter__trap" type="text" name="company" tabindex="-1" autocomplete="off" aria-hidden="true">

	<?php if ( $estatein_message ) : ?>
		<p class="form-message form-message--<?php echo esc_attr( $estatein_message['type'] ); ?>" role="status"><?php echo esc_html( $estatein_message['text'] ); ?></p>
	<?php endif; ?>

	<div class="enquiry__grid">
		<?php foreach ( $estatein_fields as $estatein_name => $estatein_field ) : ?>
			<?php
			$estatein_id   = $estatein_prefix . $estatein_name;
			$estatein_span = $estatein_field['span'] ?? '';
			$estatein_req  = ! empty( $estatein_field['required'] );
			?>
			<div class="field<?php echo $estatein_span ? ' field--' . esc_attr( $estatein_span ) : ''; ?>">
				<?php if ( 'contact_method' === $estatein_field['type'] ) : ?>
					<fieldset class="field__fieldset">
						<legend class="field__label"><?php echo esc_html( $estatein_field['label'] ); ?></legend>
						<div class="contact-method">
							<label class="contact-method__option">
								<?php estatein_the_icon( 'phone', 'icon icon--20' ); ?>
								<span class="screen-reader-text"><?php esc_html_e( 'Contact me by phone', 'estatein' ); ?></span>
								<span class="contact-method__text" aria-hidden="true"><?php esc_html_e( 'Enter Your Number', 'estatein' ); ?></span>
								<input class="contact-method__radio" type="radio" name="contact_method" value="phone" checked>
							</label>
							<label class="contact-method__option">
								<?php estatein_the_icon( 'envelope', 'icon icon--20' ); ?>
								<span class="screen-reader-text"><?php esc_html_e( 'Contact me by email', 'estatein' ); ?></span>
								<span class="contact-method__text" aria-hidden="true"><?php esc_html_e( 'Enter Your Email', 'estatein' ); ?></span>
								<input class="contact-method__radio" type="radio" name="contact_method" value="email">
							</label>
						</div>
					</fieldset>
				<?php elseif ( 'property' === $estatein_field['type'] ) : ?>
					<?php
					$estatein_pid      = (int) ( $args['property_id'] ?? 0 );
					$estatein_location = estatein_field( 'location', $estatein_pid );
					?>
					<label class="field__label" for="<?php echo esc_attr( $estatein_id ); ?>"><?php echo esc_html( $estatein_field['label'] ); ?></label>
					<div class="field__control field__control--icon">
						<input class="field__input" id="<?php echo esc_attr( $estatein_id ); ?>" type="text" value="<?php echo esc_attr( get_the_title( $estatein_pid ) . ( $estatein_location ? ', ' . $estatein_location : '' ) ); ?>" readonly>
						<input type="hidden" name="property" value="<?php echo esc_attr( $estatein_pid ); ?>">
						<?php estatein_the_icon( 'pin', 'icon icon--20 field__icon' ); ?>
					</div>
				<?php else : ?>
					<label class="field__label" for="<?php echo esc_attr( $estatein_id ); ?>"><?php echo esc_html( $estatein_field['label'] ); ?><?php echo $estatein_req ? '<span class="screen-reader-text"> ' . esc_html__( '(required)', 'estatein' ) . '</span>' : ''; ?></label>
					<?php if ( 'textarea' === $estatein_field['type'] ) : ?>
						<textarea class="field__input field__input--textarea" id="<?php echo esc_attr( $estatein_id ); ?>" name="<?php echo esc_attr( $estatein_name ); ?>" rows="5" placeholder="<?php echo esc_attr( $estatein_field['placeholder'] ); ?>"<?php echo $estatein_req ? ' required' : ''; ?>></textarea>
					<?php elseif ( 'select' === $estatein_field['type'] ) : ?>
						<div class="field__control field__control--select">
							<select class="field__input" id="<?php echo esc_attr( $estatein_id ); ?>" name="<?php echo esc_attr( $estatein_name ); ?>">
								<option value=""><?php echo esc_html( $estatein_field['placeholder'] ); ?></option>
								<?php foreach ( $estatein_options[ $estatein_field['options'] ] as $estatein_option ) : ?>
									<option value="<?php echo esc_attr( $estatein_option ); ?>"><?php echo esc_html( $estatein_option ); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					<?php else : ?>
						<input class="field__input" id="<?php echo esc_attr( $estatein_id ); ?>" type="<?php echo esc_attr( $estatein_field['type'] ); ?>" name="<?php echo esc_attr( $estatein_name ); ?>" placeholder="<?php echo esc_attr( $estatein_field['placeholder'] ); ?>" autocomplete="<?php echo esc_attr( $estatein_field['autocomplete'] ?? 'off' ); ?>"<?php echo $estatein_req ? ' required' : ''; ?>>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="enquiry__footer">
		<label class="checkbox">
			<input class="checkbox__input" type="checkbox" name="terms" value="1" required>
			<span class="checkbox__box" aria-hidden="true"></span>
			<span><?php esc_html_e( 'I agree with Terms of Use and Privacy Policy', 'estatein' ); ?></span>
		</label>
		<button class="btn btn--primary" type="submit"><?php esc_html_e( 'Send Your Message', 'estatein' ); ?></button>
	</div>
</form>

<?php
/**
 * Section header: sparkle accent, heading, description and optional button.
 *
 * Pass either $args['prefix'] (reads Home page ACF fields) or explicit
 * $args['title'], $args['text'] and $args['button']. $args['id'] sets the heading id
 * and $args['tag'] the heading level (default h2).
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

if ( ! empty( $args['prefix'] ) ) {
	$estatein_title  = estatein_home( $args['prefix'] . '_heading' );
	$estatein_text   = estatein_home( $args['prefix'] . '_text' );
	$estatein_button = estatein_home( $args['prefix'] . '_button' );
} else {
	$estatein_title  = $args['title'] ?? '';
	$estatein_text   = $args['text'] ?? '';
	$estatein_button = $args['button'] ?? array();
}

$estatein_tag = in_array( $args['tag'] ?? 'h2', array( 'h1', 'h2' ), true ) ? ( $args['tag'] ?? 'h2' ) : 'h2';
?>
<div class="section-header">
	<div class="section-header__text">
		<img class="section-header__sparkles" src="<?php echo esc_url( ESTATEIN_URI . '/assets/icons/sparkles.svg' ); ?>" width="68" height="30" alt="" aria-hidden="true">
		<<?php echo esc_html( $estatein_tag ); ?> class="section-header__title" id="<?php echo esc_attr( $args['id'] ?? '' ); ?>"><?php echo esc_html( $estatein_title ); ?></<?php echo esc_html( $estatein_tag ); ?>>
		<?php if ( $estatein_text ) : ?>
			<p class="section-header__desc"><?php echo esc_html( $estatein_text ); ?></p>
		<?php endif; ?>
	</div>
	<?php estatein_button( $estatein_button, 'secondary', 'section-header__button' ); ?>
</div>

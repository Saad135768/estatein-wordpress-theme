<?php
/**
 * Section header: sparkle accent, heading, description and "View all" button.
 *
 * Expects $args['prefix'] (field prefix) and $args['id'] (heading id).
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

$estatein_prefix = $args['prefix'];
$estatein_button = estatein_home( $estatein_prefix . '_button' );
?>
<div class="section-header">
	<div class="section-header__text">
		<img class="section-header__sparkles" src="<?php echo esc_url( ESTATEIN_URI . '/assets/icons/sparkles.svg' ); ?>" width="68" height="30" alt="" aria-hidden="true">
		<h2 class="section-header__title" id="<?php echo esc_attr( $args['id'] ); ?>"><?php echo esc_html( estatein_home( $estatein_prefix . '_heading' ) ); ?></h2>
		<p class="section-header__desc"><?php echo esc_html( estatein_home( $estatein_prefix . '_text' ) ); ?></p>
	</div>
	<?php estatein_button( $estatein_button, 'secondary', 'section-header__button' ); ?>
</div>

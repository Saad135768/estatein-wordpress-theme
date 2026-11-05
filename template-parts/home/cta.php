<?php
/**
 * Home closing call-to-action band.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="cta" aria-labelledby="cta-title">
	<img class="cta__pattern cta__pattern--left" src="<?php echo esc_url( ESTATEIN_URI . '/assets/images/pattern-squares-left.webp' ); ?>" width="546" height="308" alt="" loading="lazy" decoding="async">
	<img class="cta__pattern cta__pattern--right" src="<?php echo esc_url( ESTATEIN_URI . '/assets/images/pattern-squares-right.webp' ); ?>" width="633" height="331" alt="" loading="lazy" decoding="async">
	<div class="container cta__inner">
		<div class="cta__text">
			<h2 class="cta__title" id="cta-title"><?php echo esc_html( estatein_home( 'cta_title' ) ); ?></h2>
			<p class="cta__desc"><?php echo esc_html( estatein_home( 'cta_text' ) ); ?></p>
		</div>
		<?php estatein_button( estatein_home( 'cta_button' ), 'primary', 'cta__button' ); ?>
	</div>
</section>

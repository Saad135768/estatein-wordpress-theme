<?php
/**
 * Slider footer: "01 of 10" counter, mobile "View all" button and prev/next arrows.
 *
 * Expects $args['total'], $args['label'] and $args['button'] (ACF link array).
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="slider__footer">
	<p class="slider__counter" aria-live="polite">
		<span class="slider__current" data-slider-current>01</span>
		<span class="slider__of"><?php esc_html_e( 'of', 'estatein' ); ?> <span data-slider-total><?php echo esc_html( estatein_pad( (int) $args['total'] ) ); ?></span></span>
	</p>
	<?php estatein_button( $args['button'], 'secondary', 'slider__view-all' ); ?>
	<div class="slider__nav">
		<button class="slider__arrow" type="button" data-slider-prev aria-label="<?php echo esc_attr( sprintf( __( 'Previous %s', 'estatein' ), $args['label'] ) ); ?>" disabled>
			<?php estatein_the_icon( 'arrow-left', 'icon icon--30' ); ?>
		</button>
		<button class="slider__arrow" type="button" data-slider-next aria-label="<?php echo esc_attr( sprintf( __( 'Next %s', 'estatein' ), $args['label'] ) ); ?>">
			<?php estatein_the_icon( 'arrow-right', 'icon icon--30' ); ?>
		</button>
	</div>
</div>

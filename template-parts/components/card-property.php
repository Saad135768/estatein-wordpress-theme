<?php
/**
 * Property card used on the Home slider and the properties archive.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

$estatein_id      = get_the_ID();
$estatein_listing = 'listing' === ( $args['variant'] ?? '' );
$estatein_tagline = estatein_field( 'tagline', $estatein_id );
$estatein_excerpt = has_excerpt() ? get_the_excerpt() : wp_trim_words( get_the_content(), 14, '' );
?>
<article class="property-card">
	<a class="property-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail(
				'estatein-card',
				array(
					'class'   => 'property-card__image',
					'loading' => 'lazy',
					'sizes'   => '(min-width: 1200px) 432px, (min-width: 768px) 45vw, 90vw',
				)
			);
		}
		?>
	</a>
	<div class="property-card__body">
		<?php if ( $estatein_listing && $estatein_tagline ) : ?>
			<p class="chip property-card__tagline"><?php echo esc_html( $estatein_tagline ); ?></p>
		<?php endif; ?>
		<div class="property-card__text">
			<h3 class="property-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<p class="property-card__excerpt">
				<?php echo esc_html( rtrim( $estatein_excerpt, '. ' ) ); ?>…
				<a class="property-card__more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read More', 'estatein' ); ?><span class="screen-reader-text"> <?php esc_html_e( 'about', 'estatein' ); ?> <?php the_title(); ?></span></a>
			</p>
		</div>

		<?php $estatein_chips = $estatein_listing ? array() : estatein_property_chips( $estatein_id ); ?>
		<?php if ( $estatein_chips ) : ?>
			<ul class="chips">
				<?php foreach ( $estatein_chips as $estatein_chip ) : ?>
					<li class="chip"><?php estatein_the_icon( $estatein_chip['icon'], 'icon icon--chip' ); ?><?php echo esc_html( $estatein_chip['label'] ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<div class="property-card__footer">
			<p class="property-card__price">
				<span class="property-card__price-label"><?php esc_html_e( 'Price', 'estatein' ); ?></span>
				<span class="property-card__price-value"><?php echo esc_html( estatein_format_price( estatein_field( 'price', $estatein_id ) ) ); ?></span>
			</p>
			<a class="btn btn--primary property-card__cta" href="<?php the_permalink(); ?>"><?php esc_html_e( 'View Property Details', 'estatein' ); ?><span class="screen-reader-text">: <?php the_title(); ?></span></a>
		</div>
	</div>
</article>

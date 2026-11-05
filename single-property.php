<?php
/**
 * Single property: image, key facts and description.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$estatein_id       = get_the_ID();
	$estatein_location = estatein_field( 'location', $estatein_id );
	$estatein_area     = estatein_field( 'area', $estatein_id );
	?>
	<article class="section section--first property-single">
		<div class="container">
			<a class="property-single__back" href="<?php echo esc_url( get_post_type_archive_link( 'property' ) ); ?>"><?php estatein_the_icon( 'arrow-left', 'icon icon--24' ); ?><?php esc_html_e( 'All properties', 'estatein' ); ?></a>

			<header class="property-single__header">
				<div>
					<h1 class="property-single__title"><?php the_title(); ?></h1>
					<?php if ( $estatein_location ) : ?>
						<p class="property-single__location"><?php echo esc_html( $estatein_location ); ?></p>
					<?php endif; ?>
				</div>
				<p class="property-card__price">
					<span class="property-card__price-label"><?php esc_html_e( 'Price', 'estatein' ); ?></span>
					<span class="property-card__price-value"><?php echo esc_html( estatein_format_price( estatein_field( 'price', $estatein_id ) ) ); ?></span>
				</p>
			</header>

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="property-single__media"><?php the_post_thumbnail( 'large', array( 'class' => 'property-single__image', 'fetchpriority' => 'high' ) ); ?></div>
			<?php endif; ?>

			<div class="property-single__body">
				<ul class="chips">
					<?php foreach ( estatein_property_chips( $estatein_id ) as $estatein_chip ) : ?>
						<li class="chip"><?php estatein_the_icon( $estatein_chip['icon'], 'icon icon--chip' ); ?><?php echo esc_html( $estatein_chip['label'] ); ?></li>
					<?php endforeach; ?>
					<?php if ( $estatein_area ) : ?>
						<li class="chip"><?php echo esc_html( number_format_i18n( (float) $estatein_area ) . ' ' . __( 'sq ft', 'estatein' ) ); ?></li>
					<?php endif; ?>
				</ul>
				<div class="entry__content"><?php the_content(); ?></div>
				<a class="btn btn--primary" href="<?php echo esc_url( estatein_contact_url() ); ?>"><?php esc_html_e( 'Book a Viewing', 'estatein' ); ?></a>
			</div>
		</div>
	</article>
	<?php
endwhile;

get_footer();

<?php
/**
 * Property Details: gallery, description, features, inquiry form, pricing and FAQs.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$estatein_id       = get_the_ID();
	$estatein_location = estatein_field( 'location', $estatein_id );
	$estatein_images   = estatein_property_gallery( $estatein_id );
	$estatein_features = array_filter( array_map( 'trim', explode( "\n", (string) estatein_field( 'key_features', $estatein_id ) ) ) );
	$estatein_inquiry  = estatein_content( 'property_inquiry' );
	?>
	<article class="property-detail">
		<section class="section section--first" aria-labelledby="property-title">
			<div class="container">
				<header class="property-detail__header">
					<div class="property-detail__heading">
						<h1 class="property-detail__title" id="property-title"><?php the_title(); ?></h1>
						<?php if ( $estatein_location ) : ?>
							<p class="chip property-detail__location"><?php estatein_the_icon( 'pin', 'icon icon--chip' ); ?><?php echo esc_html( $estatein_location ); ?></p>
						<?php endif; ?>
					</div>
					<p class="property-card__price property-detail__price">
						<span class="property-card__price-label"><?php esc_html_e( 'Price', 'estatein' ); ?></span>
						<span class="property-card__price-value"><?php echo esc_html( estatein_format_price( estatein_field( 'price', $estatein_id ) ) ); ?></span>
					</p>
				</header>

				<?php if ( $estatein_images ) : ?>
					<div class="gallery" data-gallery>
						<ul class="gallery__thumbs" role="list" aria-label="<?php esc_attr_e( 'Choose photo', 'estatein' ); ?>">
							<?php foreach ( $estatein_images as $estatein_index => $estatein_image_id ) : ?>
								<li>
									<button class="gallery__thumb" type="button" data-gallery-thumb="<?php echo esc_attr( $estatein_index ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Show photo %d', 'estatein' ), $estatein_index + 1 ) ); ?>"<?php echo 0 === $estatein_index ? ' aria-current="true"' : ''; ?>>
										<?php echo wp_get_attachment_image( $estatein_image_id, 'thumbnail', false, array( 'alt' => '', 'loading' => 'lazy' ) ); ?>
									</button>
								</li>
							<?php endforeach; ?>
						</ul>
						<div class="slider slider--2 gallery__slider" data-slider>
							<ul class="slider__track gallery__track" data-slider-track tabindex="0">
								<?php foreach ( $estatein_images as $estatein_index => $estatein_image_id ) : ?>
									<li class="slider__item gallery__item">
										<?php echo wp_get_attachment_image( $estatein_image_id, 'large', false, array( 'class' => 'gallery__image', 'loading' => $estatein_index < 2 ? 'eager' : 'lazy', 'fetchpriority' => 0 === $estatein_index ? 'high' : 'auto' ) ); ?>
									</li>
								<?php endforeach; ?>
							</ul>
							<div class="gallery__nav">
								<button class="slider__arrow" type="button" data-slider-prev aria-label="<?php esc_attr_e( 'Previous photo', 'estatein' ); ?>" disabled><?php estatein_the_icon( 'arrow-left', 'icon icon--24' ); ?></button>
								<span class="gallery__dots" aria-hidden="true" data-gallery-dots>
									<?php foreach ( $estatein_images as $estatein_index => $estatein_image_id ) : ?>
										<span class="gallery__dot<?php echo 0 === $estatein_index ? ' is-active' : ''; ?>"></span>
									<?php endforeach; ?>
								</span>
								<span class="screen-reader-text"><span data-slider-current>01</span> / <span data-slider-total><?php echo esc_html( estatein_pad( count( $estatein_images ) ) ); ?></span></span>
								<button class="slider__arrow" type="button" data-slider-next aria-label="<?php esc_attr_e( 'Next photo', 'estatein' ); ?>"><?php estatein_the_icon( 'arrow-right', 'icon icon--24' ); ?></button>
							</div>
						</div>
					</div>
				<?php endif; ?>

				<div class="property-detail__info">
					<section class="info-panel" aria-labelledby="description-title">
						<h2 class="info-panel__title" id="description-title"><?php esc_html_e( 'Description', 'estatein' ); ?></h2>
						<div class="info-panel__text"><?php the_content(); ?></div>
						<dl class="facts">
							<div class="facts__item"><dt><?php estatein_the_icon( 'bed', 'icon icon--chip' ); ?><?php esc_html_e( 'Bedrooms', 'estatein' ); ?></dt><dd><?php echo esc_html( estatein_pad( (int) estatein_field( 'bedrooms', $estatein_id, 0 ) ) ); ?></dd></div>
							<div class="facts__item"><dt><?php estatein_the_icon( 'bath', 'icon icon--chip' ); ?><?php esc_html_e( 'Bathrooms', 'estatein' ); ?></dt><dd><?php echo esc_html( estatein_pad( (int) ceil( (float) estatein_field( 'bathrooms', $estatein_id, 0 ) ) ) ); ?></dd></div>
							<div class="facts__item"><dt><?php estatein_the_icon( 'feature-value', 'icon icon--chip' ); ?><?php esc_html_e( 'Area', 'estatein' ); ?></dt><dd><?php echo esc_html( number_format_i18n( (float) estatein_field( 'area', $estatein_id, 0 ) ) . ' ' . __( 'Square Feet', 'estatein' ) ); ?></dd></div>
						</dl>
					</section>
					<?php if ( $estatein_features ) : ?>
						<section class="info-panel" aria-labelledby="features-title">
							<h2 class="info-panel__title" id="features-title"><?php esc_html_e( 'Key Features and Amenities', 'estatein' ); ?></h2>
							<ul class="feature-list">
								<?php foreach ( $estatein_features as $estatein_feature ) : ?>
									<li class="feature-list__item"><?php estatein_the_icon( 'sparkle', 'icon icon--20' ); ?><?php echo esc_html( $estatein_feature ); ?></li>
								<?php endforeach; ?>
							</ul>
						</section>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<section class="section" aria-labelledby="inquiry-title">
			<div class="container split">
				<div class="split__aside">
					<?php get_template_part( 'template-parts/components/section-header', null, array( 'title' => sprintf( $estatein_inquiry['title'], get_the_title() ), 'text' => $estatein_inquiry['text'], 'id' => 'inquiry-title' ) ); ?>
				</div>
				<div class="split__main">
					<?php get_template_part( 'template-parts/components/enquiry-form', null, array( 'type' => 'inquiry', 'property_id' => $estatein_id ) ); ?>
				</div>
			</div>
		</section>

		<?php get_template_part( 'template-parts/sections/pricing', null, array( 'property_id' => $estatein_id ) ); ?>
	</article>
	<?php
endwhile;

get_template_part( 'template-parts/home/faqs' );
get_template_part( 'template-parts/home/cta' );
get_footer();

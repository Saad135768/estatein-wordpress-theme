<?php
/**
 * Home testimonials slider.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

$estatein_query = new WP_Query(
	array(
		'post_type'      => 'testimonial',
		'posts_per_page' => 12,
		'no_found_rows'  => true,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
	)
);

if ( ! $estatein_query->have_posts() ) {
	return;
}
?>
<section class="section" id="testimonials" aria-labelledby="testimonials-title">
	<div class="container">
		<?php get_template_part( 'template-parts/components/section-header', null, array( 'prefix' => 'testimonials', 'id' => 'testimonials-title' ) ); ?>

		<div class="slider" data-slider>
			<ul class="slider__track" data-slider-track tabindex="0">
				<?php
				while ( $estatein_query->have_posts() ) :
					$estatein_query->the_post();
					$estatein_rating = max( 1, min( 5, (int) estatein_field( 'rating', get_the_ID(), 5 ) ) );
					?>
					<li class="slider__item">
						<figure class="testimonial-card">
							<p class="rating" role="img" aria-label="<?php echo esc_attr( sprintf( __( 'Rated %d out of 5', 'estatein' ), $estatein_rating ) ); ?>">
								<?php for ( $estatein_i = 0; $estatein_i < $estatein_rating; $estatein_i++ ) : ?>
									<?php estatein_the_icon( 'star', 'icon rating__star' ); ?>
								<?php endfor; ?>
							</p>
							<div class="testimonial-card__text">
								<h3 class="testimonial-card__title"><?php the_title(); ?></h3>
								<blockquote class="testimonial-card__quote"><?php echo wp_kses_post( wpautop( get_the_content() ) ); ?></blockquote>
							</div>
							<figcaption class="testimonial-card__author">
								<?php
								if ( has_post_thumbnail() ) {
									the_post_thumbnail( 'estatein-avatar', array( 'class' => 'testimonial-card__avatar', 'loading' => 'lazy', 'alt' => '' ) );
								}
								?>
								<span class="testimonial-card__name"><?php echo esc_html( estatein_field( 'client_name', get_the_ID(), get_the_title() ) ); ?></span>
								<span class="testimonial-card__location"><?php echo esc_html( estatein_field( 'client_location' ) ); ?></span>
							</figcaption>
						</figure>
					</li>
				<?php endwhile; ?>
			</ul>
			<?php
			get_template_part(
				'template-parts/components/slider-controls',
				null,
				array(
					'total'  => $estatein_query->post_count,
					'label'  => __( 'testimonial', 'estatein' ),
					'button' => estatein_home( 'testimonials_button' ),
				)
			);
			?>
		</div>
	</div>
</section>
<?php
wp_reset_postdata();

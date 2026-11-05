<?php
/**
 * Home featured-properties slider.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

$estatein_query = estatein_featured_properties_query();

if ( ! $estatein_query->have_posts() ) {
	return;
}
?>
<section class="section" id="properties" aria-labelledby="properties-title">
	<div class="container">
		<?php get_template_part( 'template-parts/components/section-header', null, array( 'prefix' => 'properties', 'id' => 'properties-title' ) ); ?>

		<div class="slider" data-slider>
			<ul class="slider__track" data-slider-track tabindex="0">
				<?php
				while ( $estatein_query->have_posts() ) :
					$estatein_query->the_post();
					?>
					<li class="slider__item"><?php get_template_part( 'template-parts/components/card-property' ); ?></li>
				<?php endwhile; ?>
			</ul>
			<?php
			get_template_part(
				'template-parts/components/slider-controls',
				null,
				array(
					'total'  => $estatein_query->post_count,
					'label'  => __( 'property', 'estatein' ),
					'button' => estatein_home( 'properties_button' ),
				)
			);
			?>
		</div>
	</div>
</section>
<?php
wp_reset_postdata();

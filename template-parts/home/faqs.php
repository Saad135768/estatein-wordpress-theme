<?php
/**
 * Home FAQ slider with expandable answers.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

$estatein_query = new WP_Query(
	array(
		'post_type'      => 'faq',
		'posts_per_page' => 12,
		'no_found_rows'  => true,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
	)
);

if ( ! $estatein_query->have_posts() ) {
	return;
}

$estatein_schema = array();
?>
<section class="section" id="faqs" aria-labelledby="faqs-title">
	<div class="container">
		<?php get_template_part( 'template-parts/components/section-header', null, array( 'prefix' => 'faqs', 'id' => 'faqs-title' ) ); ?>

		<div class="slider" data-slider>
			<ul class="slider__track" data-slider-track tabindex="0">
				<?php
				while ( $estatein_query->have_posts() ) :
					$estatein_query->the_post();
					$estatein_answer_id = 'faq-answer-' . get_the_ID();
					$estatein_full      = get_the_content();
					$estatein_schema[]  = array(
						'@type'          => 'Question',
						'name'           => get_the_title(),
						'acceptedAnswer' => array(
							'@type' => 'Answer',
							'text'  => wp_strip_all_tags( $estatein_full ),
						),
					);
					?>
					<li class="slider__item">
						<article class="faq-card">
							<h3 class="faq-card__question"><?php the_title(); ?></h3>
							<p class="faq-card__summary"><?php echo esc_html( get_the_excerpt() ); ?></p>
							<?php if ( $estatein_full ) : ?>
								<div class="faq-card__answer" id="<?php echo esc_attr( $estatein_answer_id ); ?>" hidden>
									<?php echo wp_kses_post( wpautop( $estatein_full ) ); ?>
								</div>
								<button class="btn btn--secondary faq-card__toggle" type="button" aria-expanded="false" aria-controls="<?php echo esc_attr( $estatein_answer_id ); ?>" data-faq-toggle data-label-open="<?php esc_attr_e( 'Show Less', 'estatein' ); ?>">
									<?php esc_html_e( 'Read More', 'estatein' ); ?>
								</button>
							<?php endif; ?>
						</article>
					</li>
				<?php endwhile; ?>
			</ul>
			<?php
			get_template_part(
				'template-parts/components/slider-controls',
				null,
				array(
					'total'  => $estatein_query->post_count,
					'label'  => __( 'question', 'estatein' ),
					'button' => estatein_home( 'faqs_button' ),
				)
			);
			?>
		</div>
	</div>
	<script type="application/ld+json"><?php echo wp_json_encode( array( '@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $estatein_schema ), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG ); ?></script>
</section>
<?php
wp_reset_postdata();

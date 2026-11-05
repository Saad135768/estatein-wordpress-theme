<?php
/**
 * "Our Valued Clients" slider from the Client post type.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

$estatein_query = new WP_Query(
	array(
		'post_type'      => 'client',
		'posts_per_page' => 12,
		'no_found_rows'  => true,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
	)
);

if ( ! $estatein_query->have_posts() ) {
	return;
}

$estatein_header = estatein_content( 'about_clients' );
?>
<section class="section" id="clients" aria-labelledby="clients-title">
	<div class="container">
		<?php get_template_part( 'template-parts/components/section-header', null, $estatein_header + array( 'id' => 'clients-title' ) ); ?>
		<div class="slider slider--2 slider--framed" data-slider>
			<ul class="slider__track" data-slider-track tabindex="0">
				<?php
				while ( $estatein_query->have_posts() ) :
					$estatein_query->the_post();
					$estatein_id = get_the_ID();
					?>
					<li class="slider__item">
						<article class="client-card">
							<header class="client-card__header">
								<div>
									<p class="client-card__since"><?php echo esc_html( sprintf( __( 'Since %s', 'estatein' ), estatein_field( 'since', $estatein_id ) ) ); ?></p>
									<h3 class="client-card__name"><?php the_title(); ?></h3>
								</div>
								<a class="btn btn--secondary client-card__visit" href="<?php echo esc_url( estatein_field( 'website', $estatein_id, '#' ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Visit Website', 'estatein' ); ?><span class="screen-reader-text"> <?php the_title(); ?></span></a>
							</header>
							<dl class="client-card__meta">
								<div>
									<dt><?php estatein_the_icon( 'building', 'icon icon--20' ); ?><?php esc_html_e( 'Domain', 'estatein' ); ?></dt>
									<dd><?php echo esc_html( estatein_field( 'domain', $estatein_id ) ); ?></dd>
								</div>
								<div>
									<dt><?php estatein_the_icon( 'pie', 'icon icon--20' ); ?><?php esc_html_e( 'Category', 'estatein' ); ?></dt>
									<dd><?php echo esc_html( estatein_field( 'category', $estatein_id ) ); ?></dd>
								</div>
							</dl>
							<blockquote class="client-card__quote">
								<p class="client-card__quote-label"><?php esc_html_e( 'What They Said 🤩', 'estatein' ); ?></p>
								<?php echo wp_kses_post( wpautop( get_the_content() ) ); ?>
							</blockquote>
						</article>
					</li>
				<?php endwhile; ?>
			</ul>
			<?php get_template_part( 'template-parts/components/slider-controls', null, array( 'total' => $estatein_query->post_count, 'label' => __( 'client', 'estatein' ), 'button' => array() ) ); ?>
		</div>
	</div>
</section>
<?php
wp_reset_postdata();

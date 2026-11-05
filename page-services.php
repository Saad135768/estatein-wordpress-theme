<?php
/**
 * Template Name: Services
 *
 * Services page: hero, feature strip and one block per service group.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

get_header();

get_template_part( 'template-parts/components/page-hero', null, array( 'key' => 'services_hero' ) );
get_template_part( 'template-parts/home/features' );

foreach ( estatein_content( 'services_groups' ) as $estatein_group => $estatein_config ) :
	$estatein_query = new WP_Query(
		array(
			'post_type'      => 'service',
			'posts_per_page' => 8,
			'no_found_rows'  => true,
			'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
			'meta_query'     => array(
				array(
					'key'   => 'service_group',
					'value' => $estatein_group,
				),
			),
		)
	);

	if ( ! $estatein_query->have_posts() ) {
		continue;
	}

	$estatein_heading_id = 'services-' . $estatein_group;
	$estatein_split      = 'split' === $estatein_config['layout'];
	?>
	<section class="section services services--<?php echo esc_attr( $estatein_config['layout'] ); ?>" id="<?php echo esc_attr( $estatein_group ); ?>" aria-labelledby="<?php echo esc_attr( $estatein_heading_id ); ?>">
		<div class="container<?php echo $estatein_split ? ' split' : ''; ?>">
			<div class="<?php echo $estatein_split ? 'split__aside' : ''; ?>">
				<?php get_template_part( 'template-parts/components/section-header', null, array( 'title' => $estatein_config['title'], 'text' => $estatein_config['text'], 'id' => $estatein_heading_id ) ); ?>
				<?php if ( $estatein_split ) : ?>
					<div class="cta-card cta-card--stacked">
						<h3 class="cta-card__title"><?php echo esc_html( $estatein_config['cta'] ); ?></h3>
						<p class="cta-card__text"><?php echo esc_html( $estatein_config['cta_text'] ); ?></p>
						<a class="btn btn--secondary" href="<?php echo esc_url( estatein_contact_url() ); ?>"><?php esc_html_e( 'Learn More', 'estatein' ); ?></a>
					</div>
				<?php endif; ?>
			</div>

			<ul class="<?php echo $estatein_split ? 'split__main service-grid service-grid--2' : 'service-grid'; ?>">
				<?php
				while ( $estatein_query->have_posts() ) :
					$estatein_query->the_post();
					?>
					<li class="service-card">
						<div class="service-card__head">
							<span class="icon-badge"><?php estatein_the_icon( estatein_field( 'service_icon', get_the_ID(), 'sparkle' ), 'icon icon--24' ); ?></span>
							<h3 class="service-card__title"><?php the_title(); ?></h3>
						</div>
						<p class="service-card__text"><?php echo esc_html( get_the_excerpt() ); ?></p>
					</li>
				<?php endwhile; ?>
				<?php if ( ! $estatein_split ) : ?>
					<li class="cta-card">
						<div class="cta-card__row">
							<h3 class="cta-card__title"><?php echo esc_html( $estatein_config['cta'] ); ?></h3>
							<a class="btn btn--secondary" href="<?php echo esc_url( estatein_contact_url() ); ?>"><?php esc_html_e( 'Learn More', 'estatein' ); ?></a>
						</div>
						<p class="cta-card__text"><?php echo esc_html( $estatein_config['cta_text'] ); ?></p>
					</li>
				<?php endif; ?>
			</ul>
		</div>
	</section>
	<?php
	wp_reset_postdata();
endforeach;

get_template_part( 'template-parts/home/cta' );
get_footer();

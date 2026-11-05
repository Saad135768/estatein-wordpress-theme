<?php
/**
 * "Meet the Estatein Team" grid from the Team Member post type.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

$estatein_query = new WP_Query(
	array(
		'post_type'      => 'team_member',
		'posts_per_page' => 8,
		'no_found_rows'  => true,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
	)
);

if ( ! $estatein_query->have_posts() ) {
	return;
}

$estatein_header = estatein_content( 'about_team' );
?>
<section class="section" id="team" aria-labelledby="team-title">
	<div class="container">
		<?php get_template_part( 'template-parts/components/section-header', null, $estatein_header + array( 'id' => 'team-title' ) ); ?>
		<ul class="card-grid card-grid--4">
			<?php
			while ( $estatein_query->have_posts() ) :
				$estatein_query->the_post();
				$estatein_twitter = estatein_field( 'twitter_url', get_the_ID(), '#' );
				?>
				<li class="team-card">
					<div class="team-card__media">
						<?php the_post_thumbnail( 'estatein-card', array( 'class' => 'team-card__image', 'loading' => 'lazy', 'alt' => get_the_title() ) ); ?>
						<a class="team-card__social" href="<?php echo esc_url( $estatein_twitter ); ?>" aria-label="<?php echo esc_attr( sprintf( __( '%s on Twitter', 'estatein' ), get_the_title() ) ); ?>"><?php estatein_the_icon( 'twitter', 'icon icon--24' ); ?></a>
					</div>
					<div class="team-card__body">
						<h3 class="team-card__name"><?php the_title(); ?></h3>
						<p class="team-card__role"><?php echo esc_html( estatein_field( 'role' ) ); ?></p>
					</div>
					<a class="team-card__hello" href="<?php echo esc_url( add_query_arg( 'agent', rawurlencode( get_the_title() ), estatein_contact_url() ) ); ?>#enquiry-contact">
						<span><?php esc_html_e( 'Say Hello 👋', 'estatein' ); ?><span class="screen-reader-text"> <?php the_title(); ?></span></span>
						<span class="team-card__send"><?php estatein_the_icon( 'send', 'icon icon--18' ); ?></span>
					</a>
				</li>
			<?php endwhile; ?>
		</ul>
	</div>
</section>
<?php
wp_reset_postdata();

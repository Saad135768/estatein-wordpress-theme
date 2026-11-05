<?php
/**
 * Template Name: Contact
 *
 * Contact page: hero with contact cards, form, office locations and gallery.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

get_header();

$estatein_email   = (string) get_theme_mod( 'estatein_contact_email', 'info@estatein.com' );
$estatein_phone   = (string) get_theme_mod( 'estatein_contact_phone', '+1 (123) 456-7890' );
$estatein_form    = estatein_content( 'contact_form' );
$estatein_offices = estatein_content( 'contact_offices' );
$estatein_gallery = estatein_content( 'contact_gallery' );
$estatein_socials = estatein_social_links();

get_template_part( 'template-parts/components/page-hero', null, array( 'key' => 'contact_hero' ) );
?>
<section class="features" aria-label="<?php esc_attr_e( 'Ways to reach us', 'estatein' ); ?>">
	<ul class="features__list">
		<li class="feature-card">
			<a class="feature-card__link" href="<?php echo esc_url( 'mailto:' . $estatein_email ); ?>">
				<span class="feature-card__icon"><span class="feature-card__icon-inner"><?php estatein_the_icon( 'envelope', 'icon icon--34' ); ?></span></span>
				<span class="feature-card__title"><?php echo esc_html( antispambot( $estatein_email ) ); ?></span>
				<?php estatein_the_icon( 'arrow-up-right', 'icon icon--34 feature-card__arrow' ); ?>
			</a>
		</li>
		<li class="feature-card">
			<a class="feature-card__link" href="<?php echo esc_url( 'tel:' . preg_replace( '/[^\d+]/', '', $estatein_phone ) ); ?>">
				<span class="feature-card__icon"><span class="feature-card__icon-inner"><?php estatein_the_icon( 'phone', 'icon icon--34' ); ?></span></span>
				<span class="feature-card__title"><?php echo esc_html( $estatein_phone ); ?></span>
				<?php estatein_the_icon( 'arrow-up-right', 'icon icon--34 feature-card__arrow' ); ?>
			</a>
		</li>
		<li class="feature-card">
			<a class="feature-card__link" href="#offices">
				<span class="feature-card__icon"><span class="feature-card__icon-inner"><?php estatein_the_icon( 'pin', 'icon icon--34' ); ?></span></span>
				<span class="feature-card__title"><?php esc_html_e( 'Main Headquarters', 'estatein' ); ?></span>
				<?php estatein_the_icon( 'arrow-up-right', 'icon icon--34 feature-card__arrow' ); ?>
			</a>
		</li>
		<li class="feature-card">
			<div class="feature-card__link feature-card__link--static">
				<span class="feature-card__icon"><span class="feature-card__icon-inner"><?php estatein_the_icon( 'chat', 'icon icon--34' ); ?></span></span>
				<span class="feature-card__socials">
					<?php foreach ( $estatein_socials as $estatein_network => $estatein_url ) : ?>
						<a href="<?php echo esc_url( $estatein_url ); ?>"><?php echo esc_html( ucfirst( $estatein_network ) ); ?></a>
					<?php endforeach; ?>
				</span>
			</div>
		</li>
	</ul>
</section>

<section class="section" aria-labelledby="connect-title">
	<div class="container">
		<?php get_template_part( 'template-parts/components/section-header', null, $estatein_form + array( 'id' => 'connect-title' ) ); ?>
		<?php get_template_part( 'template-parts/components/enquiry-form', null, array( 'type' => 'contact' ) ); ?>
	</div>
</section>

<?php
$estatein_query = new WP_Query(
	array(
		'post_type'      => 'office',
		'posts_per_page' => 12,
		'no_found_rows'  => true,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
	)
);
if ( $estatein_query->have_posts() ) :
	?>
	<section class="section" id="offices" aria-labelledby="offices-title">
		<div class="container">
			<?php get_template_part( 'template-parts/components/section-header', null, $estatein_offices + array( 'id' => 'offices-title' ) ); ?>
			<div class="offices" data-tabs>
				<div class="tabs" role="tablist" aria-label="<?php esc_attr_e( 'Filter offices', 'estatein' ); ?>">
					<?php
					$estatein_tabs = array(
						'all'           => __( 'All', 'estatein' ),
						'regional'      => __( 'Regional', 'estatein' ),
						'international' => __( 'International', 'estatein' ),
					);
					foreach ( $estatein_tabs as $estatein_tab => $estatein_label ) :
						?>
						<button class="tabs__tab" type="button" role="tab" data-tab="<?php echo esc_attr( $estatein_tab ); ?>" aria-selected="<?php echo 'all' === $estatein_tab ? 'true' : 'false'; ?>"><?php echo esc_html( $estatein_label ); ?></button>
					<?php endforeach; ?>
				</div>
				<div role="tabpanel"><ul class="card-grid card-grid--2 offices__list">
					<?php
					while ( $estatein_query->have_posts() ) :
						$estatein_query->the_post();
						$estatein_oid    = get_the_ID();
						$estatein_omail  = (string) estatein_field( 'office_email', $estatein_oid, $estatein_email );
						$estatein_ophone = (string) estatein_field( 'office_phone', $estatein_oid, $estatein_phone );
						?>
						<li class="office-card" data-region="<?php echo esc_attr( estatein_field( 'office_region', $estatein_oid, 'regional' ) ); ?>">
							<p class="office-card__label"><?php echo esc_html( estatein_field( 'office_label', $estatein_oid ) ); ?></p>
							<h3 class="office-card__address"><?php the_title(); ?></h3>
							<div class="office-card__text"><?php echo wp_kses_post( wpautop( get_the_content() ) ); ?></div>
							<ul class="chips">
								<li class="chip"><?php estatein_the_icon( 'envelope', 'icon icon--chip' ); ?><a href="<?php echo esc_url( 'mailto:' . $estatein_omail ); ?>"><?php echo esc_html( antispambot( $estatein_omail ) ); ?></a></li>
								<li class="chip"><?php estatein_the_icon( 'phone', 'icon icon--chip' ); ?><a href="<?php echo esc_url( 'tel:' . preg_replace( '/[^\d+]/', '', $estatein_ophone ) ); ?>"><?php echo esc_html( $estatein_ophone ); ?></a></li>
								<li class="chip"><?php estatein_the_icon( 'pin', 'icon icon--chip' ); ?><?php echo esc_html( estatein_field( 'office_city', $estatein_oid ) ); ?></li>
							</ul>
							<a class="btn btn--primary office-card__cta" href="<?php echo esc_url( estatein_field( 'office_map', $estatein_oid, 'https://maps.google.com/?q=' . rawurlencode( get_the_title() ) ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Get Direction', 'estatein' ); ?><span class="screen-reader-text"> <?php the_title(); ?></span></a>
						</li>
					<?php endwhile; ?>
				</ul></div>
			</div>
		</div>
	</section>
	<?php
	wp_reset_postdata();
endif;
?>

<section class="section" aria-labelledby="gallery-title">
	<div class="container">
		<div class="office-gallery">
			<?php foreach ( $estatein_gallery['images'] as $estatein_index => $estatein_image ) : ?>
				<img class="office-gallery__image office-gallery__image--<?php echo esc_attr( $estatein_index + 1 ); ?>" src="<?php echo esc_url( estatein_image_url( $estatein_image[0] ) ); ?>" alt="<?php echo esc_attr( $estatein_image[1] ); ?>" loading="lazy" decoding="async">
			<?php endforeach; ?>
			<div class="office-gallery__text">
				<?php get_template_part( 'template-parts/components/section-header', null, array( 'title' => $estatein_gallery['title'], 'text' => $estatein_gallery['text'], 'id' => 'gallery-title' ) ); ?>
			</div>
		</div>
	</div>
</section>
<?php
get_template_part( 'template-parts/home/cta' );
get_footer();

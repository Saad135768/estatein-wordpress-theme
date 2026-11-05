<?php
/**
 * Properties archive: grid of property cards.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="section section--first" aria-labelledby="archive-title">
	<div class="container">
		<div class="section-header">
			<div class="section-header__text">
				<img class="section-header__sparkles" src="<?php echo esc_url( ESTATEIN_URI . '/assets/icons/sparkles.svg' ); ?>" width="68" height="30" alt="" aria-hidden="true">
				<h1 class="section-header__title" id="archive-title"><?php post_type_archive_title(); ?></h1>
				<p class="section-header__desc"><?php echo esc_html( estatein_home( 'properties_text' ) ); ?></p>
			</div>
		</div>

		<?php if ( have_posts() ) : ?>
			<ul class="property-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<li><?php get_template_part( 'template-parts/components/card-property' ); ?></li>
				<?php endwhile; ?>
			</ul>
			<?php the_posts_pagination( array( 'class' => 'pagination' ) ); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'No properties are listed yet.', 'estatein' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();

<?php
/**
 * Fallback template for posts, pages and archives.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="container page-content">
	<?php if ( have_posts() ) : ?>
		<?php if ( ! is_singular() ) : ?>
			<h1 class="page-content__title"><?php echo esc_html( wp_strip_all_tags( get_the_archive_title() ) ); ?></h1>
		<?php endif; ?>

		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class( 'entry' ); ?>>
				<?php if ( is_singular() ) : ?>
					<h1 class="page-content__title"><?php the_title(); ?></h1>
					<div class="entry__content"><?php the_content(); ?></div>
				<?php else : ?>
					<h2 class="entry__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<p class="entry__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
			</article>
		<?php endwhile; ?>

		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<h1 class="page-content__title"><?php esc_html_e( 'Nothing found', 'estatein' ); ?></h1>
		<p><?php esc_html_e( 'The page you are looking for does not exist.', 'estatein' ); ?></p>
		<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to Home', 'estatein' ); ?></a>
	<?php endif; ?>
</div>
<?php
get_footer();

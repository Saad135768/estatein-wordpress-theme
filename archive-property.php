<?php
/**
 * Properties page: hero with search and filters, results slider and request form.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

get_header();

$estatein_hero    = estatein_page_hero( 'properties_hero' );
$estatein_values  = estatein_property_filter_values();
$estatein_list    = estatein_content( 'properties_list' );
$estatein_form    = estatein_content( 'properties_form' );
$estatein_types   = get_terms( array( 'taxonomy' => 'property_type', 'hide_empty' => true ) );
$estatein_filters = estatein_property_range_filters();
$estatein_action  = get_post_type_archive_link( 'property' );
?>
<section class="page-hero page-hero--search" aria-labelledby="page-hero-title">
	<div class="container page-hero__inner">
		<h1 class="page-hero__title" id="page-hero-title"><?php echo esc_html( $estatein_hero['title'] ); ?></h1>
		<p class="page-hero__text"><?php echo esc_html( $estatein_hero['text'] ); ?></p>
	</div>
</section>

<div class="container">
	<form class="property-search" role="search" method="get" action="<?php echo esc_url( $estatein_action ); ?>#results">
		<div class="property-search__bar">
			<label class="screen-reader-text" for="property-keyword"><?php esc_html_e( 'Search for a property', 'estatein' ); ?></label>
			<input class="property-search__input" id="property-keyword" type="search" name="keyword" value="<?php echo esc_attr( $estatein_values['keyword'] ); ?>" placeholder="<?php esc_attr_e( 'Search For A Property', 'estatein' ); ?>">
			<button class="btn btn--primary property-search__submit" type="submit"><?php estatein_the_icon( 'search', 'icon icon--20' ); ?><?php esc_html_e( 'Find Property', 'estatein' ); ?></button>
		</div>
		<div class="property-search__filters">
			<div class="filter">
				<label class="screen-reader-text" for="filter-location"><?php esc_html_e( 'Location', 'estatein' ); ?></label>
				<?php estatein_the_icon( 'pin', 'icon icon--20 filter__icon' ); ?>
				<select class="filter__select" id="filter-location" name="location" data-autosubmit>
					<option value=""><?php esc_html_e( 'Location', 'estatein' ); ?></option>
					<?php foreach ( estatein_property_locations() as $estatein_location ) : ?>
						<option value="<?php echo esc_attr( $estatein_location ); ?>" <?php selected( $estatein_values['location'], $estatein_location ); ?>><?php echo esc_html( $estatein_location ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="filter">
				<label class="screen-reader-text" for="filter-type"><?php esc_html_e( 'Property Type', 'estatein' ); ?></label>
				<?php estatein_the_icon( 'building', 'icon icon--20 filter__icon' ); ?>
				<select class="filter__select" id="filter-type" name="ptype" data-autosubmit>
					<option value=""><?php esc_html_e( 'Property Type', 'estatein' ); ?></option>
					<?php if ( ! is_wp_error( $estatein_types ) ) : ?>
						<?php foreach ( $estatein_types as $estatein_term ) : ?>
							<option value="<?php echo esc_attr( $estatein_term->slug ); ?>" <?php selected( $estatein_values['ptype'], $estatein_term->slug ); ?>><?php echo esc_html( $estatein_term->name ); ?></option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</div>
			<?php
			$estatein_filter_icons = array( 'price' => 'wallet', 'size' => 'feature-value', 'year' => 'chart' );
			foreach ( $estatein_filters as $estatein_key => $estatein_filter ) :
				?>
				<div class="filter">
					<label class="screen-reader-text" for="filter-<?php echo esc_attr( $estatein_key ); ?>"><?php echo esc_html( $estatein_filter[0] ); ?></label>
					<?php estatein_the_icon( $estatein_filter_icons[ $estatein_key ], 'icon icon--20 filter__icon' ); ?>
					<select class="filter__select" id="filter-<?php echo esc_attr( $estatein_key ); ?>" name="<?php echo esc_attr( $estatein_key ); ?>" data-autosubmit>
						<option value=""><?php echo esc_html( $estatein_filter[0] ); ?></option>
						<?php foreach ( $estatein_filter[2] as $estatein_value => $estatein_label ) : ?>
							<option value="<?php echo esc_attr( $estatein_value ); ?>" <?php selected( $estatein_values[ $estatein_key ], $estatein_value ); ?>><?php echo esc_html( $estatein_label ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			<?php endforeach; ?>
		</div>
	</form>
</div>

<section class="section" id="results" aria-labelledby="results-title">
	<div class="container">
		<?php
		$estatein_text = $estatein_list['text'];
		if ( estatein_property_filters_active() ) {
			global $wp_query;
			$estatein_text = sprintf( _n( '%d property matches your search.', '%d properties match your search.', (int) $wp_query->found_posts, 'estatein' ), (int) $wp_query->found_posts );
		}
		get_template_part( 'template-parts/components/section-header', null, array( 'title' => $estatein_list['title'], 'text' => $estatein_text, 'id' => 'results-title' ) );
		?>

		<?php if ( have_posts() ) : ?>
			<div class="slider" data-slider>
				<ul class="slider__track" data-slider-track tabindex="0">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<li class="slider__item"><?php get_template_part( 'template-parts/components/card-property', null, array( 'variant' => 'listing' ) ); ?></li>
					<?php endwhile; ?>
				</ul>
				<?php
				global $wp_query;
				get_template_part( 'template-parts/components/slider-controls', null, array( 'total' => $wp_query->post_count, 'label' => __( 'property', 'estatein' ), 'button' => array() ) );
				?>
			</div>
		<?php else : ?>
			<div class="empty-state">
				<p><?php esc_html_e( 'No properties match these filters yet.', 'estatein' ); ?></p>
				<a class="btn btn--secondary" href="<?php echo esc_url( $estatein_action ); ?>"><?php esc_html_e( 'Clear filters', 'estatein' ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</section>

<section class="section" aria-labelledby="request-title">
	<div class="container">
		<?php get_template_part( 'template-parts/components/section-header', null, $estatein_form + array( 'id' => 'request-title' ) ); ?>
		<?php get_template_part( 'template-parts/components/enquiry-form', null, array( 'type' => 'property' ) ); ?>
	</div>
</section>
<?php
get_template_part( 'template-parts/home/cta' );
get_footer();

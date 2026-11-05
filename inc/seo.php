<?php
/**
 * Lightweight SEO: meta description, Open Graph tags and JSON-LD.
 *
 * Skipped automatically when Yoast SEO or Rank Math is active.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether a dedicated SEO plugin is handling meta output.
 *
 * @return bool
 */
function estatein_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' );
}

/**
 * Builds the meta description for the current request.
 *
 * @return string
 */
function estatein_meta_description() {
	if ( is_front_page() ) {
		return wp_strip_all_tags( (string) estatein_home( 'hero_text' ) );
	}

	if ( is_page() ) {
		$keys = array(
			'about-us' => 'about_journey',
			'services' => 'services_hero',
			'contact'  => 'contact_hero',
		);
		$slug = get_post_field( 'post_name', get_queried_object_id() );
		if ( isset( $keys[ $slug ] ) ) {
			return wp_strip_all_tags( estatein_page_hero( $keys[ $slug ] )['text'] );
		}
	}

	if ( is_singular() ) {
		$post = get_queried_object();
		$text = has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_trim_words( wp_strip_all_tags( $post->post_content ), 30, '…' );
		if ( $text ) {
			return $text;
		}
	}

	if ( is_post_type_archive( 'property' ) ) {
		return wp_strip_all_tags( (string) estatein_home( 'properties_text' ) );
	}

	return get_bloginfo( 'description' );
}

/**
 * Prints the meta description, Open Graph and Twitter card tags.
 */
function estatein_meta_tags() {
	if ( estatein_seo_plugin_active() ) {
		return;
	}

	$description = estatein_meta_description();
	$title       = wp_get_document_title();
	$url         = is_singular() ? get_permalink() : home_url( add_query_arg( array() ) );
	$image       = is_singular() && has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'large' ) : estatein_hero_image()['src'];

	if ( $description ) {
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );
	}

	$tags = array(
		'og:type'        => is_singular( 'property' ) ? 'article' : 'website',
		'og:site_name'   => get_bloginfo( 'name' ),
		'og:title'       => $title,
		'og:description' => $description,
		'og:url'         => $url,
		'og:image'       => $image,
	);

	foreach ( array_filter( $tags ) as $property => $content ) {
		printf( '<meta property="%1$s" content="%2$s">' . "\n", esc_attr( $property ), esc_attr( $content ) );
	}

	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	printf( '<meta name="theme-color" content="%s">' . "\n", '#141414' );
}
add_action( 'wp_head', 'estatein_meta_tags', 2 );

/**
 * Prints JSON-LD structured data for the business and, on properties, the listing.
 */
function estatein_schema() {
	if ( estatein_seo_plugin_active() ) {
		return;
	}

	$graph = array(
		array(
			'@type'       => 'RealEstateAgent',
			'@id'         => home_url( '/#organization' ),
			'name'        => get_bloginfo( 'name' ),
			'url'         => home_url( '/' ),
			'description' => get_bloginfo( 'description' ),
			'image'       => estatein_hero_image()['src'],
			'sameAs'      => array_values( array_filter( estatein_social_links(), static fn( $url ) => '#' !== $url ) ),
		),
	);

	if ( is_singular( 'property' ) ) {
		$post_id = get_queried_object_id();
		$price   = estatein_field( 'price', $post_id );
		$listing = array(
			'@type'       => 'RealEstateListing',
			'name'        => get_the_title( $post_id ),
			'url'         => get_permalink( $post_id ),
			'description' => estatein_meta_description(),
			'image'       => get_the_post_thumbnail_url( $post_id, 'large' ),
		);
		if ( '' !== $price ) {
			$listing['offers'] = array(
				'@type'         => 'Offer',
				'price'         => (float) $price,
				'priceCurrency' => 'USD',
			);
		}
		$graph[] = array_filter( $listing );
	}

	printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		wp_json_encode( array( '@context' => 'https://schema.org', '@graph' => $graph ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG )
	);
}
add_action( 'wp_head', 'estatein_schema', 20 );

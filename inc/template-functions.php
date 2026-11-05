<?php
/**
 * Template helpers, design defaults and Customizer settings.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

/**
 * Default content mirrored from the Figma design, used when a field is empty.
 *
 * @return array<string, mixed>
 */
function estatein_defaults() {
	static $defaults = null;

	if ( null === $defaults ) {
		$defaults = array(
			'hero_title'            => __( 'Discover Your Dream Property with Estatein', 'estatein' ),
			'hero_text'             => __( 'Your journey to finding the perfect property begins here. Explore our listings to find the home that matches your dreams.', 'estatein' ),
			'hero_badge_text'       => __( 'Discover Your Dream Property', 'estatein' ),
			'hero_secondary_button' => array( 'title' => __( 'Learn More', 'estatein' ), 'url' => '#features' ),
			'hero_primary_button'   => array( 'title' => __( 'Browse Properties', 'estatein' ), 'url' => get_post_type_archive_link( 'property' ) ),
			'hero_stats'            => array(
				array( 'value' => '200+', 'label' => __( 'Happy Customers', 'estatein' ) ),
				array( 'value' => '10k+', 'label' => __( 'Properties For Clients', 'estatein' ) ),
				array( 'value' => '16+', 'label' => __( 'Years of Experience', 'estatein' ) ),
			),
			'features'              => array(
				array( 'title' => __( 'Find Your Dream Home', 'estatein' ), 'icon' => 'feature-home' ),
				array( 'title' => __( 'Unlock Property Value', 'estatein' ), 'icon' => 'feature-value' ),
				array( 'title' => __( 'Effortless Property Management', 'estatein' ), 'icon' => 'feature-management' ),
				array( 'title' => __( 'Smart Investments, Informed Decisions', 'estatein' ), 'icon' => 'feature-investments' ),
			),
			'properties_heading'    => __( 'Featured Properties', 'estatein' ),
			'properties_text'       => __( 'Explore our handpicked selection of featured properties. Each listing offers a glimpse into exceptional homes and investments available through Estatein. Click "View Details" for more information.', 'estatein' ),
			'properties_button'     => array( 'title' => __( 'View All Properties', 'estatein' ), 'url' => get_post_type_archive_link( 'property' ) ),
			'testimonials_heading'  => __( 'What Our Clients Say', 'estatein' ),
			'testimonials_text'     => __( 'Read the success stories and heartfelt testimonials from our valued clients. Discover why they chose Estatein for their real estate needs.', 'estatein' ),
			'testimonials_button'   => array( 'title' => __( 'View All Testimonials', 'estatein' ), 'url' => '#testimonials' ),
			'faqs_heading'          => __( 'Frequently Asked Questions', 'estatein' ),
			'faqs_text'             => __( "Find answers to common questions about Estatein's services, property listings, and the real estate process. We're here to provide clarity and assist you every step of the way.", 'estatein' ),
			'faqs_button'           => array( 'title' => __( 'View All FAQ’s', 'estatein' ), 'url' => '#faqs' ),
			'cta_title'             => __( 'Start Your Real Estate Journey Today', 'estatein' ),
			'cta_text'              => __( "Your dream property is just a click away. Whether you're looking for a new home, a strategic investment, or expert real estate advice, Estatein is here to assist you every step of the way. Take the first step towards your real estate goals and explore our available properties or get in touch with our team for personalized assistance.", 'estatein' ),
			'cta_button'            => array( 'title' => __( 'Explore Properties', 'estatein' ), 'url' => get_post_type_archive_link( 'property' ) ),
		);
	}

	return $defaults;
}

/**
 * Reads an ACF field with a post-meta fallback when ACF is inactive.
 *
 * @param string   $name    Field name.
 * @param int|bool $post_id Post ID, defaults to the current post.
 * @param mixed    $fallback Value returned when the field is empty.
 * @return mixed
 */
function estatein_field( $name, $post_id = false, $fallback = '' ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$value   = function_exists( 'get_field' ) ? get_field( $name, $post_id ) : get_post_meta( $post_id, $name, true );

	if ( null === $value || '' === $value || false === $value || array() === $value ) {
		return $fallback;
	}

	return $value;
}

/**
 * Reads a Home page field, falling back to the design default.
 *
 * @param string $name Field name.
 * @return mixed
 */
function estatein_home( $name ) {
	$defaults = estatein_defaults();
	$fallback = $defaults[ $name ] ?? '';
	$front_id = (int) get_option( 'page_on_front' );

	if ( ! $front_id ) {
		return $fallback;
	}

	return estatein_field( $name, $front_id, $fallback );
}

/**
 * Returns the hero stats, merging saved values over the defaults.
 *
 * @return array[]
 */
function estatein_hero_stats() {
	$defaults = estatein_defaults()['hero_stats'];
	$stats    = array();

	foreach ( $defaults as $index => $default ) {
		$saved   = estatein_home( 'hero_stat_' . ( $index + 1 ) );
		$saved   = is_array( $saved ) ? array_filter( $saved ) : array();
		$stats[] = array_merge( $default, $saved );
	}

	return $stats;
}

/**
 * Returns the four feature cards, merging saved values over the defaults.
 *
 * @return array[]
 */
function estatein_features() {
	$defaults = estatein_defaults()['features'];
	$features = array();

	foreach ( $defaults as $index => $default ) {
		$saved      = estatein_home( 'feature_' . ( $index + 1 ) );
		$saved      = is_array( $saved ) ? array_filter( $saved ) : array();
		$features[] = array_merge( array( 'link' => '' ), $default, $saved );
	}

	return $features;
}

/**
 * Returns the hero image source data, preferring the ACF image.
 *
 * @return array{src:string,srcset:string,sizes:string,width:int,height:int,alt:string}
 */
function estatein_hero_image() {
	$image_id = (int) estatein_home( 'hero_image' );

	if ( $image_id ) {
		$image = wp_get_attachment_image_src( $image_id, 'estatein-hero' );
		if ( $image ) {
			return array(
				'src'    => $image[0],
				'srcset' => (string) wp_get_attachment_image_srcset( $image_id, 'estatein-hero' ),
				'sizes'  => '(min-width: 1024px) 50vw, 100vw',
				'width'  => (int) $image[1],
				'height' => (int) $image[2],
				'alt'    => (string) get_post_meta( $image_id, '_wp_attachment_image_alt', true ),
			);
		}
	}

	return array(
		'src'    => ESTATEIN_URI . '/assets/images/hero-building.webp',
		'srcset' => '',
		'sizes'  => '',
		'width'  => 920,
		'height' => 814,
		'alt'    => __( 'Modern glass high-rise buildings at dusk', 'estatein' ),
	);
}

/**
 * Returns the inline SVG markup for a theme icon.
 *
 * @param string $name  Icon file name without extension.
 * @param string $class Optional CSS class added to the svg element.
 * @return string
 */
function estatein_icon( $name, $class = '' ) {
	static $cache = array();

	$name = sanitize_file_name( $name );

	if ( ! isset( $cache[ $name ] ) ) {
		$file           = ESTATEIN_DIR . '/assets/icons/' . $name . '.svg';
		$cache[ $name ] = file_exists( $file ) ? trim( (string) file_get_contents( $file ) ) : '';
	}

	$svg = $cache[ $name ];

	if ( $svg && $class ) {
		$svg = preg_replace( '/^<svg /', '<svg class="' . esc_attr( $class ) . '" focusable="false" ', $svg, 1 );
	}

	return $svg;
}

/**
 * Echoes an icon; markup comes from theme-owned SVG files.
 *
 * @param string $name  Icon file name without extension.
 * @param string $class Optional CSS class.
 */
function estatein_the_icon( $name, $class = '' ) {
	echo wp_kses( estatein_icon( $name, $class ), estatein_svg_allowed_html() );
}

/**
 * Allowed SVG markup for wp_kses when printing theme icons.
 *
 * @return array<string, array<string, bool>>
 */
function estatein_svg_allowed_html() {
	$common = array(
		'fill'            => true,
		'fill-rule'       => true,
		'clip-rule'       => true,
		'stroke'          => true,
		'stroke-width'    => true,
		'stroke-linecap'  => true,
		'stroke-linejoin' => true,
		'opacity'         => true,
		'clip-path'       => true,
	);

	return array(
		'svg'      => array_merge( $common, array( 'xmlns' => true, 'width' => true, 'height' => true, 'viewbox' => true, 'class' => true, 'aria-hidden' => true, 'focusable' => true, 'x' => true, 'y' => true ) ),
		'g'        => $common,
		'path'     => array_merge( $common, array( 'd' => true ) ),
		'circle'   => array_merge( $common, array( 'cx' => true, 'cy' => true, 'r' => true ) ),
		'rect'     => array_merge( $common, array( 'x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true ) ),
		'defs'     => array(),
		'clippath' => array( 'id' => true ),
	);
}

/**
 * Renders a button-styled link from an ACF link array.
 *
 * @param array|string $link    ACF link array.
 * @param string       $variant One of primary, secondary, ghost.
 * @param string       $class   Extra classes.
 */
function estatein_button( $link, $variant = 'secondary', $class = '' ) {
	if ( empty( $link['url'] ) || empty( $link['title'] ) ) {
		return;
	}

	printf(
		'<a class="%1$s" href="%2$s"%3$s>%4$s</a>',
		esc_attr( trim( 'btn btn--' . $variant . ' ' . $class ) ),
		esc_url( $link['url'] ),
		! empty( $link['target'] ) ? ' target="' . esc_attr( $link['target'] ) . '" rel="noopener"' : '',
		esc_html( $link['title'] )
	);
}

/**
 * Formats a numeric price as a USD string.
 *
 * @param mixed $price Raw price.
 * @return string
 */
function estatein_format_price( $price ) {
	if ( '' === $price || null === $price ) {
		return __( 'On request', 'estatein' );
	}
	return '$' . number_format_i18n( (float) $price );
}

/**
 * Returns the chips (bedrooms, bathrooms, type) for a property card.
 *
 * @param int $post_id Property ID.
 * @return array[]
 */
function estatein_property_chips( $post_id ) {
	$chips    = array();
	$bedrooms = estatein_field( 'bedrooms', $post_id );
	$baths    = estatein_field( 'bathrooms', $post_id );
	$types    = get_the_terms( $post_id, 'property_type' );

	if ( '' !== $bedrooms ) {
		$chips[] = array(
			'icon'  => 'bed',
			'label' => sprintf( '%s-%s', number_format_i18n( (float) $bedrooms ), __( 'Bedroom', 'estatein' ) ),
		);
	}
	if ( '' !== $baths ) {
		$chips[] = array(
			'icon'  => 'bath',
			'label' => sprintf( '%s-%s', (string) (float) $baths, __( 'Bathroom', 'estatein' ) ),
		);
	}
	if ( $types && ! is_wp_error( $types ) ) {
		$chips[] = array(
			'icon'  => 'building',
			'label' => $types[0]->name,
		);
	}

	return $chips;
}

/**
 * Builds the featured-properties query from the Home page settings.
 *
 * @return WP_Query
 */
function estatein_featured_properties_query() {
	$limit = (int) estatein_home( 'properties_limit' );
	$args  = array(
		'post_type'           => 'property',
		'posts_per_page'      => $limit > 0 ? $limit : 9,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
		'orderby'             => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
	);

	if ( 'latest' !== estatein_home( 'properties_source' ) ) {
		$args['meta_query'] = array(
			array(
				'key'   => 'is_featured',
				'value' => '1',
			),
		);
	}

	$query = new WP_Query( $args );

	if ( ! $query->have_posts() && isset( $args['meta_query'] ) ) {
		unset( $args['meta_query'] );
		$query = new WP_Query( $args );
	}

	return $query;
}

/**
 * Pads a slider index to two digits, matching the "01 of 60" design.
 *
 * @param int $number Number to pad.
 * @return string
 */
function estatein_pad( $number ) {
	return str_pad( (string) $number, 2, '0', STR_PAD_LEFT );
}

/**
 * Prints the site logo: the custom logo if set, otherwise the Estatein SVG mark.
 */
function estatein_logo() {
	if ( has_custom_logo() ) {
		the_custom_logo();
		return;
	}

	printf(
		'<a class="logo" href="%1$s" rel="home" aria-label="%2$s">%3$s%4$s</a>',
		esc_url( home_url( '/' ) ),
		esc_attr( get_bloginfo( 'name' ) ),
		wp_kses( estatein_icon( 'logo-symbol', 'logo__symbol' ), estatein_svg_allowed_html() ),
		wp_kses( estatein_icon( 'logo-text', 'logo__text' ), estatein_svg_allowed_html() )
	);
}

/**
 * Fallback for the primary menu so a fresh install still matches the design.
 */
function estatein_primary_menu_fallback() {
	$items = array(
		__( 'Home', 'estatein' )       => home_url( '/' ),
		__( 'About Us', 'estatein' )   => home_url( '/about-us/' ),
		__( 'Properties', 'estatein' ) => get_post_type_archive_link( 'property' ),
		__( 'Services', 'estatein' )   => home_url( '/services/' ),
	);

	echo '<ul id="primary-menu" class="nav__list">';
	foreach ( $items as $label => $url ) {
		$current = is_front_page() && home_url( '/' ) === $url;
		printf(
			'<li class="menu-item%1$s"><a href="%2$s"%3$s>%4$s</a></li>',
			$current ? ' current-menu-item' : '',
			esc_url( $url ),
			$current ? ' aria-current="page"' : '',
			esc_html( $label )
		);
	}
	echo '</ul>';
}

/**
 * Returns footer columns from the footer menu, or the design defaults.
 *
 * @return array<int, array{title:string,url:string,links:array}>
 */
function estatein_footer_columns() {
	$locations = get_nav_menu_locations();

	if ( ! empty( $locations['footer'] ) ) {
		$items = wp_get_nav_menu_items( $locations['footer'] );
		if ( $items ) {
			$columns = array();
			foreach ( $items as $item ) {
				if ( 0 === (int) $item->menu_item_parent ) {
					$columns[ $item->ID ] = array(
						'title' => $item->title,
						'url'   => $item->url,
						'links' => array(),
					);
				}
			}
			foreach ( $items as $item ) {
				$parent = (int) $item->menu_item_parent;
				if ( $parent && isset( $columns[ $parent ] ) ) {
					$columns[ $parent ]['links'][] = array(
						'title' => $item->title,
						'url'   => $item->url,
					);
				}
			}
			return array_values( $columns );
		}
	}

	$home       = home_url( '/' );
	$properties = (string) get_post_type_archive_link( 'property' );

	return array(
		array( 'title' => __( 'Home', 'estatein' ), 'url' => $home, 'links' => array(
			array( 'title' => __( 'Hero Section', 'estatein' ), 'url' => $home . '#top' ),
			array( 'title' => __( 'Features', 'estatein' ), 'url' => $home . '#features' ),
			array( 'title' => __( 'Properties', 'estatein' ), 'url' => $home . '#properties' ),
			array( 'title' => __( 'Testimonials', 'estatein' ), 'url' => $home . '#testimonials' ),
			array( 'title' => __( 'FAQ’s', 'estatein' ), 'url' => $home . '#faqs' ),
		) ),
		array( 'title' => __( 'About Us', 'estatein' ), 'url' => home_url( '/about-us/' ), 'links' => array(
			array( 'title' => __( 'Our Story', 'estatein' ), 'url' => home_url( '/about-us/#story' ) ),
			array( 'title' => __( 'Our Works', 'estatein' ), 'url' => home_url( '/about-us/#works' ) ),
			array( 'title' => __( 'How It Works', 'estatein' ), 'url' => home_url( '/about-us/#how-it-works' ) ),
			array( 'title' => __( 'Our Team', 'estatein' ), 'url' => home_url( '/about-us/#team' ) ),
			array( 'title' => __( 'Our Clients', 'estatein' ), 'url' => home_url( '/about-us/#clients' ) ),
		) ),
		array( 'title' => __( 'Properties', 'estatein' ), 'url' => $properties, 'links' => array(
			array( 'title' => __( 'Portfolio', 'estatein' ), 'url' => $properties ),
			array( 'title' => __( 'Categories', 'estatein' ), 'url' => $properties . '#categories' ),
		) ),
		array( 'title' => __( 'Services', 'estatein' ), 'url' => home_url( '/services/' ), 'links' => array(
			array( 'title' => __( 'Valuation Mastery', 'estatein' ), 'url' => home_url( '/services/#valuation' ) ),
			array( 'title' => __( 'Strategic Marketing', 'estatein' ), 'url' => home_url( '/services/#marketing' ) ),
			array( 'title' => __( 'Negotiation Wizardry', 'estatein' ), 'url' => home_url( '/services/#negotiation' ) ),
			array( 'title' => __( 'Closing Success', 'estatein' ), 'url' => home_url( '/services/#closing' ) ),
			array( 'title' => __( 'Property Management', 'estatein' ), 'url' => home_url( '/services/#management' ) ),
		) ),
		array( 'title' => __( 'Contact Us', 'estatein' ), 'url' => home_url( '/contact/' ), 'links' => array(
			array( 'title' => __( 'Contact Form', 'estatein' ), 'url' => home_url( '/contact/#form' ) ),
			array( 'title' => __( 'Our Offices', 'estatein' ), 'url' => home_url( '/contact/#offices' ) ),
		) ),
	);
}

/**
 * Returns the absolute URL for the header Contact Us button.
 *
 * @return string
 */
function estatein_contact_url() {
	$url = (string) get_theme_mod( 'estatein_contact_url', '/contact/' );
	return wp_parse_url( $url, PHP_URL_HOST ) ? $url : home_url( $url );
}

/**
 * Returns social profile links configured in the Customizer.
 *
 * @return array<string, string> Icon name => URL.
 */
function estatein_social_links() {
	$links = array();
	foreach ( array( 'facebook', 'linkedin', 'twitter', 'youtube' ) as $network ) {
		$links[ $network ] = (string) get_theme_mod( 'estatein_social_' . $network, '#' );
	}
	return array_filter( $links );
}

/**
 * Registers Customizer settings for site-wide elements (banner, footer, socials).
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function estatein_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'estatein_site',
		array(
			'title'    => __( 'Estatein Site Settings', 'estatein' ),
			'priority' => 30,
		)
	);

	$settings = array(
		'estatein_banner_enabled' => array( __( 'Show top announcement banner', 'estatein' ), 'checkbox', true, 'rest_sanitize_boolean' ),
		'estatein_banner_text'    => array( __( 'Banner text', 'estatein' ), 'text', '✨Discover Your Dream Property with Estatein', 'sanitize_text_field' ),
		'estatein_banner_link'    => array( __( 'Banner “Learn More” URL', 'estatein' ), 'url', '#properties', 'esc_url_raw' ),
		'estatein_contact_url'    => array( __( 'Header “Contact Us” URL', 'estatein' ), 'url', '/contact/', 'esc_url_raw' ),
		'estatein_copyright'      => array( __( 'Copyright text', 'estatein' ), 'text', '@2023 Estatein. All Rights Reserved.', 'sanitize_text_field' ),
	);

	foreach ( array( 'facebook', 'linkedin', 'twitter', 'youtube' ) as $network ) {
		$settings[ 'estatein_social_' . $network ] = array( ucfirst( $network ) . ' URL', 'url', '#', 'esc_url_raw' );
	}

	foreach ( $settings as $id => $setting ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $setting[2],
				'sanitize_callback' => $setting[3],
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $setting[0],
				'section' => 'estatein_site',
				'type'    => $setting[1],
			)
		);
	}
}
add_action( 'customize_register', 'estatein_customize_register' );

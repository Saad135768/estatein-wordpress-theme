<?php
/**
 * One-click demo content import (admin button and WP-CLI command).
 *
 * Creates the Home page, properties, testimonials, FAQs, menus and media
 * from files bundled with the theme, so a fresh install matches the design.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns the bundled demo data.
 *
 * @return array<string, array>
 */
function estatein_demo_data() {
	return array(
		'properties'   => array(
			array( 'Seaside Serenity Villa', 'A stunning 4-bedroom, 3-bathroom villa in a peaceful suburban neighborhood', 'property-seaside-villa', 550000, 4, 3, 'Villa', 'Malibu, California', 2800 ),
			array( 'Metropolitan Haven', 'A chic and fully-furnished 2-bedroom apartment with panoramic city views', 'property-metropolitan-haven', 550000, 2, 2, 'Villa', 'Downtown, New York', 1250 ),
			array( 'Rustic Retreat Cottage', 'An elegant 3-bedroom, 2.5-bathroom townhouse in a gated community', 'property-rustic-cottage', 550000, 3, 3, 'Villa', 'Austin, Texas', 1900 ),
			array( 'Skyline Penthouse', 'A sleek 3-bedroom penthouse with a private terrace and skyline views', 'property-rustic-cottage', 1250000, 3, 2, 'Condo', 'Miami, Florida', 2100 ),
			array( 'Garden Grove Townhouse', 'A bright 3-bedroom townhouse with a landscaped private garden', 'property-metropolitan-haven', 720000, 3, 2, 'House', 'Portland, Oregon', 1750 ),
			array( 'Coastal Breeze Residence', 'A spacious 5-bedroom family home just steps from the beach', 'property-seaside-villa', 980000, 5, 4, 'Villa', 'San Diego, California', 3600 ),
		),
		'testimonials' => array(
			array( 'Exceptional Service!', "Our experience with Estatein was outstanding. Their team's dedication and professionalism made finding our dream home a breeze. Highly recommended!", 'Wade Warren', 'USA, California', 'avatar-wade' ),
			array( 'Efficient and Reliable', "Estatein provided us with top-notch service. They helped us sell our property quickly and at a great price. We couldn't be happier with the results.", 'Emelie Thomson', 'USA, Florida', 'avatar-emelie' ),
			array( 'Trusted Advisors', 'The Estatein team guided us through the entire buying process. Their knowledge and commitment to our needs were impressive. Thank you for your support!', 'John Mans', 'USA, Nevada', 'avatar-john' ),
			array( 'Smooth From Start to Finish', 'From the first viewing to the final signature, everything was handled with care. We always knew what came next.', 'Mark Ellison', 'USA, Texas', 'avatar-john' ),
			array( 'Real Market Insight', 'Their valuation was spot on and the marketing plan brought in serious buyers within the first week.', 'Sophie Lane', 'USA, Oregon', 'avatar-emelie' ),
			array( 'Worth Every Penny', 'Estatein negotiated a price we did not think was possible. Professional, responsive and genuinely kind people.', 'Daniel Brooks', 'USA, Arizona', 'avatar-wade' ),
		),
		'faqs'         => array(
			array( 'How do I search for properties on Estatein?', 'Learn how to use our user-friendly search tools to find properties that match your criteria.', 'Use the Properties page to filter listings by location, price range, property type and number of bedrooms. Save a search to get notified when new matching listings are published.' ),
			array( 'What documents do I need to sell my property through Estatein?', 'Find out about the necessary documentation for listing your property with us.', 'You will need proof of ownership (title deed), a valid ID, recent property tax statements, and any HOA documents. Your Estatein agent will send a checklist tailored to your property.' ),
			array( 'How can I contact an Estatein agent?', 'Discover the different ways you can get in touch with our experienced agents.', 'Use the contact form, call our office during business hours, or click “Contact Us” in the header to book a call. Every enquiry is answered within one business day.' ),
			array( 'Do you help with mortgages and financing?', 'See how we connect buyers with trusted lending partners.', 'Yes. We work with several independent mortgage advisors who can pre-qualify you, compare rates, and guide you through the application at no extra cost.' ),
			array( 'How long does it take to sell a property?', 'Understand typical timelines from listing to closing.', 'Most well-priced properties receive offers within 30 days. Closing typically takes another 30–45 days depending on financing and inspections.' ),
			array( 'Can Estatein manage my rental property?', 'Learn about our end-to-end property management service.', 'Our management team handles tenant screening, rent collection, maintenance and reporting, so you earn passive income without the day-to-day work.' ),
		),
	);
}

/**
 * Copies a bundled theme image into the media library.
 *
 * @param string $slug Image slug inside assets/images (without extension).
 * @param string $alt  Alt text.
 * @return int Attachment ID, or 0 on failure.
 */
function estatein_import_image( $slug, $alt ) {
	static $imported = array();

	if ( isset( $imported[ $slug ] ) ) {
		return $imported[ $slug ];
	}

	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'meta_key'       => '_estatein_demo_slug',
			'meta_value'     => $slug,
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);
	if ( $existing ) {
		$imported[ $slug ] = (int) $existing[0];
		return $imported[ $slug ];
	}

	$source = ESTATEIN_DIR . '/assets/images/' . $slug . '.webp';
	if ( ! file_exists( $source ) ) {
		return 0;
	}

	$upload = wp_upload_bits( $slug . '.webp', null, (string) file_get_contents( $source ) );
	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/image.php';

	$attachment_id = wp_insert_attachment(
		array(
			'post_mime_type' => 'image/webp',
			'post_title'     => $alt,
			'post_status'    => 'inherit',
		),
		$upload['file']
	);

	if ( is_wp_error( $attachment_id ) ) {
		return 0;
	}

	wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata( $attachment_id, $upload['file'] ) );
	update_post_meta( $attachment_id, '_wp_attachment_image_alt', $alt );
	update_post_meta( $attachment_id, '_estatein_demo_slug', $slug );

	$imported[ $slug ] = $attachment_id;
	return $attachment_id;
}

/**
 * Saves a field through ACF when available, otherwise as post meta.
 *
 * @param string $name    Field name.
 * @param mixed  $value   Value.
 * @param int    $post_id Post ID.
 */
function estatein_save_field( $name, $value, $post_id ) {
	if ( function_exists( 'update_field' ) ) {
		update_field( 'field_estatein_' . $name, $value, $post_id );
		return;
	}
	update_post_meta( $post_id, $name, $value );
}

/**
 * Creates a published post once, identified by title and post type.
 *
 * @param array $postarr Arguments for wp_insert_post().
 * @return int Post ID.
 */
function estatein_insert_once( $postarr ) {
	$found = get_posts(
		array(
			'post_type'      => $postarr['post_type'],
			'title'          => $postarr['post_title'],
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);

	if ( $found ) {
		return (int) $found[0];
	}

	return (int) wp_insert_post( array_merge( array( 'post_status' => 'publish' ), $postarr ) );
}

/**
 * Imports all demo content. Safe to run more than once.
 *
 * @return array<string, int> Counts of created or matched items.
 */
function estatein_import_demo() {
	$data   = estatein_demo_data();
	$counts = array( 'properties' => 0, 'testimonials' => 0, 'faqs' => 0 );

	foreach ( $data['properties'] as $order => $row ) {
		list( $title, $excerpt, $image, $price, $beds, $baths, $type, $location, $area ) = $row;

		$post_id = estatein_insert_once(
			array(
				'post_type'    => 'property',
				'post_title'   => $title,
				'post_excerpt' => $excerpt,
				'post_content' => '<p>' . $excerpt . '. Thoughtfully designed living spaces, premium finishes and natural light throughout make this home ready to move in.</p><p>Schedule a private viewing with an Estatein agent to explore the property and the neighborhood.</p>',
				'menu_order'   => $order,
			)
		);

		set_post_thumbnail( $post_id, estatein_import_image( $image, $title ) );
		wp_set_object_terms( $post_id, $type, 'property_type' );
		estatein_save_field( 'price', $price, $post_id );
		estatein_save_field( 'bedrooms', $beds, $post_id );
		estatein_save_field( 'bathrooms', $baths, $post_id );
		estatein_save_field( 'area', $area, $post_id );
		estatein_save_field( 'location', $location, $post_id );
		estatein_save_field( 'is_featured', 1, $post_id );
		++$counts['properties'];
	}

	foreach ( $data['testimonials'] as $order => $row ) {
		list( $title, $quote, $name, $location, $avatar ) = $row;

		$post_id = estatein_insert_once(
			array(
				'post_type'    => 'testimonial',
				'post_title'   => $title,
				'post_content' => $quote,
				'menu_order'   => $order,
			)
		);

		set_post_thumbnail( $post_id, estatein_import_image( $avatar, $name ) );
		estatein_save_field( 'rating', 5, $post_id );
		estatein_save_field( 'client_name', $name, $post_id );
		estatein_save_field( 'client_location', $location, $post_id );
		++$counts['testimonials'];
	}

	foreach ( $data['faqs'] as $order => $row ) {
		estatein_insert_once(
			array(
				'post_type'    => 'faq',
				'post_title'   => $row[0],
				'post_excerpt' => $row[1],
				'post_content' => $row[2],
				'menu_order'   => $order,
			)
		);
		++$counts['faqs'];
	}

	$pages = array();
	foreach ( array( 'Home', 'About Us', 'Services', 'Contact' ) as $page_title ) {
		$pages[ $page_title ] = estatein_insert_once(
			array(
				'post_type'    => 'page',
				'post_title'   => $page_title,
				'post_content' => 'Home' === $page_title ? '' : '<p>This page is part of the full Estatein design and is outside the scope of the Home page build.</p>',
			)
		);
	}

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $pages['Home'] );
	update_option( 'blogdescription', __( 'Discover your dream property', 'estatein' ) );

	$home_fields = array(
		'hero_image'            => estatein_import_image( 'hero-building', __( 'Modern glass high-rise buildings at dusk', 'estatein' ) ),
		'hero_secondary_button' => array( 'title' => 'Learn More', 'url' => '#features', 'target' => '' ),
		'hero_primary_button'   => array( 'title' => 'Browse Properties', 'url' => get_post_type_archive_link( 'property' ), 'target' => '' ),
		'properties_source'     => 'featured',
		'properties_limit'      => 9,
	);
	foreach ( $home_fields as $name => $value ) {
		estatein_save_field( $name, $value, $pages['Home'] );
	}

	estatein_create_primary_menu( $pages );

	update_option( 'estatein_demo_imported', time() );
	flush_rewrite_rules();

	return $counts;
}

/**
 * Creates and assigns the primary navigation menu if none is assigned.
 *
 * @param array<string, int> $pages Page IDs keyed by title.
 */
function estatein_create_primary_menu( $pages ) {
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	if ( ! empty( $locations['primary'] ) ) {
		return;
	}

	$menu    = wp_get_nav_menu_object( 'Primary' );
	$menu_id = $menu ? (int) $menu->term_id : (int) wp_create_nav_menu( 'Primary' );

	if ( ! $menu ) {
		$items = array(
			array( 'post_type', 'page', $pages['Home'], 'Home' ),
			array( 'post_type', 'page', $pages['About Us'], 'About Us' ),
			array( 'post_type_archive', 'property', 0, 'Properties' ),
			array( 'post_type', 'page', $pages['Services'], 'Services' ),
		);
		foreach ( $items as $position => $item ) {
			wp_update_nav_menu_item(
				$menu_id,
				0,
				array(
					'menu-item-title'     => $item[3],
					'menu-item-type'      => $item[0],
					'menu-item-object'    => $item[1],
					'menu-item-object-id' => $item[2],
					'menu-item-position'  => $position + 1,
					'menu-item-status'    => 'publish',
				)
			);
		}
	}

	$locations['primary'] = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );
}

/**
 * Shows an admin notice with an import button until demo content exists.
 */
function estatein_demo_notice() {
	if ( ! current_user_can( 'manage_options' ) || get_option( 'estatein_demo_imported' ) ) {
		return;
	}

	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->id, array( 'dashboard', 'themes' ), true ) ) {
		return;
	}
	?>
	<div class="notice notice-info">
		<p><strong><?php esc_html_e( 'Estatein:', 'estatein' ); ?></strong> <?php esc_html_e( 'Import the demo Home page, properties, testimonials and FAQs to match the design in one click.', 'estatein' ); ?></p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="estatein_import_demo">
			<?php wp_nonce_field( 'estatein_import_demo' ); ?>
			<p><button type="submit" class="button button-primary"><?php esc_html_e( 'Import demo content', 'estatein' ); ?></button></p>
		</form>
	</div>
	<?php
}
add_action( 'admin_notices', 'estatein_demo_notice' );

/**
 * Handles the admin import button.
 */
function estatein_handle_demo_import() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to import demo content.', 'estatein' ), 403 );
	}
	check_admin_referer( 'estatein_import_demo' );

	estatein_import_demo();

	wp_safe_redirect( home_url( '/' ) );
	exit;
}
add_action( 'admin_post_estatein_import_demo', 'estatein_handle_demo_import' );

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command(
		'estatein import-demo',
		static function () {
			$counts = estatein_import_demo();
			WP_CLI::success( sprintf( 'Imported %1$d properties, %2$d testimonials and %3$d FAQs.', $counts['properties'], $counts['testimonials'], $counts['faqs'] ) );
		}
	);
}

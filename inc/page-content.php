<?php
/**
 * Copy and structured content for the inner pages, mirrored from the Figma design.
 *
 * Every block is passed through a filter (estatein_content_{key}) so it can be
 * changed from a child theme or plugin without editing templates.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns a content block by key.
 *
 * @param string $key Block key.
 * @return mixed
 */
function estatein_content( $key ) {
	static $content = null;

	if ( null === $content ) {
		$content = estatein_content_defaults();
	}

	return apply_filters( 'estatein_content_' . $key, $content[ $key ] ?? array() );
}

/**
 * Default inner-page content.
 *
 * @return array<string, mixed>
 */
function estatein_content_defaults() {
	return array(
		'about_journey'      => array(
			'title' => __( 'Our Journey', 'estatein' ),
			'text'  => __( 'Our story is one of continuous growth and evolution. We started as a small team with big dreams, determined to create a real estate platform that transcended the ordinary. Over the years, we\'ve expanded our reach, forged valuable partnerships, and gained the trust of countless clients.', 'estatein' ),
		),
		'about_values_intro' => array(
			'title' => __( 'Our Values', 'estatein' ),
			'text'  => __( 'Our story is one of continuous growth and evolution. We started as a small team with big dreams, determined to create a real estate platform that transcended the ordinary.', 'estatein' ),
		),
		'about_values'       => array(
			array( 'icon' => 'value-star', 'title' => __( 'Trust', 'estatein' ), 'text' => __( 'Trust is the cornerstone of every successful real estate transaction.', 'estatein' ) ),
			array( 'icon' => 'graduation', 'title' => __( 'Excellence', 'estatein' ), 'text' => __( 'We set the bar high for ourselves. From the properties we list to the services we provide.', 'estatein' ) ),
			array( 'icon' => 'users', 'title' => __( 'Client-Centric', 'estatein' ), 'text' => __( 'Your dreams and needs are at the center of our universe. We listen, understand.', 'estatein' ) ),
			array( 'icon' => 'value-star', 'title' => __( 'Our Commitment', 'estatein' ), 'text' => __( 'We are dedicated to providing you with the highest level of service, professionalism, and support.', 'estatein' ) ),
		),
		'about_achievements' => array(
			'header' => array(
				'title' => __( 'Our Achievements', 'estatein' ),
				'text'  => __( 'Our story is one of continuous growth and evolution. We started as a small team with big dreams, determined to create a real estate platform that transcended the ordinary.', 'estatein' ),
			),
			'items'  => array(
				array( 'title' => __( '3+ Years of Excellence', 'estatein' ), 'text' => __( 'With over 3 years in the industry, we\'ve amassed a wealth of knowledge and experience, becoming a go-to resource for all things real estate.', 'estatein' ) ),
				array( 'title' => __( 'Happy Clients', 'estatein' ), 'text' => __( 'Our greatest achievement is the satisfaction of our clients. Their success stories fuel our passion for what we do.', 'estatein' ) ),
				array( 'title' => __( 'Industry Recognition', 'estatein' ), 'text' => __( 'We\'ve earned the respect of our peers and industry leaders, with accolades and awards that reflect our commitment to excellence.', 'estatein' ) ),
			),
		),
		'about_steps'        => array(
			'header' => array(
				'title' => __( 'Navigating the Estatein Experience', 'estatein' ),
				'text'  => __( 'At Estatein, we\'ve designed a straightforward process to help you find and purchase your dream property with ease. Here\'s a step-by-step guide to how it all works.', 'estatein' ),
			),
			'items'  => array(
				array( 'title' => __( 'Discover a World of Possibilities', 'estatein' ), 'text' => __( 'Your journey begins with exploring our carefully curated property listings. Use our intuitive search tools to filter properties based on your preferences, including location, type, size, and budget.', 'estatein' ) ),
				array( 'title' => __( 'Narrowing Down Your Choices', 'estatein' ), 'text' => __( 'Once you\'ve found properties that catch your eye, save them to your account or make a shortlist. This allows you to compare and revisit your favorites as you make your decision.', 'estatein' ) ),
				array( 'title' => __( 'Personalized Guidance', 'estatein' ), 'text' => __( 'Have questions about a property or need more information? Our dedicated team of real estate experts is just a call or message away.', 'estatein' ) ),
				array( 'title' => __( 'See It for Yourself', 'estatein' ), 'text' => __( 'Arrange viewings of the properties you\'re interested in. We\'ll coordinate with the property owners and accompany you to ensure you get a firsthand look at your potential new home.', 'estatein' ) ),
				array( 'title' => __( 'Making Informed Decisions', 'estatein' ), 'text' => __( 'Before making an offer, our team will assist you with due diligence, including property inspections, legal checks, and market analysis. We want you to be fully informed and confident in your choice.', 'estatein' ) ),
				array( 'title' => __( 'Getting the Best Deal', 'estatein' ), 'text' => __( 'We\'ll help you negotiate the best terms and prepare your offer. Our goal is to secure the property at the right price and on favorable terms.', 'estatein' ) ),
			),
		),
		'about_team'         => array(
			'title' => __( 'Meet the Estatein Team', 'estatein' ),
			'text'  => __( 'At Estatein, our success is driven by the dedication and expertise of our team. Get to know the people behind our mission to make your real estate dreams a reality.', 'estatein' ),
		),
		'about_clients'      => array(
			'title' => __( 'Our Valued Clients', 'estatein' ),
			'text'  => __( 'At Estatein, we have had the privilege of working with a diverse range of clients across various industries. Here are some of the clients we\'ve had the pleasure of serving.', 'estatein' ),
		),
		'properties_hero'    => array(
			'title' => __( 'Find Your Dream Property', 'estatein' ),
			'text'  => __( 'Welcome to Estatein, where your dream property awaits in every corner of our beautiful world. Explore our curated selection of properties, each offering a unique story and a chance to redefine your life. With categories to suit every dreamer, your journey', 'estatein' ),
		),
		'properties_list'    => array(
			'title' => __( 'Discover a World of Possibilities', 'estatein' ),
			'text'  => __( 'Our portfolio of properties is as diverse as your dreams. Explore the following categories to find the perfect property that resonates with your vision of home.', 'estatein' ),
		),
		'properties_form'    => array(
			'title' => __( 'Let\'s Make it Happen', 'estatein' ),
			'text'  => __( 'Ready to take the first step toward your dream property? Fill out the form below, and our real estate wizards will work their magic to find your perfect match. Don\'t wait; let\'s embark on this exciting journey together.', 'estatein' ),
		),
		'property_pricing'   => array(
			'title' => __( 'Comprehensive Pricing Details', 'estatein' ),
			'text'  => __( 'At Estatein, transparency is key. We want you to have a clear understanding of all costs associated with your property investment. Below, we break down the pricing for %s to help you make an informed decision.', 'estatein' ),
			'note'  => __( 'The figures provided above are estimates and may vary depending on the property, location, and individual circumstances.', 'estatein' ),
		),
		'property_inquiry'   => array(
			'title' => __( 'Inquire About %s', 'estatein' ),
			'text'  => __( 'Interested in this property? Fill out the form below, and our real estate experts will get back to you with more details, including scheduling a viewing and answering any questions you may have.', 'estatein' ),
		),
		'services_hero'      => array(
			'title' => __( 'Elevate Your Real Estate Experience', 'estatein' ),
			'text'  => __( 'Welcome to Estatein, where your real estate aspirations meet expert guidance. Explore our comprehensive range of services, each designed to cater to your unique needs and dreams.', 'estatein' ),
		),
		'services_groups'    => array(
			'selling'    => array(
				'title'    => __( 'Unlock Property Value', 'estatein' ),
				'text'     => __( 'Selling your property should be a rewarding experience, and at Estatein, we make sure it is. Our Property Selling Service is designed to maximize the value of your property, ensuring you get the best deal possible. Explore the categories below to see how we can help you at every step of your selling journey.', 'estatein' ),
				'cta'      => __( 'Unlock the Value of Your Property Today', 'estatein' ),
				'cta_text' => __( 'Ready to unlock the true value of your property? Explore our Property Selling Service categories and let us help you achieve the best deal possible for your valuable asset.', 'estatein' ),
				'layout'   => 'grid',
			),
			'management' => array(
				'title'    => __( 'Effortless Property Management', 'estatein' ),
				'text'     => __( 'Owning a property should be a pleasure, not a hassle. Estatein\'s Property Management Service takes the stress out of property ownership, offering comprehensive solutions tailored to your needs. Explore the categories below to see how we can make property management effortless for you.', 'estatein' ),
				'cta'      => __( 'Experience Effortless Property Management', 'estatein' ),
				'cta_text' => __( 'Ready to experience hassle-free property management? Explore our Property Management Service categories and let us handle the complexities while you enjoy the benefits of property ownership.', 'estatein' ),
				'layout'   => 'grid',
			),
			'investment' => array(
				'title'    => __( 'Smart Investments, Informed Decisions', 'estatein' ),
				'text'     => __( 'Building a real estate portfolio requires a strategic approach. Estatein\'s Investment Advisory Service empowers you to make smart investments and informed decisions.', 'estatein' ),
				'cta'      => __( 'Unlock Your Investment Potential', 'estatein' ),
				'cta_text' => __( 'Explore our Property Management Service categories and let us handle the complexities while you enjoy the benefits of property ownership.', 'estatein' ),
				'layout'   => 'split',
			),
		),
		'contact_hero'       => array(
			'title' => __( 'Get in Touch with Estatein', 'estatein' ),
			'text'  => __( 'Welcome to Estatein\'s Contact Us page. We\'re here to assist you with any inquiries, requests, or feedback you may have. Whether you\'re looking to buy or sell a property, explore investment opportunities, or simply want to connect, we\'re just a message away. Reach out to us, and let\'s start a conversation.', 'estatein' ),
		),
		'contact_form'       => array(
			'title' => __( 'Let\'s Connect', 'estatein' ),
			'text'  => __( 'We\'re excited to connect with you and learn more about your real estate goals. Use the form below to get in touch with Estatein. Whether you\'re a prospective client, partner, or simply curious about our services, we\'re here to answer your questions and provide the assistance you need.', 'estatein' ),
		),
		'contact_offices'    => array(
			'title' => __( 'Discover Our Office Locations', 'estatein' ),
			'text'  => __( 'Estatein is here to serve you across multiple locations. Whether you\'re looking to meet our team, discuss real estate opportunities, or simply drop by for a chat, we have offices conveniently located to serve your needs. Explore the categories below to find the Estatein office nearest to you.', 'estatein' ),
		),
		'contact_gallery'    => array(
			'title'  => __( 'Explore Estatein\'s World', 'estatein' ),
			'text'   => __( 'Step inside the world of Estatein, where professionalism meets warmth, and expertise meets passion. Our gallery offers a glimpse into our team and workspaces, inviting you to get to know us better.', 'estatein' ),
			'images' => array(
				array( 'office-workspace', __( 'Estatein open-plan office workspace', 'estatein' ) ),
				array( 'office-team', __( 'The Estatein sales team', 'estatein' ) ),
				array( 'office-meeting', __( 'Team planning meeting around a table', 'estatein' ) ),
				array( 'office-team-small', __( 'Estatein agents standing together', 'estatein' ) ),
				array( 'office-agents', __( 'Smiling Estatein agents', 'estatein' ) ),
				array( 'office-handshake', __( 'Agent shaking hands with a client', 'estatein' ) ),
			),
		),
	);
}

/**
 * Service groups available in the Service post type.
 *
 * @return array<string, string>
 */
function estatein_service_group_choices() {
	return array(
		'selling'    => __( 'Property selling', 'estatein' ),
		'management' => __( 'Property management', 'estatein' ),
		'investment' => __( 'Investment advisory', 'estatein' ),
	);
}

/**
 * Icon names available for services.
 *
 * @return string[]
 */
function estatein_service_icons() {
	return array( 'chart', 'pie', 'coins', 'megaphone', 'users', 'wrench', 'wallet', 'shield', 'lightbulb', 'flame', 'target', 'sparkle' );
}

/**
 * Returns the hero title and text for an inner page, preferring the ACF fields.
 *
 * @param string $key Content key holding the default hero copy.
 * @return array{title:string,text:string}
 */
function estatein_page_hero( $key ) {
	$defaults = estatein_content( $key );
	$page_id  = is_singular( 'page' ) ? get_queried_object_id() : (int) get_option( 'estatein_page_' . $key );

	return array(
		'title' => (string) ( $page_id ? estatein_field( 'page_hero_title', $page_id, $defaults['title'] ) : $defaults['title'] ),
		'text'  => (string) ( $page_id ? estatein_field( 'page_hero_text', $page_id, $defaults['text'] ) : $defaults['text'] ),
	);
}

/**
 * Builds the URL of a bundled theme image.
 *
 * @param string $slug Image slug without extension.
 * @return string
 */
function estatein_image_url( $slug ) {
	return ESTATEIN_URI . '/assets/images/' . sanitize_file_name( $slug ) . '.webp';
}

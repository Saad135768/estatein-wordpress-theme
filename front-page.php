<?php
/**
 * Home page: assembles the section template parts.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

get_header();

get_template_part( 'template-parts/home/hero' );
get_template_part( 'template-parts/home/features' );
get_template_part( 'template-parts/home/properties' );
get_template_part( 'template-parts/home/testimonials' );
get_template_part( 'template-parts/home/faqs' );
get_template_part( 'template-parts/home/cta' );

get_footer();

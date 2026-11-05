<?php
/**
 * Inner-page hero band: H1 and intro text, optionally followed by extra markup.
 *
 * Expects $args['key'] (content key for defaults) and optional $args['id'].
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

$estatein_hero = estatein_page_hero( $args['key'] );
?>
<section class="page-hero<?php echo ! empty( $args['modifier'] ) ? ' page-hero--' . esc_attr( $args['modifier'] ) : ''; ?>" aria-labelledby="page-hero-title">
	<div class="container page-hero__inner">
		<h1 class="page-hero__title" id="page-hero-title"><?php echo esc_html( $estatein_hero['title'] ); ?></h1>
		<p class="page-hero__text"><?php echo esc_html( $estatein_hero['text'] ); ?></p>
	</div>
</section>

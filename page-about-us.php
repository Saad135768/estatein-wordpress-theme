<?php
/**
 * Template Name: About Us
 *
 * About page: journey, values, achievements, process steps, team and clients.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

get_header();

$estatein_journey = estatein_content( 'about_journey' );
$estatein_hero    = estatein_page_hero( 'about_journey' );
$estatein_values  = estatein_content( 'about_values_intro' );
$estatein_achieve = estatein_content( 'about_achievements' );
$estatein_steps   = estatein_content( 'about_steps' );
?>
<section class="section section--first journey" aria-labelledby="journey-title">
	<div class="container journey__inner">
		<div class="journey__content">
			<?php get_template_part( 'template-parts/components/section-header', null, array( 'title' => $estatein_hero['title'], 'text' => $estatein_hero['text'], 'id' => 'journey-title', 'tag' => 'h1' ) ); ?>
			<dl class="hero__stats journey__stats">
				<?php foreach ( estatein_hero_stats() as $estatein_stat ) : ?>
					<div class="stat">
						<dt class="stat__label"><?php echo esc_html( $estatein_stat['label'] ); ?></dt>
						<dd class="stat__value"><?php echo esc_html( $estatein_stat['value'] ); ?></dd>
					</div>
				<?php endforeach; ?>
			</dl>
		</div>
		<div class="journey__media">
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( 'large', array( 'class' => 'journey__image', 'fetchpriority' => 'high' ) );
			} else {
				printf( '<img class="journey__image" src="%s" width="755" height="546" alt="%s" fetchpriority="high">', esc_url( estatein_image_url( 'about-hero' ) ), esc_attr__( 'Hand holding a model house', 'estatein' ) );
			}
			?>
		</div>
	</div>
</section>

<section class="section values" aria-labelledby="values-title">
	<div class="container values__inner">
		<?php get_template_part( 'template-parts/components/section-header', null, array( 'title' => $estatein_values['title'], 'text' => $estatein_values['text'], 'id' => 'values-title' ) ); ?>
		<ul class="values__grid">
			<?php foreach ( estatein_content( 'about_values' ) as $estatein_value ) : ?>
				<li class="value">
					<div class="value__head">
						<span class="icon-badge"><?php estatein_the_icon( $estatein_value['icon'], 'icon icon--24' ); ?></span>
						<h3 class="value__title"><?php echo esc_html( $estatein_value['title'] ); ?></h3>
					</div>
					<p class="value__text"><?php echo esc_html( $estatein_value['text'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

<section class="section" aria-labelledby="achievements-title">
	<div class="container">
		<?php get_template_part( 'template-parts/components/section-header', null, $estatein_achieve['header'] + array( 'id' => 'achievements-title' ) ); ?>
		<ul class="card-grid card-grid--3">
			<?php foreach ( $estatein_achieve['items'] as $estatein_item ) : ?>
				<li class="info-card info-card--shadow">
					<h3 class="info-card__title"><?php echo esc_html( $estatein_item['title'] ); ?></h3>
					<p class="info-card__text"><?php echo esc_html( $estatein_item['text'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

<section class="section" aria-labelledby="steps-title">
	<div class="container">
		<?php get_template_part( 'template-parts/components/section-header', null, $estatein_steps['header'] + array( 'id' => 'steps-title' ) ); ?>
		<ol class="card-grid card-grid--3 steps">
			<?php foreach ( $estatein_steps['items'] as $estatein_index => $estatein_step ) : ?>
				<li class="step">
					<span class="step__number"><?php echo esc_html( sprintf( __( 'Step %s', 'estatein' ), estatein_pad( $estatein_index + 1 ) ) ); ?></span>
					<div class="step__body">
						<h3 class="step__title"><?php echo esc_html( $estatein_step['title'] ); ?></h3>
						<p class="step__text"><?php echo esc_html( $estatein_step['text'] ); ?></p>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>

<?php
get_template_part( 'template-parts/sections/team' );
get_template_part( 'template-parts/sections/clients' );
get_template_part( 'template-parts/home/cta' );
get_footer();

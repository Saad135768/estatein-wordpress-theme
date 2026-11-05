<?php
/**
 * Home hero: headline, buttons, stats, building image and rotating badge.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

$estatein_image = estatein_hero_image();
$estatein_badge = (string) estatein_home( 'hero_badge_text' );
?>
<section class="hero" aria-labelledby="hero-title">
	<div class="hero__content">
		<div class="hero__text">
			<h1 class="hero__title" id="hero-title"><?php echo esc_html( estatein_home( 'hero_title' ) ); ?></h1>
			<p class="hero__intro"><?php echo esc_html( estatein_home( 'hero_text' ) ); ?></p>

			<a class="hero-badge" href="#properties" aria-label="<?php echo esc_attr( $estatein_badge ); ?>">
				<svg class="hero-badge__ring" viewBox="0 0 175 175" aria-hidden="true" focusable="false">
					<defs>
						<path id="hero-badge-circle" d="M87.5,87.5 m-61,0 a61,61 0 1,1 122,0 a61,61 0 1,1 -122,0"></path>
					</defs>
					<text>
						<textPath href="#hero-badge-circle" textLength="380"><?php echo esc_html( $estatein_badge ); ?> ✨ </textPath>
					</text>
				</svg>
				<span class="hero-badge__core"><?php estatein_the_icon( 'arrow-up-right', 'icon icon--34' ); ?></span>
			</a>
		</div>

		<div class="hero__actions">
			<?php
			estatein_button( estatein_home( 'hero_secondary_button' ), 'outline' );
			estatein_button( estatein_home( 'hero_primary_button' ), 'primary' );
			?>
		</div>

		<dl class="hero__stats">
			<?php foreach ( estatein_hero_stats() as $estatein_stat ) : ?>
				<div class="stat">
					<dt class="stat__label"><?php echo esc_html( $estatein_stat['label'] ); ?></dt>
					<dd class="stat__value"><?php echo esc_html( $estatein_stat['value'] ); ?></dd>
				</div>
			<?php endforeach; ?>
		</dl>
	</div>

	<div class="hero__media">
		<img
			class="hero__image"
			src="<?php echo esc_url( $estatein_image['src'] ); ?>"
			<?php if ( $estatein_image['srcset'] ) : ?>
				srcset="<?php echo esc_attr( $estatein_image['srcset'] ); ?>"
				sizes="<?php echo esc_attr( $estatein_image['sizes'] ); ?>"
			<?php endif; ?>
			width="<?php echo esc_attr( $estatein_image['width'] ); ?>"
			height="<?php echo esc_attr( $estatein_image['height'] ); ?>"
			alt="<?php echo esc_attr( $estatein_image['alt'] ); ?>"
			fetchpriority="high"
			decoding="async"
		>
	</div>
</section>

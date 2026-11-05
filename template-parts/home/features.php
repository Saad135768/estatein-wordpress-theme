<?php
/**
 * Home feature cards strip below the hero.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="features" id="features" aria-label="<?php esc_attr_e( 'What we offer', 'estatein' ); ?>">
	<ul class="features__list">
		<?php foreach ( estatein_features() as $estatein_feature ) : ?>
			<?php $estatein_url = $estatein_feature['link'] ? $estatein_feature['link'] : get_post_type_archive_link( 'property' ); ?>
			<li class="feature-card">
				<a class="feature-card__link" href="<?php echo esc_url( $estatein_url ); ?>">
					<span class="feature-card__icon">
						<span class="feature-card__icon-inner"><?php estatein_the_icon( $estatein_feature['icon'], 'icon icon--34' ); ?></span>
					</span>
					<span class="feature-card__title"><?php echo esc_html( $estatein_feature['title'] ); ?></span>
					<?php estatein_the_icon( 'arrow-up-right', 'icon icon--34 feature-card__arrow' ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</section>

<?php
/**
 * "Comprehensive Pricing Details" for a property, with computed totals.
 *
 * Expects $args['property_id'].
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

$estatein_id      = (int) $args['property_id'];
$estatein_price   = (float) estatein_field( 'price', $estatein_id, 0 );
$estatein_content = estatein_content( 'property_pricing' );

if ( ! $estatein_price ) {
	return;
}

$estatein_num  = static fn( $name ) => (float) estatein_field( $name, $estatein_id, 0 );
$estatein_fmt  = static fn( $value ) => '$' . number_format_i18n( $value );
$estatein_tax  = $estatein_num( 'fee_transfer_tax' );
$estatein_leg  = $estatein_num( 'fee_legal' );
$estatein_ins  = $estatein_num( 'fee_inspection' );
$estatein_insu = $estatein_num( 'fee_insurance' );
$estatein_mtax = $estatein_num( 'monthly_tax' );
$estatein_hoa  = $estatein_num( 'monthly_hoa' );
$estatein_dp   = (float) estatein_field( 'down_payment_percent', $estatein_id, 20 );
$estatein_fees = $estatein_tax + $estatein_leg + $estatein_ins + $estatein_insu;
$estatein_down = $estatein_price * $estatein_dp / 100;

$estatein_groups = array(
	__( 'Additional Fees', 'estatein' )     => array(
		array( __( 'Property Transfer Tax', 'estatein' ), $estatein_fmt( $estatein_tax ), __( 'Based on the sale price and local regulations', 'estatein' ) ),
		array( __( 'Legal Fees', 'estatein' ), $estatein_fmt( $estatein_leg ), __( 'Approximate cost for legal services, including title transfer', 'estatein' ) ),
		array( __( 'Home Inspection', 'estatein' ), $estatein_fmt( $estatein_ins ), __( 'Recommended for due diligence', 'estatein' ) ),
		array( __( 'Property Insurance', 'estatein' ), $estatein_fmt( $estatein_insu ), __( 'Annual cost for comprehensive property insurance', 'estatein' ) ),
		array( __( 'Mortgage Fees', 'estatein' ), __( 'Varies', 'estatein' ), __( 'If applicable, consult with your lender for specific details', 'estatein' ), true ),
	),
	__( 'Monthly Costs', 'estatein' )       => array(
		array( __( 'Property Taxes', 'estatein' ), $estatein_fmt( $estatein_mtax ), __( 'Approximate monthly property tax based on the sale price and local rates', 'estatein' ) ),
		array( __( 'Homeowners\' Association Fee', 'estatein' ), $estatein_fmt( $estatein_hoa ), __( 'Monthly fee for common area maintenance and security', 'estatein' ) ),
	),
	__( 'Total Initial Costs', 'estatein' ) => array(
		array( __( 'Listing Price', 'estatein' ), $estatein_fmt( $estatein_price ), '' ),
		array( __( 'Additional Fees', 'estatein' ), $estatein_fmt( $estatein_fees ), __( 'Property transfer tax, legal fees, inspection and insurance', 'estatein' ) ),
		array( __( 'Down Payment', 'estatein' ), $estatein_fmt( $estatein_down ), sprintf( '%s%%', number_format_i18n( $estatein_dp ) ) ),
		array( __( 'Mortgage Amount', 'estatein' ), $estatein_fmt( $estatein_price - $estatein_down ), __( 'If applicable', 'estatein' ) ),
	),
	__( 'Monthly Expenses', 'estatein' )    => array(
		array( __( 'Property Taxes', 'estatein' ), $estatein_fmt( $estatein_mtax ), '' ),
		array( __( 'Homeowners\' Association Fee', 'estatein' ), $estatein_fmt( $estatein_hoa ), '' ),
		array( __( 'Mortgage Payment', 'estatein' ), __( 'Varies based on terms and interest rate', 'estatein' ), __( 'If applicable', 'estatein' ), true ),
		array( __( 'Property Insurance', 'estatein' ), $estatein_fmt( round( $estatein_insu / 12 ) ), __( 'Approximate monthly cost', 'estatein' ) ),
	),
);
?>
<section class="section" id="pricing" aria-labelledby="pricing-title">
	<div class="container">
		<?php get_template_part( 'template-parts/components/section-header', null, array( 'title' => $estatein_content['title'], 'text' => sprintf( $estatein_content['text'], get_the_title( $estatein_id ) ), 'id' => 'pricing-title' ) ); ?>

		<p class="pricing-note"><strong><?php esc_html_e( 'Note', 'estatein' ); ?></strong><span><?php echo esc_html( $estatein_content['note'] ); ?></span></p>

		<div class="pricing">
			<div class="pricing__listing">
				<p class="pricing__listing-label"><?php esc_html_e( 'Listing Price', 'estatein' ); ?></p>
				<p class="pricing__listing-value"><?php echo esc_html( $estatein_fmt( $estatein_price ) ); ?></p>
			</div>
			<div class="pricing__groups">
				<?php foreach ( $estatein_groups as $estatein_title => $estatein_rows ) : ?>
					<section class="pricing-card">
						<header class="pricing-card__header">
							<h3 class="pricing-card__title"><?php echo esc_html( $estatein_title ); ?></h3>
							<a class="btn btn--secondary pricing-card__more" href="#enquiry-inquiry"><?php esc_html_e( 'Learn More', 'estatein' ); ?></a>
						</header>
						<dl class="pricing-card__rows">
							<?php foreach ( $estatein_rows as $estatein_row ) : ?>
								<div class="pricing-row">
									<dt class="pricing-row__label"><?php echo esc_html( $estatein_row[0] ); ?></dt>
									<dd class="pricing-row__value">
										<span class="pricing-row__amount<?php echo ! empty( $estatein_row[3] ) ? ' pricing-row__amount--text' : ''; ?>"><?php echo esc_html( $estatein_row[1] ); ?></span>
										<?php if ( $estatein_row[2] ) : ?>
											<span class="chip pricing-row__hint"><?php echo esc_html( $estatein_row[2] ); ?></span>
										<?php endif; ?>
									</dd>
								</div>
							<?php endforeach; ?>
						</dl>
					</section>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

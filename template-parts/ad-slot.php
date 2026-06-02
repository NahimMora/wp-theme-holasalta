<?php
/**
 * Reusable advertising slot.
 *
 * Add an Image or Custom HTML widget to the matching widget area to replace
 * the placeholder with a GIF, WebP, JPEG, PNG, or ad embed later.
 *
 * @package HolaSalta_Child
 */

$slot       = isset( $args['slot'] ) ? sanitize_html_class( $args['slot'] ) : 'generic';
$size       = isset( $args['size'] ) ? preg_replace( '/[^0-9x]/', '', $args['size'] ) : '728x90';
$sidebar_id = hs_get_ad_sidebar_id( $slot );
?>

<aside class="hs-ad-slot hs-ad-slot--<?php echo esc_attr( $slot ); ?>" aria-label="<?php esc_attr_e( 'Publicidad', 'holasalta-child' ); ?>">
	<?php if ( $sidebar_id && is_active_sidebar( $sidebar_id ) ) : ?>
		<div class="hs-ad-slot-content">
			<?php dynamic_sidebar( $sidebar_id ); ?>
		</div>
	<?php else : ?>
		<span class="hs-ad-label"><?php esc_html_e( 'Publicidad', 'holasalta-child' ); ?></span>
		<strong><?php esc_html_e( 'Espacio publicitario', 'holasalta-child' ); ?></strong>
		<span class="hs-ad-size"><?php echo esc_html( sprintf( __( 'Banner %s', 'holasalta-child' ), $size ) ); ?></span>
	<?php endif; ?>
</aside>

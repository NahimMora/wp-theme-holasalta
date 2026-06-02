<?php
/**
 * Reusable news card.
 *
 * @package HolaSalta_Child
 */

$variant       = isset( $args['variant'] ) ? sanitize_html_class( $args['variant'] ) : 'standard';
$show_excerpt  = ! empty( $args['show_excerpt'] );
$heading_level = isset( $args['heading_level'] ) && in_array( $args['heading_level'], array( 'h2', 'h3' ), true ) ? $args['heading_level'] : 'h3';
$image_size    = isset( $args['image_size'] ) ? $args['image_size'] : ( 'hero-main' === $variant ? 'hs-hero' : 'hs-card' );
$loading       = isset( $args['loading'] ) && 'eager' === $args['loading'] ? 'eager' : 'lazy';
$category      = hs_get_primary_category();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'hs-card hs-card--' . $variant ); ?>>
	<a class="hs-card-image" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php
			the_post_thumbnail(
				$image_size,
				array(
					'loading'  => $loading,
					'decoding' => 'async',
				)
			);
			?>
		<?php else : ?>
			<span class="hs-image-placeholder" aria-hidden="true">HolaSalta</span>
		<?php endif; ?>
	</a>

	<div class="hs-card-content">
		<?php if ( $category ) : ?>
			<a class="hs-category" href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>">
				<?php echo esc_html( $category->name ); ?>
			</a>
		<?php endif; ?>

		<<?php echo esc_html( $heading_level ); ?> class="hs-card-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</<?php echo esc_html( $heading_level ); ?>>

		<time class="hs-meta" datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
			<?php echo esc_html( get_the_date() ); ?>
		</time>

		<?php if ( $show_excerpt && get_the_excerpt() ) : ?>
			<p class="hs-card-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
		<?php endif; ?>
	</div>
</article>

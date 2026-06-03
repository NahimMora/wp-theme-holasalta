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
$section_attr  = $category ? ' data-section="' . esc_attr( $category->slug ) . '"' : '';

if ( 'compact' === $variant ) :
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'hs-card hs-card--compact' ); ?><?php echo $section_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<a class="hs-card-thumb" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'hs-card', array( 'loading' => $loading, 'decoding' => 'async' ) ); ?>
			<?php else : ?>
				<span class="hs-image-placeholder" aria-hidden="true">HS</span>
			<?php endif; ?>
		</a>
		<div class="hs-card-content">
			<p class="hs-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
			<time class="hs-meta" datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
		</div>
	</article>
	<?php
	return;
endif;

if ( 'hero-main' === $variant ) :
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'hs-card hs-card--hero-main' ); ?><?php echo $section_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>

		<div class="hs-hero-image<?php echo has_post_thumbnail() ? '' : ' hs-hero-image--fallback'; ?>" aria-hidden="true">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php
				the_post_thumbnail(
					$image_size,
					array(
						'loading'        => $loading,
						'decoding'       => 'async',
						'fetchpriority'  => 'eager' === $loading ? 'high' : 'auto',
					)
				);
				?>
			<?php endif; ?>
		</div>

		<div class="hs-hero-overlay">
			<?php if ( $category ) : ?>
				<a class="hs-hero-category" href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>">
					<?php echo esc_html( $category->name ); ?>
				</a>
			<?php endif; ?>

			<<?php echo esc_html( $heading_level ); ?> class="hs-hero-title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</<?php echo esc_html( $heading_level ); ?>>

			<time class="hs-hero-meta" datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
				<?php echo esc_html( get_the_date() ); ?>
			</time>

			<?php if ( $show_excerpt && get_the_excerpt() ) : ?>
				<p class="hs-hero-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28 ) ); ?></p>
			<?php endif; ?>
		</div>

	</article>
	<?php
	return;
endif;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'hs-card hs-card--' . $variant ); ?><?php echo $section_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
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

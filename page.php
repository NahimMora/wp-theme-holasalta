<?php
/**
 * Institutional and standard page template.
 *
 * Layout:
 *   - Breadcrumb: Inicio > (página padre >) Título.
 *   - Kicker: nombre de la página padre o "HolaSalta".
 *   - Imagen destacada si existe.
 *   - Contenido en columna estrecha (820px).
 *
 * @package HolaSalta_Child
 */

get_header();
?>

<div class="hs-page-shell hs-page-shell--page">
	<div class="hs-container hs-container--narrow">
		<?php
		while ( have_posts() ) :
			the_post();

			$parent_id = wp_get_post_parent_id( get_the_ID() );
			?>

			<nav class="hs-breadcrumb" aria-label="<?php esc_attr_e( 'Migas de pan', 'holasalta-child' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Inicio', 'holasalta-child' ); ?></a>
				<span aria-hidden="true">/</span>
				<?php if ( $parent_id ) : ?>
					<a href="<?php echo esc_url( get_permalink( $parent_id ) ); ?>"><?php echo esc_html( get_the_title( $parent_id ) ); ?></a>
					<span aria-hidden="true">/</span>
				<?php endif; ?>
			</nav>

			<article id="post-<?php the_ID(); ?>" <?php post_class( 'hs-static-page' ); ?>>
				<header class="hs-static-page-header">
					<span class="hs-category-kicker">HolaSalta</span>
					<h1><?php the_title(); ?></h1>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="hs-static-page-figure">
						<?php the_post_thumbnail( 'hs-hero', array( 'loading' => 'eager', 'decoding' => 'async' ) ); ?>
						<?php
						$caption = get_the_post_thumbnail_caption();
						if ( $caption ) :
						?>
							<figcaption><?php echo esc_html( $caption ); ?></figcaption>
						<?php endif; ?>
					</figure>
				<?php endif; ?>

				<div class="hs-static-page-content">
					<?php
					the_content();
					wp_link_pages();
					?>
				</div>
			</article>

		<?php endwhile; ?>
	</div>
</div>

<?php get_footer(); ?>


<?php
/**
 * General archive fallback.
 *
 * @package HolaSalta_Child
 */

get_header();
?>

<div class="hs-page-shell">
	<div class="hs-container">
		<header class="hs-page-header">
			<p class="hs-kicker"><?php esc_html_e( 'Archivo', 'holasalta-child' ); ?></p>
			<?php the_archive_title( '<h1>', '</h1>' ); ?>
			<?php the_archive_description( '<div class="hs-page-description">', '</div>' ); ?>
		</header>

		<?php hs_render_ad_slot( 'category-top', '728x90' ); ?>

		<div class="hs-archive-layout">
			<div class="hs-archive-content">
				<?php if ( have_posts() ) : ?>
					<div class="hs-grid hs-grid-archive">
						<?php
						while ( have_posts() ) :
							the_post();
							hs_render_post_card(
								array(
									'show_excerpt' => true,
								)
							);
						endwhile;
						?>
					</div>

					<?php hs_render_pagination(); ?>
				<?php else : ?>
					<p class="hs-empty"><?php esc_html_e( 'No se encontraron noticias para este archivo.', 'holasalta-child' ); ?></p>
				<?php endif; ?>
			</div>

			<?php get_template_part( 'template-parts/sidebar-news' ); ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>


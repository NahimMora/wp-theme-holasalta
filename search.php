<?php
/**
 * Search results template.
 *
 * @package HolaSalta_Child
 */

get_header();
?>

<div class="hs-page-shell">
	<div class="hs-container">
		<header class="hs-page-header">
			<p class="hs-kicker"><?php esc_html_e( 'Búsqueda', 'holasalta-child' ); ?></p>
			<h1>
				<?php
				echo esc_html(
					sprintf(
						/* translators: %s: search query. */
						__( 'Resultados para: %s', 'holasalta-child' ),
						get_search_query()
					)
				);
				?>
			</h1>
		</header>

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
			<div class="hs-empty">
				<p><?php esc_html_e( 'No encontramos noticias con esos términos.', 'holasalta-child' ); ?></p>
				<?php get_search_form(); ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php get_footer(); ?>


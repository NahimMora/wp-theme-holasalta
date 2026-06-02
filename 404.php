<?php
/**
 * Not found (404) template.
 *
 * Layout:
 *   - Bloque de error: mensaje + buscador + botón de inicio.
 *   - Sección de últimas noticias: 6 posts en grilla compacta.
 *
 * @package HolaSalta_Child
 */

get_header();
?>

<div class="hs-page-shell hs-page-shell--404">
	<div class="hs-container">

		<section class="hs-not-found">
			<span class="hs-category-kicker">Error 404</span>
			<h1><?php esc_html_e( 'No encontramos esa página', 'holasalta-child' ); ?></h1>
			<p><?php esc_html_e( 'Puede que el enlace haya cambiado o que el contenido ya no esté disponible.', 'holasalta-child' ); ?></p>
			<?php get_search_form(); ?>
			<a class="hs-button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Volver al inicio', 'holasalta-child' ); ?></a>
		</section>

		<?php
		$recent_posts = new WP_Query(
			array(
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'posts_per_page'      => 6,
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			)
		);

		if ( $recent_posts->have_posts() ) :
		?>
			<div class="hs-404-recent">
				<h2 class="hs-404-recent-title"><?php esc_html_e( 'Últimas noticias', 'holasalta-child' ); ?></h2>
				<div class="hs-grid hs-grid-archive">
					<?php
					while ( $recent_posts->have_posts() ) :
						$recent_posts->the_post();
						hs_render_post_card();
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</div>
		<?php endif; ?>

	</div>
</div>

<?php get_footer(); ?>


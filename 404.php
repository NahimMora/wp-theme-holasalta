<?php
/**
 * Not found template.
 *
 * @package HolaSalta_Child
 */

get_header();
?>

<div class="hs-page-shell">
	<div class="hs-container hs-container--narrow">
		<section class="hs-not-found">
			<p class="hs-kicker">Error 404</p>
			<h1><?php esc_html_e( 'No encontramos esa página', 'holasalta-child' ); ?></h1>
			<p><?php esc_html_e( 'Puede que el enlace haya cambiado o que el contenido ya no esté disponible.', 'holasalta-child' ); ?></p>
			<?php get_search_form(); ?>
			<a class="hs-button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Volver al inicio', 'holasalta-child' ); ?></a>
		</section>
	</div>
</div>

<?php get_footer(); ?>


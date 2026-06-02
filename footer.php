<?php
/**
 * Custom HolaSalta footer, preserving Blocksy's template lifecycle.
 *
 * @package HolaSalta_Child
 */

blocksy_after_current_template();
do_action( 'blocksy:content:bottom' );

$footer_sections = array_slice( hs_get_editorial_sections(), 0, 6, true );
?>
	</main>

	<?php
	do_action( 'blocksy:content:after' );
	do_action( 'blocksy:footer:before' );
	?>

	<footer class="hs-site-footer hs-footer">
		<div class="hs-container hs-footer-grid">

			<div class="hs-footer-brand">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<a class="hs-footer-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">Hola<span>Salta</span></a>
				<?php endif; ?>

				<p><?php esc_html_e( 'Medio digital de noticias de Salta, con actualidad local, policiales, política, sociedad, deportes y nacionales.', 'holasalta-child' ); ?></p>

				<nav class="hs-footer-social" aria-label="<?php esc_attr_e( 'Redes sociales', 'holasalta-child' ); ?>">
					<?php foreach ( hs_get_social_links() as $social ) : ?>
						<a href="<?php echo esc_url( $social['url'] ); ?>" aria-label="<?php echo esc_attr( $social['label'] ); ?>">
							<?php echo esc_html( $social['short'] ); ?>
						</a>
					<?php endforeach; ?>
				</nav>
			</div>

			<div class="hs-footer-column">
				<h2><?php esc_html_e( 'Institucional', 'holasalta-child' ); ?></h2>
				<?php
				wp_nav_menu(
					array(
						'theme_location'       => 'footer',
						'container'            => 'nav',
						'container_aria_label' => __( 'Menú institucional', 'holasalta-child' ),
						'fallback_cb'          => 'hs_footer_menu_fallback',
						'depth'                => 1,
					)
				);
				?>
			</div>

			<div class="hs-footer-column">
				<h2><?php esc_html_e( 'Secciones', 'holasalta-child' ); ?></h2>
				<nav aria-label="<?php esc_attr_e( 'Categorías principales', 'holasalta-child' ); ?>">
					<ul>
						<?php foreach ( $footer_sections as $slug => $label ) : ?>
							<li><a href="<?php echo esc_url( hs_get_section_url( $slug ) ); ?>"><?php echo esc_html( $label ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</nav>
			</div>

		</div>

		<div class="hs-footer-bottom">
			<div class="hs-container hs-footer-bottom-inner">
				<p>
					<?php
					echo esc_html(
						sprintf(
							/* translators: %s: current year. */
							__( '© %s HolaSalta.com — Todos los derechos reservados.', 'holasalta-child' ),
							wp_date( 'Y' )
						)
					);
					?>
				</p>
				<p class="hs-footer-direccion"><?php esc_html_e( 'Dirección a cargo de Romina Luna', 'holasalta-child' ); ?></p>
				<p class="hs-footer-location"><?php esc_html_e( 'Salta, Argentina', 'holasalta-child' ); ?></p>
			</div>
		</div>
	</footer>

	<?php do_action( 'blocksy:footer:after' ); ?>
</div>

<?php wp_footer(); ?>

</body>
</html>

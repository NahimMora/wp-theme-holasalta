<?php
/**
 * Editorial HolaSalta header.
 *
 * Keeps Blocksy's document lifecycle while replacing its visual header.
 *
 * @package HolaSalta_Child
 */

?><!doctype html>
<html <?php language_attributes(); ?><?php echo blocksy_html_attr(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
<head>
	<?php do_action( 'blocksy:head:start' ); ?>

	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, viewport-fit=cover">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
	<?php do_action( 'blocksy:head:end' ); ?>
</head>

<body <?php body_class(); ?> <?php echo blocksy_body_attr(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>

<?php
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}
?>

<div id="main-container">
	<?php do_action( 'blocksy:header:before' ); ?>

	<header class="hs-site-header">
		<div class="hs-container hs-header-inner">
			<nav class="hs-header-social" aria-label="<?php esc_attr_e( 'Redes sociales', 'holasalta-child' ); ?>">
				<?php foreach ( hs_get_social_links() as $social ) : ?>
					<a href="<?php echo esc_url( $social['url'] ); ?>" aria-label="<?php echo esc_attr( $social['label'] ); ?>">
						<?php echo esc_html( $social['short'] ); ?>
					</a>
				<?php endforeach; ?>
			</nav>

			<div class="hs-header-brand">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<a class="hs-header-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						Hola<span>Salta</span>
					</a>
				<?php endif; ?>
			</div>

			<div class="hs-header-actions">
				<form class="hs-header-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<label class="screen-reader-text" for="hs-header-search-input"><?php esc_html_e( 'Buscar noticias', 'holasalta-child' ); ?></label>
					<input id="hs-header-search-input" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Buscar noticias...', 'holasalta-child' ); ?>">
					<button type="submit"><?php esc_html_e( 'Buscar', 'holasalta-child' ); ?></button>
				</form>

				<button class="hs-sections-toggle" type="button" aria-expanded="false" aria-controls="hs-sections-panel" aria-label="<?php esc_attr_e( 'Abrir menú de secciones', 'holasalta-child' ); ?>">
					<span class="hs-sections-toggle-label"><?php esc_html_e( 'Secciones', 'holasalta-child' ); ?></span>
					<span class="hs-hamburger" aria-hidden="true"><span></span><span></span><span></span></span>
				</button>
			</div>
		</div>

		<div id="hs-sections-panel" class="hs-sections-panel" hidden>
			<div class="hs-container hs-sections-panel-inner">
				<div class="hs-sections-panel-header">
					<p><?php esc_html_e( 'Secciones', 'holasalta-child' ); ?></p>
					<button class="hs-sections-close" type="button" aria-label="<?php esc_attr_e( 'Cerrar menú de secciones', 'holasalta-child' ); ?>">×</button>
				</div>
				<nav aria-label="<?php esc_attr_e( 'Secciones de noticias', 'holasalta-child' ); ?>">
					<ul>
						<?php foreach ( hs_get_editorial_sections() as $slug => $label ) : ?>
							<li><a href="<?php echo esc_url( hs_get_section_url( $slug ) ); ?>"><?php echo esc_html( $label ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</nav>
			</div>
		</div>
	</header>

	<?php
	do_action( 'blocksy:header:after' );
	do_action( 'blocksy:content:before' );
	?>

	<main <?php echo blocksy_main_attr(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<?php
		do_action( 'blocksy:content:top' );
		blocksy_before_current_template();
		?>

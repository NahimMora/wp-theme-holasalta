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

			<?php
			$hs_social_icons = array(
				'instagram' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>',
				'facebook'  => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
				'x'         => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.741l7.726-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
				'whatsapp'  => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>',
			);
			?>
			<nav class="hs-header-social" aria-label="<?php esc_attr_e( 'Redes sociales', 'holasalta-child' ); ?>">
				<?php foreach ( hs_get_social_links() as $key => $social ) : ?>
					<a class="hs-social-<?php echo esc_attr( $key ); ?>" href="<?php echo esc_url( $social['url'] ); ?>" target="_blank" rel="noopener noreferrer nofollow" aria-label="<?php echo esc_attr( $social['label'] ); ?>">
						<?php
						if ( isset( $hs_social_icons[ $key ] ) ) {
							echo $hs_social_icons[ $key ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						?>
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
				<button class="hs-search-toggle" type="button" aria-expanded="false" aria-controls="hs-search-panel" aria-label="<?php esc_attr_e( 'Buscar noticias', 'holasalta-child' ); ?>">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
						<circle cx="11" cy="11" r="8"/>
						<line x1="21" y1="21" x2="16.65" y2="16.65"/>
					</svg>
				</button>

				<button class="hs-sections-toggle" type="button" aria-expanded="false" aria-controls="hs-sections-panel" aria-label="<?php esc_attr_e( 'Abrir secciones', 'holasalta-child' ); ?>">
					<span class="hs-sections-toggle-label"><?php esc_html_e( 'Secciones', 'holasalta-child' ); ?></span>
					<svg class="hs-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
						<polyline points="6 9 12 15 18 9"/>
					</svg>
					<svg class="hs-hamburger" width="20" height="14" viewBox="0 0 20 14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" focusable="false">
						<line x1="0" y1="1" x2="20" y2="1"/>
						<line x1="0" y1="7" x2="20" y2="7"/>
						<line x1="0" y1="13" x2="20" y2="13"/>
					</svg>
				</button>
			</div>
		</div>

		<div id="hs-search-panel" class="hs-search-panel" hidden>
			<div class="hs-container">
				<form class="hs-search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<label class="screen-reader-text" for="hs-search-input"><?php esc_html_e( 'Buscar noticias', 'holasalta-child' ); ?></label>
					<input id="hs-search-input" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Buscar noticias...', 'holasalta-child' ); ?>" autocomplete="off">
					<button type="submit" aria-label="<?php esc_attr_e( 'Buscar', 'holasalta-child' ); ?>">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
							<circle cx="11" cy="11" r="8"/>
							<line x1="21" y1="21" x2="16.65" y2="16.65"/>
						</svg>
					</button>
					<button type="button" class="hs-search-close" aria-label="<?php esc_attr_e( 'Cerrar búsqueda', 'holasalta-child' ); ?>">×</button>
				</form>
			</div>
		</div>

	</header>

	<div id="hs-sections-panel" class="hs-sections-panel" hidden role="dialog" aria-label="<?php esc_attr_e( 'Secciones', 'holasalta-child' ); ?>" aria-modal="true">
		<div class="hs-drawer-header">
			<span class="hs-drawer-title"><?php esc_html_e( 'Secciones', 'holasalta-child' ); ?></span>
			<button class="hs-drawer-close" type="button" aria-label="<?php esc_attr_e( 'Cerrar secciones', 'holasalta-child' ); ?>">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true" focusable="false">
					<line x1="18" y1="6" x2="6" y2="18"/>
					<line x1="6" y1="6" x2="18" y2="18"/>
				</svg>
			</button>
		</div>

		<nav class="hs-drawer-nav" aria-label="<?php esc_attr_e( 'Secciones de noticias', 'holasalta-child' ); ?>">
			<ul>
				<?php foreach ( hs_get_editorial_sections() as $slug => $label ) : ?>
					<li>
						<a href="<?php echo esc_url( hs_get_section_url( $slug ) ); ?>"><?php echo esc_html( $label ); ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</nav>

		<?php
		$drawer_posts = hs_get_posts_by_category( '', 4 );
		if ( $drawer_posts->have_posts() ) :
		?>
		<div class="hs-drawer-recents">
			<h3 class="hs-drawer-recents-title"><?php esc_html_e( 'Lo último', 'holasalta-child' ); ?></h3>
			<ul class="hs-drawer-post-list">
				<?php
				while ( $drawer_posts->have_posts() ) :
					$drawer_posts->the_post();
					$drawer_cat = hs_get_primary_category();
					?>
					<li class="hs-drawer-post-item">
						<a href="<?php echo esc_url( get_permalink() ); ?>">
							<?php the_title(); ?>
						</a>
						<div class="hs-drawer-post-meta">
							<?php if ( $drawer_cat ) : ?>
								<span class="hs-drawer-post-cat"><?php echo esc_html( $drawer_cat->name ); ?></span>
							<?php endif; ?>
							<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
						</div>
					</li>
				<?php endwhile; wp_reset_postdata(); ?>
			</ul>
		</div>
		<?php endif; ?>
	</div>

	<div class="hs-sections-backdrop" aria-hidden="true"></div>

	<?php
	do_action( 'blocksy:header:after' );
	do_action( 'blocksy:content:before' );
	?>

	<main <?php echo blocksy_main_attr(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<?php
		do_action( 'blocksy:content:top' );
		blocksy_before_current_template();
		?>

<?php
/**
 * Reusable template helpers.
 *
 * @package HolaSalta_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the editorial sections shown in navigation and the homepage.
 *
 * @return array<string, string>
 */
function hs_get_editorial_sections() {
	return array(
		'salta'           => __( 'Salta', 'holasalta-child' ),
		'policiales'      => __( 'Policiales', 'holasalta-child' ),
		'nacionales'      => __( 'Nacionales', 'holasalta-child' ),
		'deportes'        => __( 'Deportes', 'holasalta-child' ),
		'espectaculos'    => __( 'Espectáculos', 'holasalta-child' ),
		'internacionales' => __( 'Internacionales', 'holasalta-child' ),
		'sabias-que'      => __( '¿Sabías que?', 'holasalta-child' ),
		'columnas'        => __( 'Columnas', 'holasalta-child' ),
	);
}

/**
 * Returns a category URL with a predictable fallback before setup runs.
 *
 * @param string $slug Category slug.
 * @return string
 */
function hs_get_section_url( $slug ) {
	$category = get_category_by_slug( sanitize_title( $slug ) );

	return $category ? get_category_link( $category->term_id ) : home_url( '/category/' . sanitize_title( $slug ) . '/' );
}

/**
 * Returns the social links used by the header and footer.
 *
 * Replace the placeholder URLs here when the official profiles are ready.
 *
 * @return array<string, array<string, string>>
 */
function hs_get_social_links() {
	return array(
		'instagram' => array(
			'label' => 'Instagram',
			'short' => 'IG',
			'url'   => 'https://www.instagram.com/holasalta/',
		),
		'facebook'  => array(
			'label' => 'Facebook',
			'short' => 'FB',
			'url'   => 'https://www.facebook.com/HolaSaltaNoticiasYa',
		),
		'x'         => array(
			'label' => 'X',
			'short' => 'X',
			'url'   => 'https://x.com/HolaSaltaYa',
		),
		'whatsapp'  => array(
			'label' => 'WhatsApp',
			'short' => 'WA',
			'url'   => 'https://wa.me/5493875230770',
		),
	);
}

/**
 * Prints institutional links when no footer menu was assigned yet.
 */
function hs_footer_menu_fallback() {
	$pages = array(
		'quienes-somos'        => __( 'Quiénes somos', 'holasalta-child' ),
		'contacto'             => __( 'Contacto', 'holasalta-child' ),
		'publicidad'           => __( 'Publicidad', 'holasalta-child' ),
		'politica-editorial'   => __( 'Política editorial', 'holasalta-child' ),
		'politica-privacidad'  => __( 'Política de privacidad', 'holasalta-child' ),
		'terminos-condiciones' => __( 'Términos y condiciones', 'holasalta-child' ),
	);
	?>
	<nav aria-label="<?php esc_attr_e( 'Menú institucional', 'holasalta-child' ); ?>">
		<ul>
			<?php foreach ( $pages as $slug => $label ) : ?>
				<?php $page = get_page_by_path( $slug ); ?>
				<li><a href="<?php echo esc_url( $page ? get_permalink( $page ) : home_url( '/' . $slug . '/' ) ); ?>"><?php echo esc_html( $label ); ?></a></li>
			<?php endforeach; ?>
		</ul>
	</nav>
	<?php
}

/**
 * Returns a small post query, optionally filtered by category slug.
 *
 * @param string $slug  Category slug. Leave empty for all posts.
 * @param int    $count Number of posts.
 * @param array  $args  Additional WP_Query arguments.
 * @return WP_Query
 */
function hs_get_posts_by_category( $slug = '', $count = 8, $args = array() ) {
	$defaults = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => absint( $count ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);

	if ( $slug ) {
		$defaults['category_name'] = sanitize_title( $slug );
	}

	return new WP_Query( wp_parse_args( $args, $defaults ) );
}

/**
 * Renders one reusable post card.
 *
 * @param array $args Card display options.
 */
function hs_render_post_card( $args = array() ) {
	get_template_part( 'template-parts/card-post', null, $args );
}

/**
 * Maps an ad placement to its widget area.
 *
 * @param string $slot Ad placement.
 * @return string
 */
function hs_get_ad_sidebar_id( $slot ) {
	$slots = array(
		'home-top'      => 'hs-ad-home-top',
		'home-strip'    => 'hs-ad-home-strip',
		'home-middle'   => 'hs-ad-home-middle',
		'home-square-1' => 'hs-ad-home-square-1',
		'home-square-2' => 'hs-ad-home-square-2',
		'home-square-3' => 'hs-ad-home-square-3',
		'home-bottom'   => 'hs-ad-home-bottom',
		'single-top'      => 'hs-ad-single-top',
		'single-middle'   => 'hs-ad-single-middle',
		'single-inline'   => 'hs-ad-single-inline',
		'single-square-1' => 'hs-ad-single-square-1',
		'single-square-2' => 'hs-ad-single-square-2',
		'single-square-3' => 'hs-ad-single-square-3',
		'category-top'  => 'hs-ad-category-top',
	);

	return isset( $slots[ $slot ] ) ? $slots[ $slot ] : '';
}

/**
 * Renders an advertising template part.
 *
 * @param string $slot Ad placement.
 * @param string $size Suggested banner size.
 */
function hs_render_ad_slot( $slot, $size = '728x90' ) {
	get_template_part(
		'template-parts/ad-slot',
		null,
		array(
			'slot' => $slot,
			'size' => $size,
		)
	);
}

/**
 * Returns the most relevant category for a post.
 *
 * The editorial helper prefers a section over the "Destacadas" label.
 *
 * @param int $post_id Post ID.
 * @return WP_Term|null
 */
function hs_get_primary_category( $post_id = 0 ) {
	$categories = get_the_category( $post_id );

	if ( ! $categories ) {
		return null;
	}

	return $categories[0];
}

/**
 * Returns related stories from the current article categories.
 *
 * @param int $post_id Current post ID.
 * @param int $count   Number of related posts.
 * @return WP_Query
 */
function hs_get_related_posts( $post_id = 0, $count = 4 ) {
	$post_id    = $post_id ? absint( $post_id ) : get_the_ID();
	$categories = wp_get_post_categories( $post_id );

	return hs_get_posts_by_category(
		'',
		$count,
		array(
			'category__in' => $categories,
			'post__not_in' => array( $post_id ),
		)
	);
}

/**
 * Returns the configured posts page URL, with an archive fallback.
 *
 * @return string
 */
function hs_get_posts_page_url() {
	$page_for_posts = (int) get_option( 'page_for_posts' );

	if ( $page_for_posts ) {
		return get_permalink( $page_for_posts );
	}

	return home_url( '/' );
}

/**
 * Returns estimated reading time in minutes for a post.
 *
 * @param int $post_id Post ID. Defaults to current post.
 * @return int Minutes (minimum 1).
 */
function hs_reading_time( $post_id = 0 ) {
	$content = get_post_field( 'post_content', $post_id ?: get_the_ID() );
	$words   = str_word_count( wp_strip_all_tags( $content ) );
	return max( 1, (int) ceil( $words / 200 ) );
}

/**
 * Prints accessible numbered pagination for archive views.
 */
function hs_render_pagination() {
	the_posts_pagination(
		array(
			'mid_size'  => 1,
			'prev_text' => __( 'Anterior', 'holasalta-child' ),
			'next_text' => __( 'Siguiente', 'holasalta-child' ),
		)
	);
}

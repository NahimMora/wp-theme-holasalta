<?php
/**
 * Single news article template.
 *
 * @package HolaSalta_Child
 */

get_header();
?>

<div id="hs-reading-progress" role="progressbar" aria-hidden="true"></div>

<div class="hs-page-shell hs-page-shell--single">
	<div class="hs-container">
		<?php
		while ( have_posts() ) :
			the_post();

			$category       = hs_get_primary_category();
			$permalink      = get_permalink();
			$share_url      = rawurlencode( $permalink );
			$share_title    = rawurlencode( get_the_title() );
			$whatsapp_url   = 'https://wa.me/?text=' . $share_title . '%20' . $share_url;
			$facebook_url   = 'https://www.facebook.com/sharer/sharer.php?u=' . $share_url;
			$x_url          = 'https://twitter.com/intent/tweet?url=' . $share_url . '&text=' . $share_title;
			$is_updated     = get_the_modified_time( 'U' ) > get_the_time( 'U' );
			?>

			<div class="hs-single-layout">
				<div class="hs-single-content">
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'hs-article' ); ?>>
						<nav class="hs-breadcrumb" aria-label="<?php esc_attr_e( 'Migas de pan', 'holasalta-child' ); ?>">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Inicio', 'holasalta-child' ); ?></a>
							<span aria-hidden="true">/</span>
							<?php if ( $category ) : ?>
								<a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>"><?php echo esc_html( $category->name ); ?></a>
							<?php endif; ?>
						</nav>

						<header class="hs-article-header">
							<?php if ( $category ) : ?>
								<a class="hs-category" href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>">
									<?php echo esc_html( $category->name ); ?>
								</a>
							<?php endif; ?>

							<h1><?php the_title(); ?></h1>

							<?php if ( has_excerpt() ) : ?>
								<p class="hs-article-lead"><?php echo esc_html( get_the_excerpt() ); ?></p>
							<?php endif; ?>

							<div class="hs-article-meta">
								<span>
									<?php esc_html_e( 'Por', 'holasalta-child' ); ?>
									<a href="<?php echo esc_url( get_author_posts_url( (int) get_the_author_meta( 'ID' ) ) ); ?>">
										<?php echo esc_html( get_the_author() ); ?>
									</a>
								</span>
								<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
									<?php echo esc_html( get_the_date() ); ?>
								</time>
								<?php if ( $is_updated ) : ?>
									<time datetime="<?php echo esc_attr( get_the_modified_date( DATE_W3C ) ); ?>">
										<?php
										echo esc_html(
											sprintf(
												/* translators: %s: modified date. */
												__( 'Actualizada: %s', 'holasalta-child' ),
												get_the_modified_date()
											)
										);
										?>
									</time>
								<?php endif; ?>
								<?php $reading_time = hs_reading_time(); ?>
								<span class="hs-reading-time">
									<?php
									echo esc_html(
										sprintf(
											/* translators: %d: reading time in minutes. */
											_n( '%d min de lectura', '%d min de lectura', $reading_time, 'holasalta-child' ),
											$reading_time
										)
									);
									?>
								</span>
							</div>
						</header>

						<?php if ( has_post_thumbnail() ) : ?>
							<figure class="hs-article-image">
								<?php
								the_post_thumbnail(
									'hs-hero',
									array(
										'loading'  => 'eager',
										'decoding' => 'async',
									)
								);
								?>
								<?php if ( wp_get_attachment_caption( get_post_thumbnail_id() ) ) : ?>
									<figcaption><?php echo esc_html( wp_get_attachment_caption( get_post_thumbnail_id() ) ); ?></figcaption>
								<?php endif; ?>
							</figure>
						<?php endif; ?>

						<div class="hs-article-body">
							<?php
							the_content();
							wp_link_pages();
							?>
						</div>

						<?php if ( get_the_tag_list() ) : ?>
							<div class="hs-tags">
								<strong><?php esc_html_e( 'Etiquetas:', 'holasalta-child' ); ?></strong>
								<?php echo wp_kses_post( get_the_tag_list( '', ' ' ) ); ?>
							</div>
						<?php endif; ?>

						<section class="hs-share" aria-label="<?php esc_attr_e( 'Compartir noticia', 'holasalta-child' ); ?>">
							<strong><?php esc_html_e( 'Compartir:', 'holasalta-child' ); ?></strong>
							<a class="hs-share-wa" href="<?php echo esc_url( $whatsapp_url ); ?>" target="_blank" rel="noopener noreferrer nofollow">WhatsApp</a>
							<a class="hs-share-fb" href="<?php echo esc_url( $facebook_url ); ?>" target="_blank" rel="noopener noreferrer nofollow">Facebook</a>
							<a class="hs-share-x" href="<?php echo esc_url( $x_url ); ?>" target="_blank" rel="noopener noreferrer nofollow">X</a>
						</section>
					</article>

					<?php
					$author_id  = (int) get_the_author_meta( 'ID' );
					$author_bio = get_the_author_meta( 'description' );
					if ( $author_bio ) :
					?>
					<div class="hs-author-block">
						<div class="hs-author-avatar">
							<?php echo get_avatar( $author_id, 64, '', get_the_author(), array( 'class' => 'hs-avatar' ) ); ?>
						</div>
						<div class="hs-author-info">
							<span class="hs-author-label"><?php esc_html_e( 'Periodista', 'holasalta-child' ); ?></span>
							<a class="hs-author-name" href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>">
								<?php echo esc_html( get_the_author() ); ?>
							</a>
							<p class="hs-author-bio"><?php echo esc_html( $author_bio ); ?></p>
						</div>
					</div>
					<?php endif; ?>

					<?php get_template_part( 'template-parts/related-posts' ); ?>

					<?php
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
					?>
				</div>

				<?php
				get_template_part(
					'template-parts/sidebar-news',
					null,
					array(
						'context'  => 'single',
						'post_id'  => get_the_ID(),
						'category' => $category,
					)
				);
				?>
			</div>

			<?php
			$latest_news = hs_get_posts_by_category(
				'',
				6,
				array( 'post__not_in' => array( get_the_ID() ) )
			);
			if ( $latest_news->have_posts() ) :
			?>
			<section class="hs-latest-section" aria-labelledby="hs-latest-heading">
				<header class="hs-section-header">
					<h2 id="hs-latest-heading"><?php esc_html_e( 'Últimas noticias', 'holasalta-child' ); ?></h2>
					<a class="hs-more-link" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php esc_html_e( 'Ver todo', 'holasalta-child' ); ?> →
					</a>
				</header>
				<div class="hs-grid hs-grid-latest">
					<?php
					while ( $latest_news->have_posts() ) :
						$latest_news->the_post();
						hs_render_post_card();
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</section>
			<?php endif; ?>

			<div class="hs-ad-squares-row" aria-label="<?php esc_attr_e( 'Publicidad', 'holasalta-child' ); ?>">
				<?php hs_render_ad_slot( 'single-square-1', '450x450' ); ?>
				<?php hs_render_ad_slot( 'single-square-2', '450x450' ); ?>
				<?php hs_render_ad_slot( 'single-square-3', '450x450' ); ?>
			</div>

			<nav class="hs-share-sticky" aria-label="<?php esc_attr_e( 'Compartir noticia', 'holasalta-child' ); ?>">
				<a class="hs-share-wa" href="<?php echo esc_url( $whatsapp_url ); ?>" target="_blank" rel="noopener noreferrer nofollow" aria-label="<?php esc_attr_e( 'Compartir por WhatsApp', 'holasalta-child' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
				</a>
				<a class="hs-share-fb" href="<?php echo esc_url( $facebook_url ); ?>" target="_blank" rel="noopener noreferrer nofollow" aria-label="<?php esc_attr_e( 'Compartir en Facebook', 'holasalta-child' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
				</a>
				<a class="hs-share-x" href="<?php echo esc_url( $x_url ); ?>" target="_blank" rel="noopener noreferrer nofollow" aria-label="<?php esc_attr_e( 'Compartir en X', 'holasalta-child' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.741l7.726-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
				</a>
				<button type="button" class="hs-share-copy" aria-label="<?php esc_attr_e( 'Copiar enlace', 'holasalta-child' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
				</button>
			</nav>
		<?php endwhile; ?>
	</div>
</div>

<?php get_footer(); ?>

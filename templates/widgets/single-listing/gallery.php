<?php
/****
 * @var $use_slider
 * @var $show_zoom_icon
 * @var $show_overlay
 * @var $show_actions_onhover
 * */

global $listing_id;

$listing_id = ( is_null( $listing_id ) ) ? get_the_ID() : $listing_id;

// Getting gallery list.
$gallery           = get_post_meta( $listing_id, 'gallery', true );
$video_preview     = get_post_meta( $listing_id, 'video_preview', true );
$gallery_video     = get_post_meta( $listing_id, 'gallery_video', true );
$sold              = get_post_meta( $listing_id, 'car_mark_as_sold', true );
$sold_badge_color  = apply_filters( 'stm_me_get_nuxy_mod', '', 'sold_badge_bg_color' );
$special_car       = get_post_meta( $listing_id, 'special_car', true );
$badge_text        = get_post_meta( $listing_id, 'badge_text', true );
$badge_bg_color    = get_post_meta( $listing_id, 'badge_bg_color', true );
$big_gallery_id    = 'big-pictures-' . wp_rand( 1, 99999 );
$gallery_thumbs_id = 'thumbnails-' . wp_rand( 1, 99999 );
$car_brochure      = get_post_meta( $listing_id, 'car_brochure', true );

if ( empty( $badge_text ) ) {
	$badge_text = esc_html__( 'Special', 'motors-elementor-widgets' );
}

$badge_style = '';
if ( ! empty( $badge_bg_color ) ) {
	$badge_style = 'style=background-color:' . $badge_bg_color . ';';
}

if ( ! has_post_thumbnail() && stm_check_if_car_imported( $listing_id ) ) :
	?>
	<img
			src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/automanager_placeholders/plchldr798automanager.png' ); ?>"
			class="img-responsive"
			alt="<?php esc_attr_e( 'Placeholder', 'motors-elementor-widgets' ); ?>"
	/>
	<?php
endif;

$video_left         = ( ! empty( $show_pdf ) || ! empty( $show_print ) || ! empty( $show_featured ) || ! empty( $show_compare ) || ! empty( $show_test_drive ) || ! empty( $show_share ) ) ? 'video-left' : '';
$badge_style        = ' badge-' . $badge_position;
$use_slider_class   = ( $use_slider ) ? ' display-thumbnails' : ' no-thumbnails';
$actions_visibility = ( $show_actions_onhover ) ? ' actions-onhover' : '';
?>

<div class="motors-elementor-single-listing-gallery <?php echo esc_attr( $video_left . $badge_style . $actions_visibility . $use_slider_class ); ?>">

	<div class="stm-gallery-actions">
		<?php if ( 'yes' === $show_pdf && ! empty( $car_brochure ) ) : ?>
			<div class="stm-gallery-action-unit">
				<a href="<?php echo esc_url( wp_get_attachment_url( $car_brochure ) ); ?>" class="stm-brochure" title="<?php esc_html_e( 'Download brochure', 'motors-elementor-widgets' ); ?>" download>
					<i class="stm-icon-brochure"></i>
				</a>
			</div>
		<?php endif; ?>
		<?php if ( 'yes' === $show_print ) : ?>
			<div class="stm-gallery-action-unit stm-listing-print-action">
				<a href="javascript:window.print()" class="car-action-unit stm-car-print">
					<i class="fas fa-print"></i>
				</a>
			</div>
		<?php endif; ?>
		<?php if ( ! empty( $show_featured ) && 'yes' === $show_featured ) : ?>
			<div class="stm-gallery-action-unit stm-listing-favorite-action" data-id="<?php echo esc_attr( $listing_id ); ?>">
				<i class="stm-service-icon-staricon"></i>
			</div>
		<?php endif; ?>
		<?php if ( 'yes' === $show_compare ) : ?>
			<div class="stm-gallery-action-unit compare" data-id="<?php echo esc_attr( $listing_id ); ?>" data-title="<?php echo wp_kses_post( apply_filters( 'stm_generate_title_from_slugs', get_the_title( $listing_id ), $listing_id ) ); ?>" data-post-type="<?php echo esc_attr( get_post_type( $listing_id ) ); ?>" data-placement="bottom">
				<i class="stm-service-icon-compare-new"></i>
			</div>
		<?php endif; ?>
		<?php if ( 'yes' === $show_test_drive ) : ?>
			<div class="stm-gallery-action-unit stm-schedule" data-toggle="modal" data-target="#test-drive" onclick="stm_test_drive_car_title(<?php echo esc_js( $listing_id ); ?>, '<?php echo esc_js( get_the_title( $listing_id ) ); ?>')">
				<i class="stm-icon-steering_wheel"></i>
			</div>
		<?php endif; ?>
		<?php if ( 'yes' === $show_share ) : ?>
			<div class="stm-gallery-action-unit">
				<i class="stm-icon-share"></i>
				<?php if ( function_exists( 'ADDTOANY_SHARE_SAVE_KIT' ) && ! get_post_meta( $listing_id, 'sharing_disabled', true ) ) : ?>
					<div class="stm-a2a-popup">
						<?php echo stm_add_to_any_shortcode( $listing_id ); //phpcs:ignore ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>

	<!--New badge with videos-->
	<?php $car_media = apply_filters( 'stm_get_car_medias', array(), $listing_id ); ?>
	<?php if ( ! empty( $car_media['car_videos_count'] ) && $car_media['car_videos_count'] > 0 ) : ?>
		<div class="stm-car-medias">
			<div class="stm-listing-videos-unit stm-car-videos-<?php echo esc_attr( $listing_id ); ?>">
				<i class="fas fa-film"></i>
				<span><?php echo esc_html( $car_media['car_videos_count'] ); ?><?php esc_html_e( 'Video', 'motors-elementor-widgets' ); ?></span>
			</div>
		</div>
	<?php // @codingStandardsIgnoreStart ?>
		<script>
            jQuery(document).ready(function () {
                jQuery(".stm-car-videos-<?php echo esc_attr( $listing_id ); ?>").on('click', function () {
                    jQuery(this).lightGallery({
                        dynamic: true,
                        dynamicEl: [
							<?php foreach ( $car_media['car_videos'] as $car_video ) : ?>
                            {
                                src: "<?php echo esc_url( $car_video ); ?>"
                            },
							<?php endforeach; ?>
                        ],
                        download: false,
                        mode: 'lg-fade',
                    })
                }); //click
            }); //ready
		</script>
	<?php // @codingStandardsIgnoreEnd ?>
	<?php endif; ?>

	<?php // @codingStandardsIgnoreStart ?>
	<?php if ( ! empty( $gallery ) ) : ?>
		<script>
            jQuery(document).ready(function () {
                jQuery('.motors-elementor-big-gallery').lightGallery({
                    selector: '.stm_fancybox',
                    mode: 'lg-fade',
                    download: false,
                    thumbnail: true,
                });
            });
		</script>
	<?php endif; ?>
	<?php // @codingStandardsIgnoreEnd ?>

	<div class="swiper-container motors-elementor-big-gallery" id="<?php echo esc_attr( $big_gallery_id ); ?>">
		<div class="swiper-wrapper">
			<?php
			if ( has_post_thumbnail( $listing_id ) ) :
				$full_src = wp_get_attachment_image_src( get_post_thumbnail_id( $listing_id ), 'full' );
				// Post thumbnail first.
				?>
				<div class="stm-single-image swiper-slide" data-id="big-image-<?php echo esc_attr( get_post_thumbnail_id( $listing_id ) ); ?>">
					<a href="<?php echo esc_url( $full_src[0] ); ?>" class="stm_fancybox" rel="stm-car-gallery">
						<?php echo get_the_post_thumbnail( $listing_id, 'full', array( 'class' => 'img-responsive' ) ); ?>
						<?php if ( $show_overlay ) : ?>
							<span class="image-overlay"></span>
						<?php endif; ?>
						<?php if ( $show_zoom_icon ) : ?>
							<?php $icon_box_style = ( isset( $zoom_icon_box_style ) && 'none' !== $zoom_icon_box_style ) ? ' image-icon-' . $zoom_icon_box_style : ''; ?>
							<span class="image-icon<?php echo esc_attr( $icon_box_style ); ?>">
								<i class="stm-icon-light-zoom-in"></i>
							</span>
						<?php endif; ?>
					</a>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $video_preview ) && ! empty( $gallery_video ) && $use_slider ) : ?>
				<?php $src = wp_get_attachment_image_src( $video_preview, 'stm-img-796-466' ); ?>
				<?php if ( ! empty( $src[0] ) ) : ?>
					<div class="stm-single-image swiper-slide video-preview" data-id="big-image-<?php echo esc_attr( $video_preview ); ?>">
						<a class="fancy-iframe" data-iframe="true" data-src="<?php echo esc_url( $gallery_video ); ?>">
							<img src="<?php echo esc_url( $src[0] ); ?>" class="img-responsive" alt="<?php esc_attr_e( 'Video preview', 'motors-elementor-widgets' ); ?>"/>
						</a>
					</div>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ( ! empty( $gallery ) ) : ?>
				<?php foreach ( $gallery as $gallery_image ) : ?>
					<?php $src = wp_get_attachment_image_src( $gallery_image, 'full' ); ?>
					<?php $full_src = wp_get_attachment_image_src( $gallery_image, 'full' ); ?>
					<?php if ( ! empty( $src[0] ) && get_post_thumbnail_id( $listing_id ) !== $gallery_image ) : ?>
						<div class="stm-single-image swiper-slide" data-id="big-image-<?php echo esc_attr( $gallery_image ); ?>">
							<a href="<?php echo esc_url( $full_src[0] ); ?>" class="stm_fancybox" rel="stm-car-gallery">
								<img src="<?php echo esc_url( $src[0] ); ?>" alt="
								<?php
								printf(
								/* translators: post title */
									esc_attr__( '%s full', 'motors-elementor-widgets' ),
									esc_html( get_the_title( $listing_id ) )
								);
								?>
									"
								/>
								<?php if ( $show_overlay ) : ?>
									<span class="image-overlay"></span>
								<?php endif; ?>
								<?php if ( $show_zoom_icon ) : ?>
									<?php $icon_box_style = ( isset( $zoom_icon_box_style ) && 'none' !== $zoom_icon_box_style ) ? ' image-icon-' . $zoom_icon_box_style : ''; ?>
									<span class="image-icon<?php echo esc_attr( $icon_box_style ); ?>">
										<i class="stm-icon-light-zoom-in"></i>
									</span>
								<?php endif; ?>
							</a>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>

			<?php if ( ! empty( $car_media['car_videos_posters'] ) && ! empty( $car_media['car_videos'] ) && $use_slider ) : ?>
				<?php
				foreach ( $car_media['car_videos_posters'] as $k => $val ) :
					$src = wp_get_attachment_image_src( $val, 'stm-img-350-205' );
					$k ++;
					$video_source = ( isset( $car_media['car_videos'][ $k ] ) ) ? $car_media['car_videos'][ $k ] : '';
					if ( ! empty( $src[0] ) ) :
						?>
						<div class="stm-single-image swiper-slide video-preview" data-id="big-image-<?php echo esc_attr( $val ); ?>">
							<a class="fancy-iframe" data-iframe="true" data-src="<?php echo esc_url( $video_source ); ?>">
								<img src="<?php echo esc_url( $src[0] ); ?>" class="img-responsive" alt="<?php esc_attr_e( 'Video preview', 'motors-elementor-widgets' ); ?>"/>
							</a>
						</div>
						<?php
					endif;
				endforeach;
			endif;
			?>
		</div>
		<?php if ( empty( $sold ) && ! empty( $special_car ) && 'on' === $special_car ) : ?>
			<div class="special-label h5" <?php echo esc_attr( $badge_style ); ?>>
				<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $badge_text, 'Special Badge Text' ) ); ?>
			</div>
		<?php elseif ( true === apply_filters( 'stm_sold_status_enabled', false ) && ! empty( $sold ) ) : ?>
			<?php $badge_style = 'style=background-color:' . $sold_badge_color . ';'; ?>
			<div class="special-label h5" <?php echo esc_attr( $badge_style ); ?>>
				<?php esc_html_e( 'Sold', 'motors-elementor-widgets' ); ?>
			</div>
		<?php endif; ?>
	</div>

	<?php if ( $use_slider ) : ?>

		<div class="swiper-container motors-elementor-thumbs-gallery" id="<?php echo esc_attr( $gallery_thumbs_id ); ?>">
			<?php if ( has_post_thumbnail() || ( ! empty( $video_preview ) && ! empty( $gallery_video ) ) ) : ?>
				<div class="swiper-wrapper">

					<?php
					if ( has_post_thumbnail( $listing_id ) ) :
						// Post thumbnail first.
						?>
						<div class="stm-single-image swiper-slide" id="big-image-<?php echo esc_attr( get_post_thumbnail_id( $listing_id ) ); ?>">
							<?php echo get_the_post_thumbnail( $listing_id, 'stm-img-350-205', array( 'class' => 'img-responsive' ) ); ?>
							</div>
						<?php endif; ?>

					<?php if ( ! empty( $video_preview ) && ! empty( $gallery_video ) ) : ?>
						<?php $src = wp_get_attachment_image_src( $video_preview, 'stm-img-350-205' ); ?>
						<?php if ( ! empty( $src[0] ) ) : ?>
							<div class="stm-single-image swiper-slide video-preview" data-id="big-image-<?php echo esc_attr( $video_preview ); ?>">
								<a class="fancy-iframe" data-iframe="true" data-src="<?php echo esc_url( $gallery_video ); ?>">
									<img src="<?php echo esc_url( $src[0] ); ?>" alt="<?php esc_attr_e( 'Video preview', 'motors-elementor-widgets' ); ?>"/>
								</a>
							</div>
						<?php endif; ?>
					<?php endif; ?>

					<?php if ( ! empty( $gallery ) ) : ?>
						<?php foreach ( $gallery as $gallery_image ) : ?>
							<?php $src = wp_get_attachment_image_src( $gallery_image, 'stm-img-350-205' ); ?>
							<?php if ( ! empty( $src[0] ) && get_post_thumbnail_id( $listing_id ) !== $gallery_image ) : ?>
								<div class="stm-single-image swiper-slide" id="big-image-<?php echo esc_attr( $gallery_image ); ?>">
									<img src="<?php echo esc_url( $src[0] ); ?>" alt="
										<?php
										printf(
										/* translators: post title */
											esc_attr__( '%s full', 'motors-elementor-widgets' ),
											esc_html( get_the_title( $listing_id ) )
										);
										?>
									"/>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>

					<?php if ( ! empty( $car_media['car_videos_posters'] ) && ! empty( $car_media['car_videos'] ) ) : ?>
						<?php
						foreach ( $car_media['car_videos_posters'] as $k => $val ) :
							$k ++;
							$src          = wp_get_attachment_image_src( $val, 'stm-img-350-205' );
							$video_source = ( isset( $car_media['car_videos'][ $k ] ) ) ? $car_media['car_videos'][ $k ] : '';
							if ( ! empty( $src[0] ) ) :
								?>
								<div class="stm-single-image swiper-slide video-preview" data-id="big-image-<?php echo esc_attr( $video_preview ); ?>">
									<a class="fancy-iframe" data-iframe="true" data-src="<?php echo esc_url( $video_source ); ?>">
										<img src="<?php echo esc_url( $src[0] ); ?>" alt="<?php esc_attr_e( 'Video preview', 'motors-elementor-widgets' ); ?>"/>
									</a>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>

				</div>
				<div class="stm-swiper-controls">
					<div class="stm-swiper-prev"></div>
					<div class="stm-swiper-next"></div>
				</div>
			<?php endif; ?>
		</div>

	<?php endif; ?>

</div>

<?php if ( $use_slider ) : ?>
	<?php // @codingStandardsIgnoreStart ?>
	<!--Enable carousel-->
	<script>
		(function ($) {
			"use strict";
			<?php
			$is_elementor_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();
			if ( ! $is_elementor_editor ) :
			?>
			$(window).on('elementor/frontend/init', function () {
				<?php
				endif;
				?>
				var big_gallery = new Swiper("#<?php echo esc_attr( $big_gallery_id ); ?>", {
					loop: true,
					autoplay: false,
					slidesPerView: 'auto',
					simulateTouch: false,
					centeredSlides: true,
				});

				var small_thumbs = new Swiper("#<?php echo esc_attr( $gallery_thumbs_id ); ?>", {
					loop: true,
					spaceBetween: 23,
					slidesPerView: 'auto',
					centeredSlides: true,
					slideToClickedSlide: true,
					navigation: {
						nextEl: '.stm-swiper-next',
						prevEl: '.stm-swiper-prev',
					},
				});

				big_gallery.controller.control = small_thumbs;
				small_thumbs.controller.control = big_gallery;

				<?php
				if ( ! $is_elementor_editor ) :
				?>
			});
			<?php
			endif;
			?>
		}(jQuery));
	</script>
	<?php // @codingStandardsIgnoreEnd ?>
<?php endif; ?>

<?php
$filter_cats = array();

if ( ! empty( $selected_taxonomies ) ) {
	if ( ! empty( $selected_taxonomies ) ) {
		foreach ( $selected_taxonomies as $categories ) {
			if ( ! empty( $categories ) ) {
				$filter_cats[] = array_map( 'trim', explode( '|', $categories ) );
			}
		}
	}
}

if ( ! empty( $tab_prefix ) ) {
	$tab_prefix = $tab_prefix . ' ';
}

if ( ! empty( $enable_search ) && 'yes' === $enable_search ) {
	$filter_columns_number = 12 / $filter_columns_number;

	// get options with "Use on car filter" enabled.
	$filter = apply_filters( 'stm_listings_filter_func', null );

	$get_post_args = array(
		'post_type'      => apply_filters( 'stm_listings_post_type', 'listings' ),
		'post_status'    => 'publish',
		'posts_per_page' => - 1,
	);
	$all_cars      = new WP_Query( $get_post_args );
	wp_reset_postdata();
}

// Mileage sorting.
if ( ! function_exists( 'mileage_sort' ) ) {
	function mileage_sort( $a, $b ) {
		if ( $a->slug === $b->slug ) {
			return false;
		}

		return ( ( $a->slug < $b->slug ) ? - 1 : 1 );
	}
}

// Search options.
$random_int       = wp_rand( 1, 99999 );
$tab_unique       = 'listing-cars-id-' . $random_int;
$tab_unique_found = 'found-cars-' . $random_int;

$found_cars_classes  = '';
$found_cars_classes .= ( $found_cars_align && ! empty( $found_cars_align ) ) ? ' position-' . $found_cars_align : '';
$found_cars_classes .= ( $found_cars_hide_mobile && 'yes' === $found_cars_hide_mobile ) ? ' hide-on-mobile' : '';

?>
<div class="motors-elementor-widget car-listing-tabs-unit <?php echo esc_attr( $tab_unique ); ?>">
	<div class="car-listing-top-part">
		<?php if ( 'yes' === $found_cars_show ) : ?>
			<div class="found-cars-cloned <?php echo esc_attr( $tab_unique_found ); ?><?php echo esc_attr( $found_cars_classes ); ?>"></div>
		<?php endif; ?>
		<?php if ( ! empty( $content ) ) : ?>
			<div class="title">
				<?php echo wp_kses_post( $content, true ); ?>
			</div>
		<?php endif; ?>
		<?php $filter_cats_counter = 0; ?>

		<?php if ( ! empty( $filter_cats ) ) : ?>

			<div class="stm-listing-tabs">
				<ul class="heading-font" role="tablist">
					<?php if ( ! empty( $filter_cats ) ) : ?>
						<?php
						foreach ( $filter_cats as $filter_cat ) :
							$filter_cats_counter ++;
							?>
							<?php
							if ( ! empty( $filter_cat[0] ) && ! ( empty( $filter_cat[1] ) ) ) :
								$current_category = get_term_by( 'slug', $filter_cat[0], $filter_cat[1] );
								if ( ! empty( $current_category ) ) :
									?>
									<li
										<?php
										if ( 1 === $filter_cats_counter ) {
											echo esc_attr( 'class=active' );
										}
										?>
									><?php $tab_title = ( is_rtl() ) ? $tab_suffix . ' ' . $current_category->name . ' ' . $tab_prefix : $tab_prefix . $current_category->name . ' ' . $tab_suffix; ?>
										<a href="#car-listing-category-<?php echo esc_attr( $current_category->slug . '-' . $random_int ); ?>" role="tab" data-toggle="tab">
											<?php echo esc_html( $tab_title ); ?>
										</a>
									</li>
								<?php endif; ?>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
					<?php if ( ! empty( $enable_search ) && $enable_search ) : ?>
						<li
							<?php
							if ( 0 === $filter_cats_counter ) {
								echo esc_attr( 'class=active' );
							}
							?>
						>
							<a href="#car-listing-tab-search-<?php echo esc_attr( $random_int ); ?>" role="tab" data-toggle="tab">
								<?php echo esc_attr( $search_label ); ?>
							</a>
						</li>
					<?php endif; ?>
				</ul>
			</div>

		<?php endif; ?>

		<?php $filter_cats_counter = 0; ?>
	</div>

	<div class="car-listing-main-part">
		<div class="tab-content">
			<?php if ( ! empty( $filter_cats ) ) : ?>
				<?php
				foreach ( $filter_cats as $filter_cat ) :
					$filter_cats_counter ++;

					if ( ! empty( $filter_cat[0] ) && ! ( empty( $filter_cat[1] ) ) ) :
						// Creating custom query for each tab.
						$args = array(
							'post_type'      => apply_filters( 'stm_listings_post_type', 'listings' ),
							'post_status'    => 'publish',
							'posts_per_page' => $per_page,
						);

						$args['tax_query'][] = array(
							'taxonomy' => $filter_cat[1],
							'field'    => 'slug',
							'terms'    => array( $filter_cat[0] ),
						);

						$args['meta_query'][] = array(
							'key'     => 'car_mark_as_sold',
							'value'   => '',
							'compare' => '=',
						);

						$listing_cars = new WP_Query( $args );
						?>
						<div role="tabpanel" class="tab-pane
						<?php
						if ( 1 === $filter_cats_counter ) {
							echo esc_attr( 'active' );
						}
						?>
						" id="car-listing-category-<?php echo esc_attr( $filter_cat[0] . '-' . $random_int ); ?>">
							<div class="found-cars-clone">
								<div class="found-cars heading-font">
									<?php
									if ( ! empty( $found_cars_icon ) ) {
										echo wp_kses( $found_cars_icon, apply_filters( 'stm_ew_kses_svg', array() ) );
									}
									?>
									<span><?php echo esc_html( $found_cars_prefix ); ?></span>
									<span class="blue-lt">
										<?php echo esc_attr( $listing_cars->found_posts ); ?>
										<?php echo esc_html( $found_cars_suffix ); ?>
									</span>
								</div>
							</div>
							<?php if ( $listing_cars->have_posts() ) : ?>
								<div class="row row-4 car-listing-row">
									<?php
									while ( $listing_cars->have_posts() ) :
										$listing_cars->the_post();
										?>
										<?php

										include MOTORS_ELEMENTOR_CUSTOMS_PATH . '/partials/car-filter-loops.php'; ?>
									<?php endwhile; ?>
								</div>

								<?php if ( ! empty( $enable_ajax_loading ) && $enable_ajax_loading ) : ?>
									<?php if ( $listing_cars->found_posts > $per_page ) : ?>
										<div class="row car-listing-actions">
											<div class="col-xs-12 text-center">
												<div class="dp-in">
													<div class="preloader">
														<span></span>
														<span></span>
														<span></span>
														<span></span>
														<span></span>
													</div>
													<a class="load-more-btn" href="" onclick="stm_loadMoreCars(jQuery(this),'<?php echo esc_js( $filter_cat[0] ); ?>','<?php echo esc_js( $filter_cat[1] ); ?>',<?php echo esc_js( intval( $per_page ) ); ?>,<?php echo esc_js( intval( $per_page ) ); ?>,'<?php echo esc_js( intval( $random_int ) ); ?>');return false;">
														<?php esc_html_e( 'Load more', 'motors-elementor-widgets' ); ?>
													</a>
												</div>
											</div>
										</div>
									<?php endif; ?>
								<?php else : ?>
									<div class="row">
										<div class="col-xs-12 text-center">
											<div class="dp-in">
												<a class="load-more-btn" href="<?php echo esc_url( apply_filters( 'stm_filter_listing_link', '' ) ) . '?' . esc_attr( $filter_cat[1] ) . '=' . esc_attr( $filter_cat[0] ); ?>">
													<?php esc_html_e( 'Show all', 'motors-elementor-widgets' ); ?>
												</a>
											</div>
										</div>
									</div>
								<?php endif; ?>
								<?php wp_reset_postdata(); ?>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			<!--Search tab-->
			<?php if ( ! empty( $enable_search ) && $enable_search ) : ?>
				<div role="tabpanel" class="tab-pane
				<?php
				if ( 0 === $filter_cats_counter ) {
					echo esc_attr( 'active' );
				}
				?>
				" id="car-listing-tab-search-<?php echo esc_attr( $random_int ); ?>">
					<div class="found-cars-clone">
						<div class="found-cars heading-font">
							<i class="stm-icon-car"></i>
							<span><?php echo esc_html( $found_cars_prefix ); ?></span>
							<span class="blue-lt">
								<?php echo esc_attr( $all_cars->found_posts ); ?>
								<?php echo esc_html( $found_cars_suffix ); ?>
							</span>
						</div>
					</div>
					<?php if ( ! empty( $search_label ) ) : ?>
						<div class="tab-search-title heading-font">
							<?php
							if ( ! empty( $search_icon ) ) {
								echo wp_kses( $search_icon, apply_filters( 'stm_ew_kses_svg', array() ) );
							}
							?>
							<?php echo esc_attr( $search_label ); ?>
						</div>
					<?php endif; ?>
					<div class="filter stm-vc-ajax-filter">
						<?php if ( ! empty( $filter ) && ! empty( $filter_selected ) ) : ?>
							<div class="row">
								<form action="<?php echo esc_url( apply_filters( 'stm_filter_listing_link', '' ) ); ?>" method="get">
									<?php
									foreach ( $filter['filters'] as $attribute => $config ) :
										if ( ! empty( $filter['options'][ $attribute ] ) ) :
											if ( in_array( $attribute, $filter_selected, true ) ) :
												?>
												<?php if ( isset( $filter['options'][ $attribute ] ) ) : ?>
												<div class="col-md-<?php echo esc_attr( $filter_columns_number ); ?> col-sm-6 stm-filter_<?php echo esc_attr( $attribute ); ?>">
													<div class="form-group type-select">
														<?php

														$args = array(
															'options' => $filter['options'][ $attribute ],
															'name'    => $attribute,
														);

														if ( apply_filters( 'stm_is_listing_price_field', false, $attribute ) ) {
															$first   = true;
															$options = array();

															foreach ( $filter['options'][ $attribute ] as $key => $option ) {
																if ( $first ) {
																	$options[''] = array(
																		'label'    => $config['single_name'],
																		'selected' => true,
																		'disabled' => false,
																	);

																	$first = false;
																}

																$options[ $key ] = $option;
															}

															$args['options'] = $options;
															$args['maxify']  = true;

														}

														do_action( 'stm_listings_load_template', 'filter/types/select', $args );
														?>
													</div>
												</div>
											<?php endif; ?>
											<?php endif; ?>
										<?php endif; ?>
									<?php endforeach; ?>
									<div class="col-md-3 col-sm-6">
										<div class="row">
											<div class="col-md-8 col-sm-12">
												<button type="submit" class="button icon-button"><i
															class="stm-icon-search"></i><?php esc_html_e( 'Search', 'motors-elementor-widgets' ); ?>
												</button>
											</div>
											<div class="col-md-4 hidden-sm hidden-xs">
												<a href="" class="reset-all reset-styled" title="<?php esc_html_e( 'Reset search fields', 'motors-elementor-widgets' ); ?>"><i
															class="stm-icon-reset"></i></a>
											</div>
										</div>
									</div>
								</form>
							</div>
						<?php endif; ?>
						<?php if ( ! empty( $enable_call_to_action ) && $enable_call_to_action ) : ?>
							<div class="search-call-to-action">
								<div class="stm-call-to-action heading-font">
									<div class="call-to-action-content">
										<?php if ( ! empty( $call_to_action_label ) ) : ?>
											<div class="content">
												<?php
												if ( ! empty( $call_to_action_icon ) ) {
													echo wp_kses( $call_to_action_icon, apply_filters( 'stm_ew_kses_svg', array() ) );
												}
												?>
												<?php echo esc_html( $call_to_action_label ); ?>
											</div>
										<?php endif; ?>
									</div>
									<div class="call-to-action-right">
										<?php if ( ! empty( $call_to_action_label_right ) ) : ?>
											<div class="content">
												<?php
												if ( ! empty( $call_to_action_icon_right ) ) {
													echo wp_kses( $call_to_action_icon_right, apply_filters( 'stm_ew_kses_svg', array() ) );
												}
												?>
												<?php echo esc_html( $call_to_action_label_right ); ?>
											</div>
										<?php endif; ?>
									</div>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php // @codingStandardsIgnoreStart ?>
<script>
    (function ($) {
        "use strict";

        $(document).ready(function () {
            $('.<?php echo esc_attr( $tab_unique_found ); ?>').html($('.<?php echo esc_attr( $tab_unique ); ?> .car-listing-main-part .tab-pane.active .found-cars-clone').html());
            $('.<?php echo esc_attr( $tab_unique ); ?> a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var tab_href = $(e.target).attr('href');
                var found_cars = $(tab_href).find('.found-cars-clone').html();
                $('.<?php echo esc_attr( $tab_unique_found ); ?>').html(found_cars);

            })
        })
    })(jQuery);
</script>
<?php // @codingStandardsIgnoreEnd ?>

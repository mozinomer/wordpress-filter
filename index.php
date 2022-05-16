<?php
get_header(); ?>

<div class="pageContainer">
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<div class="selector">
					<select class="IndustrySelect">
						<option>select</option>
						<?php 
						$terms = get_terms([ 'taxonomy' => 'category', 'hide_empty' => false, ]);
						foreach ($terms as $term) { ?>
							<option value="<?php echo $term->slug ?>">
								<?php echo $term->name; ?>
							</option>
						<?php } ?>
					</select>


					<div class="selectorBrand">
						<div id="mydiv">
							<span class="loader"></span>
						</div>
						<select>
							<?php 
							$terms = get_terms([ 'taxonomy' => 'brands', 'hide_empty' => false, ]);
							foreach ($terms as $term) {
								$termID = get_field('brands_industry', 'brands_'.$term->term_id);
								$term_name = get_term($termID[0])->name;
								?>
								<option value="<?php echo $term_name; ?>">
									<?php echo $term->name; ?>
								</option>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>
			<div class="col-md-8">
				<?php 
				$terms = get_terms([
					'taxonomy' => 'brands',
					'child_of' => 0,
					'orderby' => 'name',
					'order' => 'ASC',
					'hide_empty' => false,
					'include_last_update_time' => false,
					'hierarchical' => 1, 
				]);

				$term_list = [];    
				foreach ( $terms as $term ){
					$first_letter = strtoupper($term->name[0]);
					$term_list[$first_letter][] = $term;
				}
				unset($term);

				foreach ( $term_list as $key=>$value ) { ?>
					<div class="containerItem">
						<h2 class="term-letter"> <?php echo $key; ?></h2>
						<?php echo '<ul class="my_term-archive">';
						foreach ( $value as $term ) {
							$termID = get_field('brands_industry', 'brands_'.$term->term_id);
							$term_name = get_term($termID[0])->slug;
							echo '<li class="brandsNames '. $term_name .'"><a href="' . get_term_link( $term ) . '" title="' . sprintf(__('View all post filed under %s', 'my_localization_domain'), $term->name) . '">'     . $term->name . '</a></li>';
						} ?>
					</div>
				<?php }

				echo '</ul>'; 
				?>
			</div>
		</div>
	</div>
</div>
<?php get_footer();

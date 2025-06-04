<?php echo get_header();  ?>
<h2>Taxonomy Archive Plugin</h2>
	<div class="container pb-3">
		<h2>Категорії</h2>
		<div class="d-grid gap-4" style="grid-template-columns: 3fr 1fr;">

			<div class="bg-body-tertiary rounded-3">
				<div class="row mb-2">
					<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
						<div class="col-md-12">
							<div class="row g-0 rounded overflow-hidden flex-md-row mb-4 shadow h-md-250 position-relative">
								<div class="col p-3 d-flex post-content">
									<div class="col-md-4">
										<div class=" d-none d-lg-block">
											<?php the_post_thumbnail('thumbnail') ?>
										</div>
									</div>
									<div class="col-md-8">
										<div class="row text-secondary">
											<div class=" justify-content-start">
												<i class="fa-solid fa-user"></i> <?php the_author(); ?>
											</div>
											<div class="justify-content-end">
												<i class=" fa-solid fa-calendar-days "></i> <?php the_date(); ?>
											</div>
										</div>

										<h3 class="mb-0"><i class="fa-solid fa-hashtag"></i><?php the_ID() .' '.  the_title(); ?></h3>

										<div class="col  d-flex flex-column">
											<p class="card-text mb-auto"><?php the_excerpt(); ?></p>
										</div>
										<div class="">
											<a href="<?php the_permalink(); ?>" class="">
												<?php echo __('Continue reading...', 'cocojambo') ?>
											</a>
											<span class="text-danger text-end">Подобається <i class="fa-regular fa-heart"></i></span>

										</div>


									</div>
								</div>
							</div>
						</div>
					<?php endwhile; ?>
						<?php the_posts_pagination([
							'prev_next'    => true,
							'prev_text'    => __('« Попередня'),
							'next_text'    => __('Наступна »'),
							'type'         => 'list'
						]);?>
					<?php else: ?>
						<p>Записів поки що немає...</p>
					<?php endif; ?>
				</div>
			</div>
			<div class="bg-body-tertiary rounded-3">
				<div class="position-sticky shadow" style="top: 2rem;">
					<?php dynamic_sidebar('right_sidebar')?>
				</div>
			</div>
		</div>
	</div>
	<div class="b-example-divider"></div>
<?php echo get_footer() ?>
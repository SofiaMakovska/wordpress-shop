<?php echo get_header();  ?>
<h3>BOOOKSS Plugin</h3>
	<div class="container pb-3">
		<div class="d-grid gap-4" style="grid-template-columns: 3fr 1fr;">

			<div class="bg-body-tertiary rounded-3">
				<div class="row mb-2">
					<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
						<div class="col-md-12">
							<div class="row g-0 rounded overflow-hidden flex-md-row mb-4 shadow h-md-250 position-relative">
								<div class="col p-4 d-flex flex-column position-static">
									<strong class="d-inline-block mb-2 text-primary"><?php the_author(); ?></strong>
									<h3 class="mb-0"><?php the_ID(); the_title(); ?></h3>
									<div class="mb-1 text-body-secondary"><?php the_date(); ?></div>
									<div class="col-auto d-none d-lg-block w-auto" style="width: 600px">
										<?php the_post_thumbnail('large') ?>
									</div>
									<p class="card-text mb-auto"><?php the_content(); ?></p>

									<?php the_comment(); ?>
								</div>

							</div>
						</div>
					<?php endwhile; ?>
					<?php endif; ?>
				</div>
			</div>
			<div class="bg-body-tertiary rounded-3">
				<div class="position-sticky shadow" style="top: 2rem;">
					<div class="p-4 mb-3 bg-body-tertiary rounded">
						<h4 class="fst-italic">About</h4>
						<p class="mb-0">Customize this section to tell your visitors a little bit about your publication, writers, content, or something else entirely. Totally up to you.</p>
					</div>

					<div class="p-4">
						<h4 class="fst-italic">Archives</h4>
						<ol class="list-unstyled mb-0">
							<li><a href="#">March 2021</a></li>
							<li><a href="#">February 2021</a></li>
							<li><a href="#">January 2021</a></li>
							<li><a href="#">December 2020</a></li>
							<li><a href="#">November 2020</a></li>
							<li><a href="#">October 2020</a></li>
							<li><a href="#">September 2020</a></li>
							<li><a href="#">August 2020</a></li>
							<li><a href="#">July 2020</a></li>
							<li><a href="#">June 2020</a></li>
							<li><a href="#">May 2020</a></li>
							<li><a href="#">April 2020</a></li>
						</ol>
					</div>

					<div class="p-4">
						<h4 class="fst-italic">Elsewhere</h4>
						<ol class="list-unstyled">
							<li><a href="#">GitHub</a></li>
							<li><a href="#">Twitter</a></li>
							<li><a href="#">Facebook</a></li>
						</ol>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="b-example-divider"></div>
<?php echo get_footer() ?>
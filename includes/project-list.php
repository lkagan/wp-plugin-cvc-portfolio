<?php
/* @var \WP_Query $project_query */
if ( $photo_projects->have_posts() ) : ?>
<div class="portfolio">
	<div class="projects with-photos">
		<?php while ( $photo_projects->have_posts() ) :
			$photo_projects->the_post();
			$photos = get_field( 'photos' );
			?>
			<a href="<?php the_permalink() ?>">
				<div class="project">
					<h4 class="title"><?php echo $this->get_title_formatted() ; ?></h4>
					<img src="<?php echo $photos[0]['sizes']['medium-square'] ?>">
					<?php if ( $excerpt = get_the_excerpt() ) : ?>
						<div class="excerpt"><?php echo $excerpt; ?></div>
					<?php endif; ?>
				</div>
			</a>
		<?php endwhile; ?>
	</div><!-- .projects .with-photos -->

	<div class="projects no-photos">
		<h2>Recent Projects</h2>
		<div class="list-wrapper">
			<ul>
			<?php while ( $projects->have_posts() ) :
				$projects->the_post();
				?> <li><span class="title"><?php echo $this->get_title_formatted() ?></span></li>
			<?php endwhile; ?>
			</ul>
			<a class="button" href="#" id="see-all-projects">See all projects</a>
		</div><!-- .list-wrapper -->
	</div><!-- .projects.no-photos -->
	<?php
		wp_reset_postdata();
		wp_reset_query();
	?>
</div><!-- .portfolio -->
<?php endif;

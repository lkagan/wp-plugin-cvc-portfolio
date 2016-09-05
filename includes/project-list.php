<?php
/* @var \WP_Query $project_query */
if ( $project_query->have_posts() ) :

	?><h2>Projects w/ Photos</h2><?php
	// Traverse and output projects that have photos.
	while ( $project_query->have_posts() ) : $project_query->the_post();

		// Skip if no images are associated with the project.
		if ( ! $photos = get_field( 'photos' ) ) {
			continue;
		}

		?>
		<a href="<?php echo the_permalink() ?>">
			<div class="project">
				<h4 class="title"><?php the_title(); ?></h4>
				<img src="<?php echo $photos[0]['sizes']['medium-square'] ?>">
				<div class="excerpt"><?php the_excerpt(); ?></div>
			</div>
		</a>
	<?php endwhile;

	$project_query->rewind_posts();

	?><h2>Other Projects</h2><?php
	// Traverse and output projects with NO photos
	?><ul class="other-projects"><?php
	while ( $project_query->have_posts() ) :
		$project_query->the_post();

		// Skip if images DO exist.
		if ( get_field( 'photos' ) ) {
			continue;
		}

		?> <li><span class="title"><?php the_title(); ?></span>
		<?php echo ( get_field( 'type' ) ) ? '- <span class="type">' .  get_field( 'type' ) . '</span>' : ''; ?>

	<?php endwhile;
	?><ul><?php

	wp_reset_postdata();
wp_reset_query();
endif;

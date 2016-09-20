<div class="gallery">
	<?php foreach ( $images as $image ) : ?>
		<div class="thumbnail-container">
			<a href="<?php echo $image['url'] ?>" rel="lightbox"><img src="<?php echo $image['sizes']['thumbnail'] ?>">
				<div class="overlay"><i class="fa fa-search-plus"></i></div>
			</a>
		</div><!-- .thumbnail-container -->
	<?php endforeach; ?>
</div><!-- .gallery -->

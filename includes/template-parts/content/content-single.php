<?php
/**
 * Template part for single Events
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-meta">
		<div class="date"><?php echo wp_date( get_option( 'date_format' ), get_post_timestamp() ); ?></div>
		<div class="author">Written by <?php echo get_the_author_meta('display_name'); ?></div>
	</div>
	
	<div class="entry-content">
		<?php the_content(); ?>

		<?php

			if ( function_exists('get_field')) {
				$email_subject = str_replace(array("\r", "\n"), '', get_field('email_subject', $post->ID));
				$direct_download = get_field('direct_download', $post->ID);
				$direct_link = get_field('download_link', $post->ID);
			}

			if(! isset($form) || $direct_download) {
				//$_SESSION['pre_url'] = $url; ?>
					<div class="publication-download">
						<?php if(has_post_thumbnail()): ?>
							<div class="pub-thumb">
								<?php if($direct_download): ?>
									<a href="<?php echo $direct_link ?>">
								<?php endif; ?>
								<img src="<?php echo wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>" alt="<?php the_title(); ?>" />
								<?php if($direct_download): ?>
									</a>
								<?php endif; ?>
							</div>
						<?php endif; ?>
							<div class="pub-download">
								<?php
									$file_size = get_field('file_size');
									$download_ids = get_field('download_ids');
									$second_title = get_field('second_title');
								?>
								<?php if($direct_download): ?>
									<h3><a href="<?php echo $direct_link ?>">
								<?php endif; ?>
								<?php echo $second_title; ?>
								<?php if($direct_download): ?>
									</a></h3>
								<?php endif; ?>
							</div>
						</div>

			<?php } else { ?>

					<div class="download-form">
						<p><strong>The below information will allow us to grant you access to the webinar replay</strong></p>
							<?php echo do_shortcode('[email-download download_id="' . get_field('download_ids') . '" contact_form_id="600"]'); ?>
							<p>Should you need further information on how and why we process your personal information, please contact us at <a href="mailto:info@dfdl.com?subject=How and why does DFDL process my personal information" target="_blank" rel="noopener noreferrer">info@dfdl.com.</a></p>
					</div>
					<?php
						if(!empty($email_subject)){ ?>
							<script type="text/javascript">
								var email_subject = "<?php echo $email_subject; ?>";
								document.getElementById('email-subject').value = email_subject;
							</script><?php
						}
			} ?>
		
	</div><!-- .entry-content -->

	<footer class="entry-footer default-max-width">	
	</footer><!-- .entry-footer -->

</article><!-- #post-<?php the_ID(); ?> -->

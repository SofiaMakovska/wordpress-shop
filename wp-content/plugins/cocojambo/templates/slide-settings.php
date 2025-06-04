<div class="wrap">
	<h1><?php _e( 'Slides management', 'cocojambo' ) ?></h1>
	<h3><?php _e( 'Add slide', 'cocojambo' ) ?></h3>

	<?php
	$errors = get_transient( 'cocojambo_form_errors' );
	$success = get_transient( 'cocojambo_form_success' );
	?>

	<?php if ( $errors ): ?>
		<div id="setting-error-settings_updated" class="notice notice-error settings-error is-dismissible">
			<p><strong><?php echo $errors ?></strong></p>
		</div>
		<?php delete_transient( 'cocojambo_form_errors' ); ?>
	<?php endif; ?>

	<?php if ( $success ): ?>
		<div id="setting-error-settings_updated" class="notice notice-success settings-error is-dismissible">
			<p><strong><?php echo $success ?></strong></p>
		</div>
		<?php delete_transient( 'cocojambo_form_success' ); ?>
	<?php endif; ?>

	<form action="<?php echo admin_url( 'admin-post.php' ) ?>" method="post" class="cocojambo-add-slide">
		<?php wp_nonce_field( 'cocojambo_action', 'cocojambo_add_slide' ) ?>
		<input type="hidden" name="action" value="save_slide">
		<table class="form-table">
			<tbody>
				<tr>
					<th>
						<label for="slide_title"><?php _e( 'Slide title', 'cocojambo' ) ?></label>
					</th>
					<td>
						<input type="text" class="regular-text" name="slide_title" id="slide_title">
					</td>
				</tr>
				<tr>
					<th>
						<label for="slide_content"><?php _e( 'Slide content', 'cocojambo' ) ?></label>
					</th>
					<td>
<!--						<textarea name="slide_content" id="slide_content" class="large-text code" cols="50" rows="10"></textarea>-->
						<?php wp_editor('', 'wp_editor_main', [
							'textarea_name' => 'slide_content',
							'textarea_rows' => 10
						]);?>
					</td>
				</tr>
                <tr>
                    <th>
                        <label for="slide_url"><?php _e( 'Slide Url', 'cocojambo' ) ?></label>
                    </th>
                    <td>
                        <input type="text" class="regular-text" name="slide_url" id="slide_url">
                    </td>
                </tr>
			</tbody>
		</table>

		<p class="submit">
			<button class="button button-primary" type="submit"><?php _e( 'Save slide', 'cocojambo' ) ?></button>
		</p>
	</form>
	<hr>
	<h3><?php _e( 'Edit slides', 'cocojambo' ) ?></h3>

	<div id="accordion">
		<?php /** @var Cocojambo_Admin $slides */
		foreach ( $slides as $slide ): ?>
			<h3><?php echo $slide['title'] ?></h3>
			<div>
				<form action="<?php echo admin_url( 'admin-post.php' ) ?>" method="post" class="cocojambo-add-slide">
					<?php wp_nonce_field( 'cocojambo_action', 'cocojambo_add_slide' ) ?>
					<input type="hidden" name="action" value="save_slide">
					<input type="hidden" name="slide_id" value="<?php echo $slide['id'] ?>">
					<table class="form-table">
						<tbody>
						<tr>
							<th>
								<label for="slide_title_<?php echo $slide['id'] ?>">
									<?php _e( 'Slide title', 'cocojambo' ) ?></label>
							</th>
							<td>
								<input type="text" class="regular-text" name="slide_title"
								       id="slide_title_<?php echo $slide['id'] ?>"
								       value="<?php echo esc_attr( $slide['title'] ) ?>">
							</td>
						</tr>
						<tr>
							<th>
								<label for="slide_content_<?php echo $slide['id'] ?>">
									<?php _e( 'Slide content', 'cocojambo' ) ?></label>
							</th>
							<td>
								<?php wp_editor( $slide['content'], "slide_content_{$slide['id']}", array(
									'textarea_name' => 'slide_content',
									'textarea_rows' => 10,
								) ); ?>
							</td>
						</tr>
                        <tr>
                            <th>
                                <label for="slide_title_<?php echo $slide['id'] ?>">
									<?php _e( 'Slide Url', 'cocojambo' ) ?></label>
                            </th>
                            <td>
                                <input type="text" class="regular-text" name="slide_url"
                                       id="slide_url_<?php echo $slide['id'] ?>"
                                       value="<?php echo esc_attr( $slide['url'] ) ?>">
                            </td>
                        </tr>

						</tbody>
					</table>

					<p class="submit">
						<button class="button button-primary" type="submit">
							<?php _e( 'Save slide', 'cocojambo' ) ?></button>
					</p>

				</form>
			</div>
		<?php endforeach; ?>
	</div>
</div>

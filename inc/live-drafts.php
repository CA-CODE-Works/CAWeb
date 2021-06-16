<?php
/**
 * CAWeb Live Drafts
 *
 * @source https://plugins.trac.wordpress.org/browser/live-drafts/
 *
 * @package CAWeb
 */

add_action( 'load-post.php', 'caweb_live_drafts_init' );
add_action( 'load-post-new.php', 'caweb_live_drafts_init' );

add_action( 'publish_future_post', 'caweb_live_drafts_publish_future_post' );

/**
 * CAWeb Live Drafts Initialization
 *
 * Fires before the post and post new pages are loaded.
 *
 * @category {
 * add_action( 'load-post.php', 'caweb_live_drafts_init' );
 * add_action( 'load-post-new.php', 'caweb_live_drafts_init' );
 * }
 * @return void
 */
function caweb_live_drafts_init() {

	// Admin head.
	add_action( 'admin_head-post.php', 'caweb_live_drafts_admin_head' );

	caweb_live_drafts_post_hooks();

	// Admin footer.
	add_action( 'admin_footer-post.php', 'caweb_live_drafts_admin_footer', 10 );

	add_action( 'admin_notices', 'caweb_live_drafts_admin_notice' );
}

/**
 * CAWeb Live Drafts Post Hooks
 *
 * To avoid infinite loop these hooks are added and removed during insert/update of a post.
 *
 * @see https://developer.wordpress.org/reference/hooks/save_post/#avoiding-infinite-loops
 * @param  bool $add Whether to remove or add the hooks.
 * @return void
 */
function caweb_live_drafts_post_hooks( $add = true ) {

	if ( $add ) {
		// Pre-post update.
		add_action( 'pre_post_update', 'caweb_live_drafts_pre_post_update', 10, 2 );

		// Save Post Action.
		add_action( 'save_post', 'caweb_live_drafts_post_update', 10, 2 );

	} else {
		// Pre-post update.
		remove_action( 'pre_post_update', 'caweb_live_drafts_pre_post_update' );

		// Save Post Action.
		remove_action( 'save_post', 'caweb_live_drafts_post_update' );

	}

}

/**
 * CAWeb Publish Future Post
 *
 * Invoked by cron ‘publish_future_post’ event.
 *
 * @category add_action( 'publish_future_post', 'caweb_live_drafts_publish_future_post' );
 * @param  int|WP_Post $post_id Post ID or post object.
 * @return void
 */
function caweb_live_drafts_publish_future_post( $post_id ) {
	// Check for post meta that identifies this as a 'draft of a live page'.
	$_pc_live_id = get_post_meta( $post_id, '_pc_liveId', true );

	if ( empty( $_pc_live_id ) ) {
		return;
	}

	$post_data = get_post( $post_id, ARRAY_A );

	// Duplicate post and replace live page.
	$updated_post = caweb_live_drafts_duplicate_post(
		$post_data,
		array(
			'ID'             => $_pc_live_id,
			'post_status'    => 'publish',
			'post_content'   => isset( $post_data['post_content'] ) ? $post_data['post_content'] : ( is_object( $post_data ) ? $post_data->post_content : '' ),
		)
	);

	// unhook actions.
	caweb_live_drafts_post_hooks( false );

	// Insert the post into the database.
	wp_update_post( $updated_post );

	// re-hook action.
	caweb_live_drafts_post_hooks();

	// Clear existing meta data.
	$existing = get_post_custom( $_pc_live_id );
	foreach ( $existing as $ekey => $evalue ) {
		delete_post_meta( $_pc_live_id, $ekey );
	}

	// Migrate custom meta data from draft.
	caweb_live_drafts_migrate_post_meta( $post_id, $_pc_live_id, array( '_pc_liveId', '_pc_draftId' ) );

	// Delete draft post, force delete since 2.9, no sending to trash.
	wp_delete_post( $post_id, true );

}

/**
 * CAWeb Live Drafts Admin Head
 *
 * Fires in head section for post and post new pages.
 *
 * @category add_action( 'admin_head-post.php', 'caweb_live_drafts_admin_head' );
 * @return void
 */
function caweb_live_drafts_admin_head() {
	global $post;

	// Only show on published pages.
	if ( in_array( $post->post_type, array( 'post', 'page' ), true ) && 'publish' === $post->post_status ) {

		?>
		<script type="text/javascript">

			// Add save draft button to live pages.
			jQuery(document).ready(function($) {

				$('<input type="submit" class="button button-highlighted caweb-save-draft" tabindex="4" value="Save Draft" id="save-post" name="save"><input type="hidden" name="caweb_save_draft"/>').prependTo('#save-action');

				$('#save-action .caweb-save-draft').on('click', function(e){
					if( undefined !== e.originalEvent && e.originalEvent instanceof MouseEvent ){
						$('#save-action input[name="caweb_save_draft"]').val('saving');
					}
				})

			});

		</script>
		<?php
	}
}

/**
 * CAWeb Live Drafts Admin Notices
 *
 * Prints admin screen notices.
 *
 * @category add_action( 'admin_notices', 'caweb_live_drafts_admin_notice' );
 * @return void
 */
function caweb_live_drafts_admin_notice() {
	global $post;

	// Only show on published pages.
	if ( is_object( $post ) &&
		in_array( $post->post_type, array( 'post', 'page' ), true )
		) {

		$draft_id = get_post_meta( $post->ID, '_pc_draftId', true );
		$live_id  = get_post_meta( $post->ID, '_pc_liveId', true );

		if ( empty( $draft_id ) && empty( $live_id ) ) {
			return;
		}

		$class = 'notice notice-warning';

		if ( ! empty( $draft_id ) ) {
			$message    = 'Warning! A live draft exists for this page.  ';
			$draft_link = admin_url( 'post.php?action=edit&post=' . $draft_id );
		} else {
			$message    = 'Warning! This is a draft for a live page.  ';
			$draft_link = admin_url( 'post.php?action=edit&post=' . $live_id );
		}

		printf(
			'<div class="%1$s"><p>%2$s<a href="%3$s">View here.</a></p></div>',
			esc_attr( $class ),
			esc_html( $message ),
			esc_url( $draft_link )
		);
	}
}

/**
 * CAWeb Live Drafts Admin Footer
 *
 * Fires in footer section for post and post new pages.
 *
 * @category add_action( 'admin_footer-post.php', 'caweb_live_drafts_admin_footer' );
 * @return void
 */
function caweb_live_drafts_admin_footer() {
	global $post;

	// Only show on published pages.
	if ( in_array( $post->post_type, array( 'post', 'page' ), true ) && 'publish' === $post->post_status ) {
		?>
		<style>
			#save-post{
				display: inline-block !important;
			}
		</style>
		<?php
	}
}

/**
 * CAWeb Live Drafts Duplicate Post
 *
 * @param  array $post_data Post data to duplicate from.
 * @param  array $default Default post data. post_status = draft.
 * @param  bool  $use_post_id Whether to use the $post_data Post ID or leave blank.
 * @return array
 */
function caweb_live_drafts_duplicate_post( $post_data, $default = array( 'post_status' => 'draft' ), $use_post_id = true ) {
	$dup_post = array(
		'menu_order'     => $post_data['menu_order'],
		'comment_status' => ( empty( $post_data['comment_status'] ) || 'open' === $post_data['comment_status'] ? 'open' : 'closed' ),
		'ping_status'    => ( empty( $post_data['ping_status'] ) || 'open' === $post_data['ping_status'] ? 'open' : 'closed' ),
		'post_author'    => $post_data['post_author'],
		'post_category'  => ( isset( $post_data['post_category'] ) ? $post_data['post_category'] : array() ),
		'post_content'   => isset( $post_data['content'] ) ? $post_data['content'] : ( isset( $post_data['post_content'] ) ? $post_data['post_content'] : '' ),
		'post_excerpt'   => isset( $post_data['excerpt'] ) ? $post_data['excerpt'] : '',
		'post_parent'    => isset( $post_data['parent_id'] ) ? $post_data['parent_id'] : 0,
		'post_password'  => $post_data['post_password'],
		'post_title'     => $post_data['post_title'],
		'post_type'      => $post_data['post_type'],
		'tags_input'     => ( isset( $post_data['tax_input']['post_tag'] ) ? $post_data['tax_input']['post_tag'] : '' ),
	);

	if ( $use_post_id && isset( $post_data['post_ID'] ) ) {
		$dup_post['ID'] = $post_data['post_ID'];
	}

	return array_merge( $dup_post, $default );
}

/**
 * CAWeb Live Drafts Migrate Post Meta
 *
 * @param  int   $from Post ID of the post we are getting the meta from.
 * @param  int   $to Post ID of the post we are adding the meta to.
 * @param  array $exclude Array of meta to exclude from adding.
 * @return void
 */
function caweb_live_drafts_migrate_post_meta( $from, $to, $exclude = array() ) {
	$exclusions = is_string( $exclude ) ? explode( $exclude, ',' ) : $exclude;
	$excluded   = array_merge( array( '_edit_lock', '_edit_last' ), $exclusions );

	$custom = get_post_custom( $from );

	foreach ( $custom as $ckey => $cvalue ) {
		if ( ! in_array( $ckey, $excluded, true ) ) {
			foreach ( $cvalue as $mvalue ) {
				if ( '_et_pb_ab_current_shortcode' === $ckey ) {
					add_post_meta( $to, $ckey, array( sprintf( '[et_pb_split_track id="%1$s"]', $to ) ), true );
				} else {
					add_post_meta( $to, $ckey, $mvalue, true );
				}
			}
		}
	}
}

/**
 * CAWeb Live Drafts Pre Post Update
 *
 * Fires immediately before an existing post is updated in the database.
 *
 * @category add_action( 'pre_post_update', 'caweb_live_drafts_pre_post_update', 10, 2 );
 * @param  int   $post_id Post ID.
 * @param  array $post Array of unslashed post data.
 * @return int
 */
function caweb_live_drafts_pre_post_update( $post_id, $post ) {
	$caweb_draft = isset( $_REQUEST['caweb_save_draft'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['caweb_save_draft'] ) ) : '';

	// check if the caweb save draft button was pressed.
	if ( 'saving' !== $caweb_draft ) {
		return $post_id;
	}

	$verified = isset( $_REQUEST['_wpnonce'] ) && wp_verify_nonce( sanitize_key( $_REQUEST['_wpnonce'] ), 'add-post' );

	// Check if this is an auto save routine. If it is we dont want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}

	$post_type = isset( $_POST['post_type'] ) ? sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) : '';

	// Only continue if this request is for the post or page post type.
	if ( ! in_array( $post_type, array( 'post', 'page' ), true ) ) {
		return $post_id;
	}

	// Check permissions.
	if ( ! current_user_can( 'edit_' . ( 'posts' === $post_type ? 'posts' : 'page' ), $post_id ) ) {
		return $post_id;
	}

	$save        = isset( $_REQUEST['save'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['save'] ) ) : '';
	$post_status = isset( $_REQUEST['post_status'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['post_status'] ) ) : '';

	// Catch only when a draft is saved of a live page.
	if ( 'Save Draft' === $save &&
		'publish' === $post_status
		) {
		// Check for post meta that identifies this as a 'live draft'.
		$_pc_draft_id = get_post_meta( $post_id, '_pc_draftId', true );

		if ( ! empty( $_pc_draft_id ) &&
			'trash' !== get_post_status( $_pc_draft_id ) ) {
			return $post_id;
		}

		// Duplicate post and set as a draft.
		$draft_post = caweb_live_drafts_duplicate_post(
			$_REQUEST,
			array(
				'post_content' => $post['post_content'],
				'post_status'  => 'draft',
			),
			false
		);

		// unhook action.
		remove_action( 'pre_post_update', 'caweb_live_drafts_pre_post_update' );

		// Insert the post into the database.
		$new_id = wp_insert_post( $draft_post );

		// re-hook action.
		add_action( 'pre_post_update', 'caweb_live_drafts_pre_post_update', 10, 2 );

		// Migrate meta data.
		caweb_live_drafts_migrate_post_meta( $post_id, $new_id, array( '_pc_liveId', '_pc_draftId' ) );

		// Add a hidden meta data value to indicate that this is a draft of a live page.
		update_post_meta( $new_id, '_pc_liveId', $post_id );

		// Add a hidden meta data value to indicate the draft exist for a live page.
		update_post_meta( $post_id, '_pc_draftId', $new_id );
	}

}

/**
 * CAWeb Live Drafts Post Update
 *
 * Fires once a post has been saved.
 *
 * @category add_action( 'save_post', 'caweb_live_drafts_post_update', 10, 2 );
 * @param  int     $post_id Post ID.
 * @param  WP_POST $post Post object.
 * @return int
 */
function caweb_live_drafts_post_update( $post_id, $post ) {

	// Check if this is an auto save routine. If it is we dont want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}

	$verified = isset( $_REQUEST['_wpnonce'] ) && wp_verify_nonce( sanitize_key( $_REQUEST['_wpnonce'] ), 'add-post' );

	$post_type = isset( $_POST['post_type'] ) ? sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) : '';

	// Only continue if this request is for the post or page post type.
	if ( ! in_array( $post_type, array( 'post', 'page' ), true ) ) {
		return $post_id;
	}

	// Check permissions.
	if ( ! current_user_can( 'edit_' . ( 'posts' === $post_type ? 'posts' : 'page' ), $post_id ) ) {
		return $post_id;
	}

	$request_post_id = isset( $_REQUEST['post_ID'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['post_ID'] ) ) : '';
	$save            = isset( $_REQUEST['save'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['save'] ) ) : '';
	$post_status     = isset( $_REQUEST['post_status'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['post_status'] ) ) : '';
	$caweb_draft     = isset( $_REQUEST['caweb_save_draft'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['caweb_save_draft'] ) ) : '';

	// Catch when a draft is saved of a live page.
	if ( 'Save Draft' === $save &&
			'publish' === $post_status &&
			'saving' === $caweb_draft &&
			! wp_is_post_revision( $request_post_id )
		) {

		// Check for post meta that identifies this as a 'live draft'.
		$_pc_draft_id = get_post_meta( $request_post_id, '_pc_draftId', true );

		if ( empty( $_pc_draft_id ) ) {
			return;
		}

		// Divi saves the original even when a draft is created, revert to previous revision.
		$revs = wp_get_post_revisions( $request_post_id );

		if ( ! empty( $revs ) ) {
			array_shift( $revs );

			// unhook actions.
			caweb_live_drafts_post_hooks( false );

			// Divi is saving the original page even if a draft is created, duplicate post, and rollback to previous post_content.
			$rollback_post = caweb_live_drafts_duplicate_post(
				$_REQUEST,
				array(
					'post_status'    => 'publish',
					'post_content'   => array_shift( $revs )->post_content,
				)
			);

			wp_update_post( $rollback_post );

			// re-hook actions.
			caweb_live_drafts_post_hooks();
		}

			// Send user to new edit page.
			wp_safe_redirect( admin_url( 'post.php?action=edit&post=' . $_pc_draft_id ) );
			exit();

	}

	$publish = isset( $_REQUEST['publish'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['publish'] ) ) : '';
	$content = isset( $_REQUEST['content'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['content'] ) ) : $post->post_content;

	// Catch when a draft is published.
	if ( 'Schedule' !== $publish ) {
		// Check for post meta that identifies this as a 'draft of a live page'.
		$_pc_live_id = get_post_meta( $post_id, '_pc_liveId', true );

		if ( empty( $_pc_live_id ) ) {
			return;
		}

		// Duplicate post and replace live page.
		$updated_post = caweb_live_drafts_duplicate_post(
			$_REQUEST,
			array(
				'ID'             => $_pc_live_id,
				'post_status'    => 'publish',
				'post_content'   => $content,
				'post_date'      => current_time( 'mysql' ),
			)
		);

		// unhook actions.
		caweb_live_drafts_post_hooks( false );

		// Insert the post into the database.
		wp_update_post( $updated_post );

		// re-hook action.
		caweb_live_drafts_post_hooks();

		// Clear existing meta data.
		$existing = get_post_custom( $_pc_live_id );
		foreach ( $existing as $ekey => $evalue ) {
			delete_post_meta( $_pc_live_id, $ekey );
		}

		// Migrate custom meta data from draft.
		caweb_live_drafts_migrate_post_meta( $post_id, $_pc_live_id, array( '_pc_liveId', '_pc_draftId' ) );

		// Delete draft post, force delete since 2.9, no sending to trash.
		wp_delete_post( $post_id, true );

		unset( $_SESSION[ "post_$_pc_live_id" ] );

		// Send user to new edit page.
		wp_safe_redirect( admin_url( 'post.php?action=edit&post=' . $_pc_live_id ) );
		exit();
	}

}

?>

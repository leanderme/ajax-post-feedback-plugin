<?php
/*function pf_post_taxonomies() {
	$positive_labels = array(
		'name'              => _x( 'Positive Headwords', 'taxonomy general name' ),
		'singular_name'     => _x( 'Artist', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Positive Headwords' ),
		'all_items'         => __( 'All Positive Headwords' ),
		'parent_item'       => __( 'Parent Artist' ),
		'parent_item_colon' => __( 'Parent Artist:' ),
		'edit_item'         => __( 'Edit Artist' ),
		'update_item'       => __( 'Update Artist' ),
		'add_new_item'      => __( 'Add New Artist' ),
		'new_item_name'     => __( 'New Artist Name' ),
		'menu_name'         => __( 'Positive Headwords' ),
	);

	$positive_args = array(
		'hierarchical'      => true,
		'labels'            => $positive_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'positive' ),
	);

	register_taxonomy( 'positive', array( 'post' ), $positive_args );

	$neutral_labels = array(
		'name'              => _x( 'Neutral Headwords', 'taxonomy general name' ),
		'singular_name'     => _x( 'Artist', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Neutral Headwords' ),
		'all_items'         => __( 'All Neutral Headwords' ),
		'parent_item'       => __( 'Parent Artist' ),
		'parent_item_colon' => __( 'Parent Artist:' ),
		'edit_item'         => __( 'Edit Artist' ),
		'update_item'       => __( 'Update Artist' ),
		'add_new_item'      => __( 'Add New Artist' ),
		'new_item_name'     => __( 'New Artist Name' ),
		'menu_name'         => __( 'Neutral Headwords' ),
	);

	$neutral_args = array(
		'hierarchical'      => true,
		'labels'            => $neutral_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'neutral' ),
	);

	register_taxonomy( 'neutral', array( 'post' ), $neutral_args );


	$negative_labels = array(
		'name'              => _x( 'Negative Headwords', 'taxonomy general name' ),
		'singular_name'     => _x( 'Artist', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Negative Headwords' ),
		'all_items'         => __( 'All Negative Headwords' ),
		'parent_item'       => __( 'Parent Artist' ),
		'parent_item_colon' => __( 'Parent Artist:' ),
		'edit_item'         => __( 'Edit Artist' ),
		'update_item'       => __( 'Update Artist' ),
		'add_new_item'      => __( 'Add New Artist' ),
		'new_item_name'     => __( 'New Artist Name' ),
		'menu_name'         => __( 'Negative Headwords' ),
	);

	$negative_args = array(
		'hierarchical'      => true,
		'labels'            => $negative_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'negative' ),
	);

	register_taxonomy( 'negative', array( 'post' ), $negative_args );


}
*/
add_action('admin_init', 'add_meta_boxes', 1);
function add_meta_boxes() {
    add_meta_box( 'repeatable-fields', 'Post Feedback Headwords', 'repeatable_meta_box_display', 'post', 'normal', 'high');
}

function repeatable_meta_box_display() {
    global $post;

    $pf_headwords = get_post_meta($post->ID, 'pf_headwords', true);


    wp_nonce_field( 'repeatable_meta_box_nonce', 'repeatable_meta_box_nonce' );
?>
    <script type="text/javascript">
	jQuery(document).ready(function($) {
    $('.metabox_submit').click(function(e) {
        e.preventDefault();
        $('#publish').click();
    });
    $('#add-row').on('click', function() {
        var row = $('.empty-row.screen-reader-text').clone(true);
        row.removeClass('empty-row screen-reader-text');
        row.insertBefore('#repeatable-fieldset-one tbody>tr:last');
        return false;
    });
    $('.remove-row').on('click', function() {
        $(this).parents('tr').remove();
        return false;
    });

    $('#repeatable-fieldset-one tbody').sortable({
        opacity: 0.6,
        revert: true,
        cursor: 'move',
        handle: '.sort'
    });
});
    </script>

    <table id="repeatable-fieldset-one" width="100%">
    <thead>
        <tr>
            <th width="2%"></th>
            <th width="30%">Name</th>
            <th width="50%">Description</th>
            <th width="16%">Rating</th>
            <th width="2%"></th>
        </tr>
    </thead>
    <tbody>
    <?php

    if ( $pf_headwords ) :

        foreach ( $pf_headwords as $field ) {
?>
    <tr>
        <td><a class="button remove-row" href="#">-</a></td>
        <td><input type="text" class="widefat" name="name[]" value="<?php if($field['name'] != '') echo esc_attr( $field['name'] ); ?>" /></td>
        <td><input type="text" class="widefat" name="desc[]" value="<?php if($field['desc'] != '') echo esc_attr( $field['desc'] ); ?>" /></td>
        <td><input type="text" class="widefat" name="rating[]" value="<?php if ($field['rating'] != '') echo esc_attr( $field['rating'] ); else echo '0'; ?>" /></td>
        <td><a class="sort">|||</a></td>

    </tr>
    <?php
        }
    else :
        // show a blank one
?>
    <tr>
        <td><a class="button remove-row" href="#">-</a></td>
        <td><input type="text" class="widefat" name="name[]" /></td>
        <td><input type="text" class="widefat" name="desc[]" /></td>
        <td><input type="text" class="widefat" name="rating[]" value="0"/></td>
		<td><a class="sort">|||</a></td>
    </tr>
    <?php endif; ?>

    <!-- empty hidden one for jQuery -->
    <tr class="empty-row screen-reader-text">
        <td><a class="button remove-row" href="#">-</a></td>
        <td><input type="text" class="widefat" name="name[]" /></td>
        <td><input type="text" class="widefat" name="desc[]" /></td>
        <td><input type="text" class="widefat" name="rating[]" value="0"/></td>
		<td><a class="sort">|||</a></td>
    </tr>
    </tbody>
    </table>

    <p><a id="add-row" class="button" href="#">Add another</a>
    <input type="submit" class="metabox_submit" value="Save" />
    </p>
    <?php

    }

	add_action('save_post', 'repeatable_meta_box_save');
	function repeatable_meta_box_save($post_id) {
    if ( ! isset( $_POST['repeatable_meta_box_nonce'] ) ||
        ! wp_verify_nonce( $_POST['repeatable_meta_box_nonce'], 'repeatable_meta_box_nonce' ) )
        return;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    if (!current_user_can('edit_post', $post_id))
        return;

    $old = get_post_meta($post_id, 'pf_headwords', true);
    $new = array();


    $names = $_POST['name'];
    $descs = $_POST['desc'];
    $ratings = $_POST['rating'];

    $count = count( $names );

    for ( $i = 0; $i < $count; $i++ ) {
        if ( $names[$i] != '' ) :
            $new[$i]['name'] = stripslashes( strip_tags( $names[$i] ) );
        if ( $descs[$i] != ''  )
            $new[$i]['desc'] = stripslashes( strip_tags( $descs[$i] ) );
        if ( $ratings[$i] != ''  )
            $new[$i]['rating'] = stripslashes( $ratings[$i] ); // and however you want to sanitize
        endif;
    }

    if ( !empty( $new ) && $new != $old )
        update_post_meta( $post_id, 'pf_headwords', $new );
    elseif ( empty($new) && $old )
        delete_post_meta( $post_id, 'pf_headwords', $old );
}



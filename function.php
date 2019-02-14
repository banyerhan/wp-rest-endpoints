<?php
//GET call back for custom REST Advanced
function register_categories_names_field() {

    register_rest_field( 'post',
        'categories_names',
        array(
            'get_callback'    => 'req_get_categories_names',
            'update_callback' => null,
            'schema'          => null,
        )
    );
}

add_action( 'rest_api_init', 'register_categories_names_field' );

function req_get_categories_names( $object, $field_name, $request ) {

    $formatted_categories = array();

    $categories = get_the_category( $object['id'] );

    foreach ($categories as $category) {
        $formatted_categories[] = $category->name;
    }

    return $formatted_categories;
}
//---------------------------------------------

//GET all formated post item in wp-json it is normal usual
function get_all_posts( $data, $post, $context ) {
    return [
        'id'        => $data->data['id'],
        'date'      => $data->data['date'],
        'date_gmt'  => $data->data['date_gmt'],
        'modified'  => $data->data['modified'],
        'title'     => $data->data['title']['rendered'],
        'content'   => $data->data['content']['rendered'],
        'excerpt'   => $data->data['excerpt']['rendered'],
        'category'  => get_the_category_by_ID( $data->data['categories'][0] ), //Category endpoint returns directly the name of the post category
        'link'      => $data->data['link'],


    ];
}
add_filter( 'rest_prepare_post', 'get_all_posts', 10, 3 );
//---------------------------------------------

//it is easy to take features image.. becaz it is default. one of custom field.
function features_image() {
//Add featured image
register_rest_field( 
    'post', 
    'my_features', 
    array(
        'get_callback'    => 'get_image_src',
        'update_callback' => null,
        'schema'          => null,
         )
    );
}
add_action( 'rest_api_init', 'features_image' );
function get_image_src( $object, $field_name, $request ) {
  $feat_img_array = wp_get_attachment_image_src(
    $object['featured_media'], 
    'full',  // Size.  Ex. "thumbnail", "large", "full", etc..
    true // Whether the image should be treated as an icon.
  );
  return $feat_img_array[0];
}
//---------------------------------------------
//GET custom-post-type in REST
function my_show_rest_view(){
    global $wp_post_types;
    $wp_post_types['your-post-type']->show_in_rest = true;
    $wp_post_types['your-post-type']->rest_base = your-post-type; // you can set desired if missed it return 404
    $wp_post_types['your-post-type']->rest_controller_class = 'WP_REST_Posts_Controller';
}
add_action( 'init', 'my_show_rest_view', 30 );
?>
//---------------------------------------------


?>

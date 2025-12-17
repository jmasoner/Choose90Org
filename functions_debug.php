<?php
function choose90_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    // Enqueue Global Styles with Cache Busting
    wp_enqueue_style( 'hybrid-global-style', 'https://choose90.org/style.css', array(), time() );
    wp_enqueue_style( 'choose90-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Outfit:wght@500;700;800&display=swap', array(), null );
}
add_action( 'wp_enqueue_scripts', 'choose90_enqueue_styles' );

// --- REGISTER CHAPTERS CPT ---
function choose90_register_chapters() {
     = array(
        'name'               => 'Chapters',
        'singular_name'      => 'Chapter',
        'add_new'            => 'Add New Chapter',
        'add_new_item'       => 'Add New Chapter',
        'edit_item'          => 'Edit Chapter',
        'new_item'           => 'New Chapter',
        'all_items'          => 'All Chapters',
        'view_item'          => 'View Chapter',
        'search_items'       => 'Search Chapters',
        'not_found'          => 'No chapters found',
        'not_found_in_trash' => 'No chapters found in Trash',
        'menu_name'          => 'Chapters'
    );

     = array(
        'labels'             => ,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'chapter' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-groups',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'custom-fields' )
    );

    register_post_type( 'chapter',  );
}
add_action( 'init', 'choose90_register_chapters' );

// --- REGISTER REGION TAXONOMY ---
function choose90_register_region_taxonomy() {
     = array(
        'name'              => 'Regions',
        'singular_name'     => 'Region',
        'search_items'      => 'Search Regions',
        'all_items'         => 'All Regions',
        'parent_item'       => 'Parent Region',
        'parent_item_colon' => 'Parent Region:',
        'edit_item'         => 'Edit Region',
        'update_item'       => 'Update Region',
        'add_new_item'      => 'Add New Region',
        'new_item_name'     => 'New Region Name',
        'menu_name'         => 'Region',
    );

     = array(
        'hierarchical'      => true,
        'labels'            => ,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'region' ),
    );

    register_taxonomy( 'chapter_region', array( 'chapter' ),  );
}
add_action( 'init', 'choose90_register_region_taxonomy' );
?>

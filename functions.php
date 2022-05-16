<?php

//hook into the init action and call create_book_taxonomies when it fires

add_action( 'init', 'create_brands_hierarchical_taxonomy', 0 );

//create a custom taxonomy name it brands for your posts

function create_brands_hierarchical_taxonomy() {

// Add new taxonomy, make it hierarchical like categories
//first do the translations part for GUI

  $labels = array(
    'name' => _x( 'brands', 'taxonomy general name' ),
    'singular_name' => _x( 'brand', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search brands' ),
    'all_items' => __( 'All brands' ),
    'parent_item' => __( 'Parent brand' ),
    'parent_item_colon' => __( 'Parent brand:' ),
    'edit_item' => __( 'Edit brand' ), 
    'update_item' => __( 'Update brand' ),
    'add_new_item' => __( 'Add New brand' ),
    'new_item_name' => __( 'New brand Name' ),
    'menu_name' => __( 'brands' ),
);    

// Now register the taxonomy
  register_taxonomy('brands',array('post'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_in_rest' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'brand' ),
));

}



function my_enqueue() {
    wp_enqueue_script( 'ajax-script', get_template_directory_uri() . '/ajax.js', array('jquery') );
    wp_localize_script( 'ajax-script', 'my_ajax_object',
        array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}
add_action( 'wp_enqueue_scripts', 'my_enqueue' );
function add_this_script_footer(){ ?>
    <script>
        jQuery(document).ready(function($) {
            $('.IndustrySelect').on('change', function() {
                $('#mydiv').fadeIn('fast');
                $.ajax({
                    url: '<?php echo admin_url( 'admin-ajax.php' ); ?>', 
                    data: {
                        'action':'example_ajax_request', 
                        'industry' : this.value 
                    },
                    success:function(data) {
                        data = data.substring(0, data.length - 1);
                        data = JSON.parse(data)
                        console.log(data)
                        $('.selectorBrand select').empty()

                        $.each(data, function(index, value){
                            $('.selectorBrand select').append('<option value="'+value+'">'+value+'</option>');
                        });
                        $('#mydiv').fadeOut('fast');
                    },  
                    error: function(errorThrown){
                        window.alert(errorThrown);
                    }
                });  
            });
        });
    </script>
<?php } 
add_action('wp_footer', 'add_this_script_footer'); 
function example_ajax_request() {
    if ( isset($_REQUEST) ) {
        $select = [];
        $terms = get_terms([ 'taxonomy' => 'brands', 'hide_empty' => false, ]);
        foreach ($terms as $term) {
            $termID = get_field('brands_industry', 'brands_'.$term->term_id);
            $term_name = get_term($termID[0])->name;
            $term_name = strtolower($term_name);
            if($term_name == $_REQUEST['industry']) {
                array_push($select, $term->name);
            } 
        }
        echo json_encode($select);
    }
}
add_action( 'wp_ajax_example_ajax_request', 'example_ajax_request' ); 
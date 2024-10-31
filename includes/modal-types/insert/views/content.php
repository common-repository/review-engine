<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <?php
        do_action('admin_print_styles');
        do_action('admin_print_scripts');
        do_action('admin_head');
        ?>
    </head>

    <body>
        <div id="loader" style="display: none;"><span class="spinner"><i class="bounce1"></i><i class="bounce2"></i><i class="bounce3"></i></span></div>

        <?php
        $args = array();
        $args['post_type'] = REE_POST_TYPE;
        $args['post_status'] = 'publish';
        $args['posts_per_page'] = REE_INSERT_SEARCH_LIMIT;
        $args['meta_key'] = REE_META_KEY_PRODUCT_DATA;
        $args['meta_compare'] = 'EXISTS';

        $the_query = new WP_Query($args);
        ?>

        <div class="wrap" id="reviewengine-modal">
            <div id="search">
                <form action="#" method="get" id="search-form" class="row">
                    <div class="col-md-12">
                        <div class="search-keyword">
                            <?php if( $the_query->have_posts() ) { ?>
                                <input type="text" placeholder="<?php _e('Search for Products'); ?>" name="search-keyword" class="form-control">
                                <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                            <?php } else { ?>
                                <input type="text" placeholder="<?php _e('Search for Products'); ?>" name="search-keyword" class="form-control" disabled>
                                <button type="submit" disabled><i class="fa fa-search" aria-hidden="true"></i></button>
                            <?php } ?>
                        </div>
                    </div>
                </form><!-- .row -->
                <div id="search-results">
                    <table class="table">
                        <thead>
                            <tr>
                                <th colspan="2" class="column-product"><?php _e('Product'); ?></th>
                                <th class="column-display" style="width: 30%"><?php _e('Display'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if( $the_query->have_posts() ) :
                                while( $the_query->have_posts() ) : $the_query->the_post();
                                    $post_id = get_the_ID();
                                    $product_data = get_post_meta( $post_id, REE_META_KEY_PRODUCT_DATA, true );

                                    if( !empty( $product_data ) ) {
                                        $photo = '';

                                        if( isset( $product_data['images'] ) 
                                            && is_array( $product_data['images'] ) 
                                            && empty( $photo ) ) {
                                            foreach( $product_data['images'] as $image ) {
                                                if( isset( $image['url'] ) 
                                                    && isset( $image['width'] ) 
                                                    && $image['width'] >= 50 
                                                    && $image['width'] <= 150 ) {
                                                    $photo = $image['url'];
                                                }
                                            }
                                        }

                                        echo '<tr data-post_id="' .$post_id. '">';
                                        echo '<td class="column-photo">';
                                        if( !empty( $photo ) ) {
                                            echo '<img src="' .$photo. '" />';
                                        }
                                        echo '</td>';
                                        echo '<td class="column-product">';
                                        if( isset( $product_data['url'] ) ) {
                                            echo '<p><a href="' .$product_data['url']. '" target="_blank"><strong>' .get_the_title( $post_id ). '</strong></a></p>';
                                            echo '<div class="product-info">';
                                            if( isset( $product_data['price'] ) ) {
                                                echo '<p><strong>' .$product_data['price']. '</strong></p>';
                                            }
                                            if( isset( $product_data['reviews']['star'] ) && $product_data['reviews']['star'] <= 5 ) {
                                                $percent_rating = $product_data['reviews']['star'] * 20;
                                                echo '<span class="rating"><i style="width:' .$percent_rating. '%"></i></span>';
                                            }
                                            if( isset( $product_data['reviews']['total'] ) ) {
                                                echo '<span>(' .$product_data['reviews']['total']. ')</span>';
                                            }
                                            echo '</div>';
                                        }
                                        echo '</td>';
                                        echo '<td class="column-display" style="width: 30%">' .apply_filters('reviewengine_display_buttons', ''). '</td>';
                                        echo '</tr>';
                                    }
                                endwhile;
                                wp_reset_postdata();
                            else:
                                echo '<tr>';
                                echo '<td colspan="3">' .__('Whoops, There are no products in the database yet. Click <a href="#" class="ree-link-quickcreate">here</a> to add your first product.'). '</td>';
                                echo '</tr>';
                            endif;
                            ?>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="column-product"><?php _e('Product'); ?></th>
                                <th class="column-display" style="width: 30%"><?php _e('Display'); ?></th>
                            </tr>
                        </tfoot>
                    </table><!-- .table -->

                    <button type="button" class="btn btn-primary button-more" style="<?php echo $the_query->max_num_pages > 1 ? '' : 'display: none;'; ?>">
                        <span class="text"><?php _e('Load More Products'); ?></span>
                    </button>
                </div><!-- #search-results -->
            </div><!-- #search -->

            <?php do_action('reviewengine_display_content'); ?>
        </div><!-- .wrap -->

        <script type="text/html" id="template-search-result">
        <tr data-post_id="{{id}}">
            <td class="column-photo"><img src="{{photo}}"/></td>
            <td class="column-product">
                <p><a href="{{url}}" target="_blank"><strong>{{name}}</strong></a></p>
                <div class="product-info">
                    <p><strong>{{price}}</strong></p>
                    <span class="rating"><i style="width:{{percent_rating}}%"></i></span>
                    <span>({{total_reviews}})</span>
                </div>
            </td>
            <td class="column-display" style="width: 30%"><?php echo apply_filters('reviewengine_display_buttons', ''); ?></td>
        </tr>
    </script>
</body>
<?php 

add_action('wp_ajax_filter_films', 'filter_films_ajax_handler');
add_action('wp_ajax_nopriv_filter_films', 'filter_films_ajax_handler');

function filter_films_ajax_handler() {
    $args = [
        'post_type' => 'films',
        'posts_per_page' => -1,
        'meta_query' => [],
        'tax_query' => [],
    ];

    // Filter by cost
    $price_min = isset($_POST['price_min']) ? floatval($_POST['price_min']) : 0;
    $price_max = isset($_POST['price_max']) ? floatval($_POST['price_max']) : 0;

    if ($price_min || $price_max) {
        $price_filter = ['key' => 'price', 'type' => 'NUMERIC'];
        if ($price_min && $price_max) {
            $price_filter['value'] = [$price_min, $price_max];
            $price_filter['compare'] = 'BETWEEN';
        } elseif ($price_min) {
            $price_filter['value'] = $price_min;
            $price_filter['compare'] = '>=';
        } elseif ($price_max) {
            $price_filter['value'] = $price_max;
            $price_filter['compare'] = '<=';
        }
        $args['meta_query'][] = $price_filter;
    }

    // Filter by genres
    if (!empty($_POST['genres']) && is_array($_POST['genres'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'genre',
            'field' => 'term_id',
            'terms' => array_map('intval', $_POST['genres']),
            'operator' => 'IN',
        ];
    }

    // Filter by country
    if (!empty($_POST['countries']) && is_array($_POST['countries'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'country',
            'field' => 'term_id',
            'terms' => array_map('intval', $_POST['countries']),
            'operator' => 'IN',
        ];
    }

    if (count($args['tax_query']) > 1) {
        $args['tax_query']['relation'] = 'AND';
    }

    // Sorting
    $allowed_sortby = ['price', 'release_date'];
    $sortby = (isset($_POST['sortby']) && in_array($_POST['sortby'], $allowed_sortby)) ? $_POST['sortby'] : 'price';
    $sortdir = (isset($_POST['sortdir']) && in_array(strtoupper($_POST['sortdir']), ['ASC', 'DESC'])) ? strtoupper($_POST['sortdir']) : 'ASC';

    $args['orderby'] = 'meta_value_num';
    $args['meta_key'] = $sortby;
    $args['order'] = $sortdir;

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            ?>
           <?php get_template_part( 'template-parts/content', get_post_type() ); ?>
            <?php
        }
    } else {
        echo '<p>Фильмы не найдены.</p>';
    }
    wp_reset_postdata();
    wp_die();
}
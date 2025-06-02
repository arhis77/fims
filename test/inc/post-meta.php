<?php

// Register custom post type "films"
function register_films_post_type() {
    register_post_type('films', [
        'labels' => [
            'name' => 'Фильмы',
            'singular_name' => 'Фильм',
        ],
        'public' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'films'],
        'supports' => ['title', 'editor', 'thumbnail'],
    ]);
}
add_action('init', 'register_films_post_type');

// Enable thumbnail support for films type
function films_setup() {
    add_theme_support('post-thumbnails', ['films']);
}
add_action('after_setup_theme', 'films_setup');

// Taksonomies: genre
register_taxonomy('genre', 'films', [
    'label' => 'Жанры',
    'hierarchical' => true,
    'rewrite' => ['slug' => 'genre'],
]);

// Taksonomies: films
register_taxonomy('country', 'films', [
    'label' => 'Страны',
    'hierarchical' => true,
    'rewrite' => ['slug' => 'country'],
]);

// Added custom fields
function add_films_meta_boxes() {
    add_meta_box('films_meta', 'Детали фильма', 'films_meta_callback', 'films', 'normal', 'high');
}
add_action('add_meta_boxes', 'add_films_meta_boxes');

function films_meta_callback($post) {
    $price = get_post_meta($post->ID, 'price', true);
    $release_date = get_post_meta($post->ID, 'release_date', true);
    ?>
    <label>Стоимость:</label>
    <input type="number" name="price" value="<?php echo esc_attr($price); ?>" /><br><br>
    <label>Дата выхода:</label>
    <input type="date" name="release_date" value="<?php echo esc_attr($release_date); ?>" />
    <?php
}

function save_films_meta($post_id) {
    if (isset($_POST['price'])) {
        update_post_meta($post_id, 'price', intval($_POST['price']));
    }
    if (isset($_POST['release_date'])) {
        update_post_meta($post_id, 'release_date', sanitize_text_field($_POST['release_date']));
    }
}
add_action('save_post', 'save_films_meta');

// Added product_id field
function films_add_product_id_meta_box() {
    add_meta_box('films_product_id', 'ID товара WooCommerce', 'films_product_id_meta_box_callback', 'films', 'side');
}
add_action('add_meta_boxes', 'films_add_product_id_meta_box');

function films_product_id_meta_box_callback($post) {
    $product_id = get_post_meta($post->ID, 'product_id', true);
    ?>
    <label for="films_product_id_field">ID товара:</label>
    <input type="number" id="films_product_id_field" name="films_product_id_field" value="<?php echo esc_attr($product_id); ?>" style="width:100%;" />
    <?php
}

function films_save_product_id_meta_box($post_id) {
    if (isset($_POST['films_product_id_field'])) {
        update_post_meta($post_id, 'product_id', intval($_POST['films_product_id_field']));
    }
}
add_action('save_post', 'films_save_product_id_meta_box');

/**
 * transfer the price
 */
function get_film_price_by_product_id($product_id) {
    $args = [
        'post_type' => 'films',
        'meta_query' => [
            [
                'key' => 'product_id',
                'value' => $product_id,
                'compare' => '=',
            ]
        ],
        'posts_per_page' => 1,
        'fields' => 'ids',
    ];
    $query = new WP_Query($args);
    if (!empty($query->posts)) {
        $film_id = $query->posts[0];
        $price = get_post_meta($film_id, 'price', true);
        return $price ? floatval($price) : false;
    }
    return false;
}

add_filter('woocommerce_get_price_html', 'custom_price_html_from_film', 10, 2);
function custom_price_html_from_film($price_html, $product) {
    $custom_price = get_film_price_by_product_id($product->get_id());
    if ($custom_price !== false && $custom_price > 0) {
        $price_html = wc_price($custom_price);
    }
    return $price_html;
}

add_action('woocommerce_before_calculate_totals', 'custom_cart_item_price_from_film', 20, 1);
function custom_cart_item_price_from_film($cart) {
    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }
    foreach ($cart->get_cart() as $cart_item) {
        $product = $cart_item['data'];
        $custom_price = get_film_price_by_product_id($product->get_id());
        if ($custom_price !== false && $custom_price > 0) {
            $product->set_price($custom_price);
        }
    }
}

// Update price
function update_product_price_from_film($post_id) {
    if (get_post_type($post_id) !== 'films') {
        return;
    }

    $product_id = get_post_meta($post_id, 'product_id', true);
    if (!$product_id) {
        return;
    }

    $price = get_post_meta($post_id, 'price', true);
    if ($price === '') {
        return;
    }

    update_post_meta($product_id, '_price', $price);
    update_post_meta($product_id, '_regular_price', $price);
}
add_action('save_post', 'update_product_price_from_film');



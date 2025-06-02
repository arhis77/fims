<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div class="container">
        <div class="row">
            <div class="film-card">
                <!-- Thumbnail -->
                <div class="film-thumb">
                    <?php if (has_post_thumbnail()) {
                        the_post_thumbnail('medium');
                    } ?>
                </div>
                
                <!-- Title -->
                <h2 class="film-title">
                    <?php the_title(); ?>
                </h2>

                <!-- Excerpt -->
                <div class="film-excerpt">
                    <?php the_excerpt(); ?>
                </div>

                <!-- Price -->
                <div class="film-price">
                    <?php
                    $price = get_post_meta(get_the_ID(), 'price', true);
                    if ($price) {
                        echo '<strong>Стоимость:</strong> ' . esc_html($price) . ' ₽';
                    }
                    ?>
                </div>

                <!-- Date -->
                <div class="film-release-date">
                    <?php
                    $release_date = get_post_meta(get_the_ID(), 'release_date', true);
                    if ($release_date) {
                        echo '<strong>Дата выхода:</strong> ' . esc_html(date('d.m.Y', strtotime($release_date)));
                    }
                    ?>
                </div>

                <!-- Genres -->
                <div class="film-genres">
                    <?php
                    $genres = get_the_terms(get_the_ID(), 'genre');
                    if ($genres && !is_wp_error($genres)) {
                        echo '<strong>Жанры:</strong> ';
                        $genre_names = wp_list_pluck($genres, 'name');
                        echo esc_html(implode(', ', $genre_names));
                    }
                    ?>
                </div>

                <!-- Countries -->
                <div class="film-countries">
                    <?php
                    $countries = get_the_terms(get_the_ID(), 'country');
                    if ($countries && !is_wp_error($countries)) {
                        echo '<strong>Страны:</strong> ';
                        $country_names = wp_list_pluck($countries, 'name');
                        echo esc_html(implode(', ', $country_names));
                    }
                    ?>
                </div>

                <!-- Add to Cart btn -->
                <div class="film-add-to-cart">
                    <?php
                        $product_id = get_post_meta(get_the_ID(), 'product_id', true);
                        if ($product_id && function_exists('wc_get_product')) {
                            $product = wc_get_product($product_id);
                            if ($product && $product->is_virtual()) {
                                echo '<a href="' . esc_url('?add-to-cart=' . $product_id) . '" class="button add-to-cart">Добавить в корзину</a>';
                            }
                        }

                    ?>
                </div>

            </div>
        </div>
    </div>
<?php endwhile; endif; ?>
<?php get_footer(); ?>

<?php
	get_header();
?>
<div class="container">
	<div class="row">
		<h1>
			<?php post_type_archive_title(); ?>
		</h1>

		<div class="films">

			<form id="filmsFilterForm" class="film-filters">
				<div>
					<label>Стоимость от: <input type="number" name="price_min" min="0" /></label>
					<label>до: <input type="number" name="price_max" min="0" /></label>
				</div>

				<div>
					<strong>Жанры:</strong><br>
					<?php
						$genres = get_terms(['taxonomy' => 'genre', 'hide_empty' => false]);
						foreach ($genres as $genre) {
							echo '<label><input type="checkbox" name="genres[]" value="' . esc_attr($genre->term_id) . '"> ' . esc_html($genre->name) . '</label><br>';
						}
					?>
				</div>

				<div>
					<strong>Страны:</strong><br>
					<?php
						$countries = get_terms(['taxonomy' => 'country', 'hide_empty' => false]);
						foreach ($countries as $country) {
							echo '<label><input type="checkbox" name="countries[]" value="' . esc_attr($country->term_id) . '"> ' . esc_html($country->name) . '</label><br>';
						}
					?>
				</div>

				<div>
					<label>Сортировка:
					<select name="sortby">
						<option value="price">Стоимость</option>
						<option value="release_date">Дата выхода</option>
					</select>
					</label>
					<select name="sortdir">
					<option value="ASC">По возрастанию</option>
					<option value="DESC">По убыванию</option>
					</select>
				</div>

				<button type="submit">Применить фильтр</button>
			</form>

			<div  id="films-results">
				<div class="films-archive">
					

					<?php if (have_posts()) : ?>
						<div class="films-list">
							<?php while (have_posts()) : the_post(); ?>
								<?php get_template_part( 'template-parts/content', get_post_type() ); ?>
							<?php endwhile; ?>
						</div>
						<div class="films-pagination">
							<?php the_posts_pagination(); ?>
						</div>
						<?php else : ?>
							<p>Фильмы не найдены.</p>
						<?php endif; ?>
				</div>
			</div>
		</div>
			</div>
		</div>

<?php
	get_footer();
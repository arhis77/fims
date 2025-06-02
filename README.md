# Films Project — Custom Post Type for WordPress

## Short Description
This project implements a custom post type `films` for WordPress, allowing you to conveniently manage movies as a separate entity. For each movie, a related virtual WooCommerce product is automatically created, the price of which is taken from the custom `price` field of the `films` record. Synchronization of description, title and preview between the movie and the product is also implemented.

## How to install
1. Copy the `test` theme files to wp-content/themes/.
2. Activate the `test` theme in the admin panel under "Appearance / Themes".
3. Install and activate the WooCommerce plugin.
5. Update permalinks in the WordPress admin panel (Settings → Permalinks → Save).

## Where and how to test
- Test on a local or staging WordPress environment with active WooCommerce.
- Create a new record of type `films`, fill in the product data, including custom fields, content and thumbnail.
- You also need to create a virtual WooCommerce product and pass its id to the product_id field of the record of type `films`.

## Implementation Features
- Adding a product to the cart is implemented by creating a virtual product.
- Automatic update of the price of the associated product when saving a record of type `films` is implemented.
- The relationship between the film and the product is carried out through the `product_id` meta field in the `films` record.
- The price of the product is dynamically updated from the `price` field of the film.

# Проект Films — Кастомный тип записи для WordPress

## Краткое описание
Данный проект реализует пользовательский тип записи `films` для WordPress, позволяющий удобно управлять фильмами как отдельной сущностью. Для каждого фильма автоматически создаётся связанный виртуальный товар WooCommerce, цена которого берётся из произвольного поля `price` записи `films`. Также реализована синхронизация описания, названия и превью между фильмом и товаром.

## Как установить
1. Скопируйте файлы темы `test` в wp-content/themes/.
2. Актиивируйте тему `test` в административной панели в разделе "Внешний вид / Темы".
3. Установите и актиивруйте плагин WooCommerce.
5. Обновите постоянные ссылки в админке WordPress (Настройки → Постоянные ссылки → Сохранить).

## Где и как тестировать
- Тестируйте на локальной или тестовой среде WordPress с активным WooCommerce.
- Создайте новую запись типа `films`, заполните данные о товаре, включая кастомные поля, контент и миниатюру.
- Так же необходимо создать виртуальный товар WooCommerce и передать его id в поле product_id записи типа `films`.

## Особенности реализации
- Добавление товара в корзину реализовано посредством создания вирутального товара.
- Реализовано автоматическое обновление цены связанного товара при сохранение записи типа `films`.
- Связь между фильмом и товаром осуществляется через мета-поле `product_id` в записи `films`.
- Цена товара динамически обновляется из поля `price` фильма.

- 

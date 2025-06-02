document.addEventListener('DOMContentLoaded', function() {

    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', function() {
            let productId = this.dataset.product_id;
            fetch('/?add-to-cart=' + productId)
                .then(() => alert('Добавлено в корзину!'));
        });
    });

    // FILTERS
    const form = document.getElementById('filmsFilterForm');
    const resultsContainer = document.getElementById('films-results');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(form);
        formData.append('action', 'filter_films');

        fetch(ajaxurl, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.text();
        })
        .then(html => {
            resultsContainer.innerHTML = html;
        })
        .catch(error => {
            console.error('Ошибка AJAX:', error);
        });
    });

});

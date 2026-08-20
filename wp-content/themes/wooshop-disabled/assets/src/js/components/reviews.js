/**
 * WooShop Advanced Reviews
 *
 * Handles AJAX review filtering.
 *
 * @package WooShop
 */

document.addEventListener('DOMContentLoaded', () => {

    const summary = document.querySelector('[data-ws-review-summary]');

    if (!summary || typeof WooShopReviews === 'undefined') {
        return;
    }

    const list = summary.querySelector('[data-ws-reviews-list]');

    const productId = list?.dataset.productId;

    if (!list || !productId) {
        return;
    }

    const loadReviews = async (rating = 0, page = 1) => {

        list.classList.add('is-loading');

        const data = new FormData();

        data.append('action', WooShopReviews.action);

        data.append('nonce', WooShopReviews.nonce);

        data.append('product_id', productId);

        data.append('rating', rating);

        data.append('page', page);

        try {

            const response = await fetch(WooShopReviews.ajaxUrl, {
                method: 'POST', body: data,
            });

            const result = await response.json();

            if (!result.success) {
                throw new Error(WooShopReviews.error);
            }

            list.innerHTML = result.data.html;

        } catch (error) {

            list.innerHTML = `
				<div class="alert alert-danger">
					${WooShopReviews.error}
				</div>
			`;

            console.error('WooShop reviews error:', error);

        } finally {

            list.classList.remove('is-loading');
        }
    };

    summary
        .querySelectorAll('[data-ws-review-rating]')
        .forEach((button) => {

            button.addEventListener('click', () => {

                const rating = parseInt(button.dataset.wsReviewRating, 10);

                summary
                    .querySelectorAll('[data-ws-review-rating]')
                    .forEach((item) => {
                        item.classList.remove('is-active');
                    });

                button.classList.add('is-active');

                loadReviews(rating, 1);
            });
        });
});
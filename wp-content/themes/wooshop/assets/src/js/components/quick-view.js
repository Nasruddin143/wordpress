/**
 * WooShop Quick View
 *
 * Handles AJAX product quick-view modal interactions.
 *
 * @package WooShop
 */

document.addEventListener('DOMContentLoaded', () => {

    const buttons = document.querySelectorAll('[data-ws-quick-view]');

    if (!buttons.length || typeof WooShopQuickView === 'undefined') {
        return;
    }

    const createModal = () => {

        let modal = document.querySelector('[data-ws-quick-view-modal]');

        if (modal) {
            return modal;
        }

        modal = document.createElement('div');

        modal.className = 'ws-quick-view-modal';
        modal.dataset.wsQuickViewModal = '';

        modal.innerHTML = `
			<div
				class="ws-quick-view-modal__backdrop"
				data-ws-quick-view-close
			></div>

			<div
				class="ws-quick-view-modal__dialog"
				role="dialog"
				aria-modal="true"
				aria-label="${WooShopQuickView.loading}"
			>
				<button
					type="button"
					class="ws-quick-view-modal__close"
					data-ws-quick-view-close
					aria-label="Close"
				>
					&times;
				</button>

				<div class="ws-quick-view-modal__body">
					<div class="ws-quick-view-modal__loading">
						${WooShopQuickView.loading}
					</div>
				</div>
			</div>
		`;

        document.body.appendChild(modal);

        modal
            .querySelectorAll('[data-ws-quick-view-close]')
            .forEach((element) => {

                element.addEventListener('click', () => closeModal(modal));
            });

        return modal;
    };

    const closeModal = (modal) => {

        modal.classList.remove('is-open');

        document.body.classList.remove('ws-quick-view-open');
    };

    const openModal = async (button) => {

        const productId = button.dataset.productId;

        if (!productId) {
            return;
        }

        const modal = createModal();
        const body = modal.querySelector('.ws-quick-view-modal__body');

        modal.classList.add('is-open');

        document.body.classList.add('ws-quick-view-open');

        body.innerHTML = `
			<div class="ws-quick-view-modal__loading">
				${WooShopQuickView.loading}
			</div>
		`;

        const data = new FormData();

        data.append('action', WooShopQuickView.action);

        data.append('nonce', WooShopQuickView.nonce);

        data.append('product_id', productId);

        try {

            const response = await fetch(WooShopQuickView.ajaxUrl, {
                method: 'POST', body: data,
            });

            const result = await response.json();

            if (!result.success) {
                throw new Error(WooShopQuickView.error);
            }

            body.innerHTML = result.data.html;

            /*
             * Allow WooCommerce variation scripts to
             * initialize newly inserted variation forms.
             */
            if (window.jQuery && typeof window.jQuery.fn.wc_variation_form === 'function') {

                window.jQuery(body)
                    .find('.variations_form')
                    .each(function () {

                        window.jQuery(this)
                            .wc_variation_form();
                    });
            }

        } catch (error) {

            body.innerHTML = `
				<div class="alert alert-danger">
					${WooShopQuickView.error}
				</div>
			`;
        }
    };

    buttons.forEach((button) => {

        button.addEventListener('click', () => openModal(button));
    });

    document.addEventListener('keydown', (event) => {

        if (event.key !== 'Escape') {
            return;
        }

        const modal = document.querySelector('[data-ws-quick-view-modal]');

        if (modal) {
            closeModal(modal);
        }
    });
});
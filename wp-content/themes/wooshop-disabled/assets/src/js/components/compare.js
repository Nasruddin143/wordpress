/**
 * WooShop Product Compare
 *
 * Handles product comparison interactions.
 *
 * @package WooShop
 */

document.addEventListener('DOMContentLoaded', () => {

    const buttons = document.querySelectorAll('[data-ws-compare]');

    if (typeof WooShopCompare === 'undefined') {
        return;
    }

    const getIds = () => {

        const value = document.cookie
            .split('; ')
            .find((row) => row.startsWith('wooshop_compare='));

        if (!value) {
            return [];
        }

        try {
            return JSON.parse(decodeURIComponent(value.split('=')[1]));
        } catch {
            return [];
        }
    };

    const updateButtons = (ids) => {

        document
            .querySelectorAll('[data-ws-compare]')
            .forEach((button) => {

                const id = parseInt(button.dataset.productId, 10);

                const active = ids.includes(id);

                button.classList.toggle('is-active', active);

                button.setAttribute('aria-pressed', active ? 'true' : 'false');

                button.textContent = active ? WooShopCompare.removeText : WooShopCompare.addText;
            });
    };

    const updateBar = (ids) => {

        const bar = document.querySelector('[data-ws-compare-bar]');

        if (!bar) {
            return;
        }

        bar.hidden = ids.length === 0;

        const items = bar.querySelector('[data-ws-compare-items]');

        if (!items) {
            return;
        }

        items.innerHTML = ids.map((id) => `
				<span
					class="ws-compare-bar__item"
					data-product-id="${id}"
				>
					${id}
				</span>
			`).join('');
    };

    const toggle = async (button) => {

        const productId = button.dataset.productId;

        if (!productId) {
            return;
        }

        button.disabled = true;

        const data = new FormData();

        data.append('action', WooShopCompare.action);

        data.append('nonce', WooShopCompare.nonce);

        data.append('product_id', productId);

        try {

            const response = await fetch(WooShopCompare.ajaxUrl, {
                method: 'POST', body: data,
            });

            const result = await response.json();

            if (!result.success) {

                if (result.data?.message) {
                    window.alert(result.data.message);
                }

                return;
            }

            const ids = result.data.ids || [];

            updateButtons(ids);
            updateBar(ids);

        } catch (error) {

            console.error('WooShop compare error:', error);

        } finally {

            button.disabled = false;
        }
    };

    buttons.forEach((button) => {

        button.addEventListener('click', () => toggle(button));
    });

    updateBar(getIds());

    const renderComparisonTable = async () => {

        const table = document.querySelector('[data-ws-compare-table]');

        if (!table) {
            return;
        }

        const content = table.querySelector('[data-ws-compare-content]');

        const loading = table.querySelector('[data-ws-compare-loading]');

        const data = new FormData();

        data.append('action', WooShopCompare.dataAction);

        data.append('nonce', WooShopCompare.nonce);

        try {

            const response = await fetch(WooShopCompare.ajaxUrl, {
                method: 'POST', body: data,
            });

            const result = await response.json();

            if (!result.success) {
                return;
            }

            const products = result.data.products || [];

            if (loading) {
                loading.hidden = true;
            }

            if (!products.length) {

                content.innerHTML = `
				<div class="alert alert-light">
					No products selected for comparison.
				</div>
			`;

                return;
            }

            content.innerHTML = `
			<div class="table-responsive">
				<table class="table ws-compare-table__grid">
					<thead>
						<tr>
							<th>Product</th>
							${products.map((product) => `
									<th>
										<a href="${product.url}">
											${product.image ? `<img
													src="${product.image}"
													alt="${product.name}"
												>` : ''}
											<span>
												${product.name}
											</span>
										</a>
									</th>
								`).join('')}
						</tr>
					</thead>

					<tbody>

						<tr>
							<th>Price</th>
							${products.map((product) => `<td>${product.price}</td>`).join('')}
						</tr>

						<tr>
							<th>Rating</th>
							${products.map((product) => `<td>${product.rating}</td>`).join('')}
						</tr>

						<tr>
							<th>Reviews</th>
							${products.map((product) => `<td>${product.reviews}</td>`).join('')}
						</tr>

						<tr>
							<th>SKU</th>
							${products.map((product) => `<td>${product.sku || '—'}</td>`).join('')}
						</tr>

						<tr>
							<th>Availability</th>
							${products.map((product) => `<td>${product.availability}</td>`).join('')}
						</tr>

					</tbody>
				</table>
			</div>
		`;

        } catch (error) {

            console.error('WooShop comparison error:', error);

        }
    };

    renderComparisonTable();
});
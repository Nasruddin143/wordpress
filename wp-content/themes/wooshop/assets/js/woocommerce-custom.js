document.addEventListener("DOMContentLoaded", function () {
  const stickyCart = document.querySelector(".kt-sticky-cart");

  const stickyFormWrapper = document.querySelector(".kt-sticky-cart-form");

  const originalForm = document.querySelector("form.cart");

  if (!stickyCart || !stickyFormWrapper || !originalForm) {
    return;
  }

  /*
	|--------------------------------------------------------------------------
	| Clone WooCommerce Form
	|--------------------------------------------------------------------------
	*/

  const clonedForm = originalForm.cloneNode(true);

  // Remove duplicate IDs
  clonedForm.querySelectorAll("[id]").forEach((el) => {
    el.removeAttribute("id");
  });

  stickyFormWrapper.appendChild(clonedForm);

  /*
	|--------------------------------------------------------------------------
	| Toggle Sticky Cart
	|--------------------------------------------------------------------------
	*/

  function toggleStickyCart() {
    const rect = originalForm.getBoundingClientRect();

    const visible = rect.top >= 0 && rect.bottom <= window.innerHeight;

    if (visible) {
      stickyCart.classList.remove("show");
    } else {
      stickyCart.classList.add("show");
    }
  }

  toggleStickyCart();

  window.addEventListener("scroll", toggleStickyCart, {
    passive: true,
  });

  /*
	|--------------------------------------------------------------------------
	| Sync Quantity
	|--------------------------------------------------------------------------
	*/

  const originalQty = originalForm.querySelector("input.qty");

  const stickyQty = clonedForm.querySelector("input.qty");

  if (originalQty && stickyQty) {
    stickyQty.addEventListener("input", function () {
      originalQty.value = this.value;
    });

    originalQty.addEventListener("input", function () {
      stickyQty.value = this.value;
    });
  }

  /*
	|--------------------------------------------------------------------------
	| Submit Original Form
	|--------------------------------------------------------------------------
	*/

  clonedForm.addEventListener("submit", function (e) {
    e.preventDefault();

    originalForm.requestSubmit();
  });
});

document.addEventListener("click", function (e) {
  const plusBtn = e.target.closest(".kt-qty-plus");
  const minusBtn = e.target.closest(".kt-qty-minus");

  if (!plusBtn && !minusBtn) {
    return;
  }

  const wrapper = e.target.closest(".kt-quantity");

  if (!wrapper) {
    return;
  }

  const input = wrapper.querySelector("input.qty");

  if (!input) {
    return;
  }

  let current = parseFloat(input.value) || 1;

  const min = parseFloat(input.min) || 1;
  const max = parseFloat(input.max) || 9999;
  const step = parseFloat(input.step) || 1;

  if (plusBtn) {
    current += step;
  }

  if (minusBtn) {
    current -= step;
  }

  if (current < min) {
    current = min;
  }

  if (current > max) {
    current = max;
  }

  input.value = current;

  input.dispatchEvent(
    new Event("change", {
      bubbles: true,
    }),
  );
});

document.addEventListener("click", function (e) {
  const reviewLink = e.target.closest(".woocommerce-review-link");

  if (!reviewLink) {
    return;
  }

  const reviewTabTrigger = document.querySelector(
    '[data-bs-target="#tab-reviews"]',
  );

  if (!reviewTabTrigger) {
    return;
  }

  // Open Bootstrap tab
  const tab = new bootstrap.Tab(reviewTabTrigger);

  tab.show();

  // Smooth scroll
  const reviewsSection = document.getElementById("tab-reviews");

  if (reviewsSection) {
    setTimeout(() => {
      reviewsSection.scrollIntoView({
        behavior: "smooth",
      });
    }, 150);
  }
});

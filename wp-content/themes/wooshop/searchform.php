<div class="desktop-search">
    <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
        <div class="input-group p-1 border rounded-pill bg-white align-items-center shadow-sm custom-search-group">
            <!-- Text Input Field -->
            <input type="search"
                   class="form-control border-0 bg-transparent ps-3 text-secondary shadow-none"
                   placeholder="Search for products"
                   value="<?php echo get_search_query(); ?>"
                   name="s"/>

            <!-- Hidden input to restrict search strictly to WooCommerce products if needed -->
            <input type="hidden" name="post_type" value="product"/>

            <!-- Submit Button -->
            <button class="btn btn-primary rounded-pill px-4 py-2 d-flex align-items-center justify-content-center"
                    type="submit">
                <!-- https://feathericons.dev/?search=search&iconset=feather -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" x2="16.65" y1="21" y2="16.65" />
                </svg>

            </button>
        </div>
    </form>
</div>
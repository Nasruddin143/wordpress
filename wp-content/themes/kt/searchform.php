<div class="nav-search-wrapper">
    <form id="site-search" class="nav-search-form" action="<?php echo home_url('/'); ?>" method="get" role="search">
    <div class="input-group">    
    <input type="text" name="s" class="form-control" placeholder="<?php echo esc_attr(__('Search...')) ?>" />
        <button class="nav-search-toggle btn btn-dark" aria-label="Search">
        <i class="bi bi-search"></i>
    </button>
    </div>
    </form>
</div>
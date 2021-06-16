<?php
$search = get_option( 'ca_google_search_id', '' );

$toggle = CAWEB_MINIMUM_SUPPORTED_TEMPLATE_VERSION < caweb_template_version() ? ' data-toggle="collapse" data-target="#navigation"' : '';
?>
<!-- mobile navigation controls -->
<div class="mobile-controls">
    <span class="mobile-control-group mobile-header-icons">
        <!-- Add more mobile controls here. These will be on the right side of the mobile page header section -->
    </span>
    <div class="mobile-control-group main-nav-icons">
        <?php if(  ! empty( $search ) ): ?>
        <button class="mobile-control toggle-search">
            <span class="ca-gov-icon-search hidden-print" aria-hidden="true"></span><span class="sr-only">Search</span>
        </button>
        <?php endif; ?>
        <button id="nav-icon3" class="mobile-control toggle-menu" aria-expanded="false" aria-controls="navigation" data-toggle="collapse" data-target="#navigation"<?php print $toggle; ?>>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span class="sr-only">Menu</span>
        </button>

    </div>
</div>
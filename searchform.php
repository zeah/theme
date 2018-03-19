<?php
/**
 * default search form
 */
?>
<form role="search" method="get" id="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <div class="search-wrap">
        <input type="search" class="emtheme-search-input" placeholder="<?php echo esc_attr( 'Search…', 'presentation' ); ?>" name="s" id="search-input" value="<?php echo esc_attr( get_search_query() ); ?>" />
        <button type="submit"><i class="material-icons emtheme-search-icon">search</i></button>
        <!-- <input class="screen-reader-text" type="submit" id="search-submit" value="Search" /> -->
    </div>
</form>
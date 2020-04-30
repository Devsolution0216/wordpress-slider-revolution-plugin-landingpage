<?php
/**
 * The search form displayed in our theme.
 *
 * @package Brand
 */

$form = '<form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">
         	<label>
            	<span class="screen-reader-text">' . _x( 'Search for:', 'label', 'brand' ) . '</span>
                <input type="search" class="search-field" placeholder="' . esc_attr_x( 'Search &hellip;', 'placeholder', 'brand' ) . '" value="' . get_search_query() . '" name="s" title="' . esc_attr_x( 'Search for:', 'label', 'brand' ) . '" />
            </label>
         </form>';

echo $form; // WPCS: XSS ok.

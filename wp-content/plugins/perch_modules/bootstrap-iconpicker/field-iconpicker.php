<?php
if ( class_exists( 'RWMB_Field' ) ) {
    class RWMB_Iconpicker_Field extends RWMB_Field {
        public static function html( $meta, $field ) {
            wp_enqueue_style( 'perch-iconpicker-iconpicker');
            wp_enqueue_script('perch-iconpicker-iconpicker');

            return sprintf(
                '<div class="perch-modules-iconpicker"><button class="btn btn-primary" role="iconpicker" data-iconset="fontawesome5" data-icon="'.$meta.'"></button><input type="hidden" class="perch-modules-iconpicker-input" name="%s" value="%s"></div>',
                $field['field_name'],
                $meta
            );
        }
    }
}

<?php
if ( class_exists( 'RWMB_Field' ) ) {
    class RWMB_Color_gradient_Field extends RWMB_Field {
        public static function html( $meta, $field ) {
            $meta = shortcode_atts(array('from' => '', 'to' => ''), $meta);
            $output = sprintf(
                'From: <input type="text" class="rwmb-color" name="%s[from]" id="%s-from" value="%s"  data-options="defaultColor:true">',
                $field['field_name'],
                $field['id'],
                isset($meta['from'])? $meta['from'] : $field['std']['from']
            );

            $output .= sprintf(
                'To: <input type="text" class="rwmb-color" name="%s[to]" id="%s-to" value="%s" data-options="defaultColor:true">',
                $field['field_name'],
                $field['id'],
                isset($meta['to'])? $meta['to'] : $field['std']['to']
            );

            return $output;
        }
    }
}
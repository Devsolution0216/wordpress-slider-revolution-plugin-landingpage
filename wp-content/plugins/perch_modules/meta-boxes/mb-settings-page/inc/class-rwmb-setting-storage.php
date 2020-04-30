<?php
/**
 * Option storage
 *
 * @package    Meta Box
 * @subpackage MB Term Meta
 */

if ( interface_exists( 'RWMB_Storage_Interface' ) ) {
	/**
	 * Class RWMB_Setting_Storage
	 */
	class RWMB_Setting_Storage implements RWMB_Storage_Interface {

		/**
		 * Get value from storage.
		 *
		 * @param int    $object_id Object id. Use option name in this case.
		 * @param string $name      Field name.
		 * @param array  $args      Custom arguments.
		 *
		 * @return mixed
		 */
		public function get( $object_id, $name, $args = array() ) {
			if ( is_array( $args ) ) {
				$single = ! empty( $args['single'] );
			} else {
				$single = (bool) $args;
			}

			$settings = $this->get_all_settings( $object_id );
			return isset( $settings[ $name ] ) ? $settings[ $name ] : ( $single ? '' : array() );
		}

		/**
		 * Add setting
		 *
		 * @param int    $object_id  Option name.
		 * @param string $meta_key   Key of element want to add.
		 * @param mixed  $meta_value Value of element want to add.
		 * @param bool   $unique     Unused param.
		 *
		 * @return bool
		 */
		public function add( $object_id, $meta_key, $meta_value, $unique = false ) {
			if ( $unique ) {
				return $this->update( $object_id, $meta_key, $meta_value );
			}

			$setting = (array) $this->get( $object_id, $meta_key, array(
				'std' => array(),
			) );

			$meta_value = wp_unslash( $meta_value );
			$setting[]  = $meta_value;

			return $this->update( $object_id, $meta_key, $setting );
		}

		/**
		 * Update setting.
		 *
		 * @param int    $object_id    ID of the object metadata is for.
		 * @param string $meta_key     Metadata key.
		 * @param mixed  $meta_value   Metadata value. Must be serializable if non-scalar.
		 * @param mixed  $prev_value   Optional. If specified, only update existing metadata entries with
		 *                             the specified value. Otherwise, update all entries.
		 *
		 * @return int|bool Meta ID if the key didn't exist, true on successful update, false on failure.
		 *
		 * @see update_metadata()
		 */
		public function update( $object_id, $meta_key, $meta_value, $prev_value = '' ) {
			$settings              = $this->get_all_settings( $object_id );
			$meta_value            = wp_unslash( $meta_value );
			$settings[ $meta_key ] = $meta_value;
			return update_option( $object_id, $settings );
		}

		/**
		 * Delete metadata.
		 *
		 * @param int    $object_id  ID of the object metadata is for.
		 * @param string $meta_key   Metadata key.
		 * @param mixed  $meta_value Optional. Metadata value. Must be serializable if non-scalar. If specified, only delete
		 *                           metadata entries with this value. Otherwise, delete all entries with the specified meta_key.
		 *                           Pass `null, `false`, or an empty string to skip this check. (For backward compatibility,
		 *                           it is not possible to pass an empty string to delete those entries with an empty string
		 *                           for a value).
		 * @param bool   $delete_all Optional, default is false. If true, delete matching metadata entries for all objects,
		 *                           ignoring the specified object_id. Otherwise, only delete matching metadata entries for
		 *                           the specified object_id.
		 *
		 * @return bool True on successful delete, false on failure.
		 *
		 * @see delete_metadata()
		 */
		public function delete( $object_id, $meta_key, $meta_value = '', $delete_all = false ) {
			$settings = $this->get_all_settings( $object_id );
			if ( ! isset( $settings[ $meta_key ] ) ) {
				return true;
			}

			if ( $delete_all || ! $meta_value || $settings[ $meta_key ] === $meta_value ) {
				unset( $settings[ $meta_key ] );
				return update_option( $object_id, $settings );
			}

			if ( ! is_array( $settings[ $meta_key ] ) ) {
				return true;
			}

			// For field with multiple values.
			foreach ( $settings[ $meta_key ] as $key => $value ) {
				if ( $value === $meta_value ) {
					unset( $settings[ $meta_key ][ $key ] );
				}
			}
			return update_option( $object_id, $settings );
		}

		/**
		 * Get all settings.
		 *
		 * @param string $option_name Option name.
		 *
		 * @return array
		 */
		protected function get_all_settings( $option_name ) {
			return (array) get_option( $option_name, array() );
		}
	}
}

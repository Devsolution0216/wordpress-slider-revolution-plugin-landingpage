<?php

namespace WPForms\Integrations;

/**
 * Interface IntegrationInterface defines required methods for integrations to work properly.
 *
 * @since 1.4.8
 */
interface IntegrationInterface {

	/**
	 * Indicates if current integration is allowed to load.
	 *
	 * @since 1.4.8
	 *
	 * @return bool
	 */
	public function allow_load();

	/**
	 * Loads an integration.
	 *
	 * @since 1.4.8
	 */
	public function load();
}

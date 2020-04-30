<?php

namespace WPForms\Tasks;

use WPForms\Tasks\Actions\EntryEmailsTask;

/**
 * Class Tasks manages the tasks queue and provides API to work with it.
 *
 * @since 1.5.9
 */
class Tasks {

	/**
	 * Group that will be assigned to all actions.
	 *
	 * @since 1.5.9
	 */
	const GROUP = 'wpforms';

	/**
	 * Perform certain things on class init.
	 *
	 * @since 1.5.9
	 */
	public function init() {

		// Register WPForms tasks.
		foreach ( $this->get_tasks() as $task ) {

			if ( ! is_subclass_of( $task, Task::class ) ) {
				continue;
			}

			new $task();
		}

		add_action( 'admin_menu', [ $this, 'admin_hide_as_menu' ], PHP_INT_MAX );

		add_action( EntryEmailsTask::ACTION, [ EntryEmailsTask::class, 'process' ] );
	}

	/**
	 * Get the list of WPForms default scheduled tasks.
	 * Tasks, that are fired under certain specific circumstances
	 * (like sending form submission email notifications)
	 * are not listed here.
	 *
	 * @since 1.5.9
	 *
	 * @return Task[] List of tasks classes.
	 */
	public function get_tasks() {

		$tasks = [
			Actions\EntryEmailsMetaCleanupTask::class,
		];

		return apply_filters( 'wpforms_tasks_get_tasks', $tasks );
	}

	/**
	 * Hide Action Scheduler admin area when not in debug mode.
	 *
	 * @since 1.5.9
	 */
	public function admin_hide_as_menu() {

		// Filter to redefine that WPForms hides Tools > Action Scheduler menu item.
		if ( apply_filters( 'wpforms_tasks_admin_hide_as_menu', ! wpforms_debug() ) ) {
			remove_submenu_page( 'tools.php', 'action-scheduler' );
		}
	}

	/**
	 * Create a new task.
	 * Used for "inline" tasks, that require additional information
	 * from the plugin runtime before they can be scheduled.
	 *
	 * Example:
	 *     wpforms()->get( 'tasks' )
	 *              ->create( 'i_am_the_dude' )
	 *              ->async()
	 *              ->params( 'The Big Lebowski', 1998 )
	 *              ->register();
	 *
	 * This `wpforms_do_something_special` action will be later processed as:
	 *     add_action( 'i_am_the_dude', 'thats_what_you_call_me' );
	 *
	 * @since 1.5.9
	 *
	 * @param string $action Action that will be used as a hook.
	 *
	 * @return \WPForms\Tasks\Task
	 */
	public function create( $action ) {

		return new Task( $action );
	}

	/**
	 * Cancel all the AS actions for a group.
	 *
	 * @since 1.5.9
	 *
	 * @param string $group Group to cancel all actions for.
	 */
	public function cancel_all( $group = '' ) {

		if ( empty( $group ) ) {
			$group = self::GROUP;
		} else {
			$group = sanitize_key( $group );
		}

		if ( class_exists( 'ActionScheduler_DBStore' ) ) {
			\ActionScheduler_DBStore::instance()->cancel_actions_by_group( $group );
		}
	}
}

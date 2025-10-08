<?php
/**
 * WP Action Network Events
 *
 * @package   Site_Functionality
 */
namespace Site_Functionality\Common\Abstracts;

use Site_Functionality\Settings;

/**
 * The Base class which can be extended by other classes to load in default methods
 *
 * @package Site_Functionality\Common\Abstracts
 * @since 1.0.0
 */
abstract class Base {

	/**
	 * The plugin settings.
	 *
	 * @var Settings
	 */
	protected Settings $settings;

	/**
	 * The data.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      array    $data
	 */
	public $data;

	/**
	 * The errors.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $errors
	 */
	protected $errors;

	/**
	 * Base constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $settings ) {
		$this->settings = $settings;
		$this->init();
	}

	/**
	 * Initialize stuff
	 *
	 * @return void
	 */
	public function init(): void {}

	/**
	 * Handle Errors
	 *
	 * @return void
	 */
	protected function handle_error( $exception ): void {
		$this->errors[] = $exception;
	}

	/**
	 * Debug log wrapper
	 *
	 * @param mixed       $message Message to log.
	 * @param string|null $prefix  Optional prefix, defaults to calling method.
	 * @param string|null $channel Optional channel. If provided, logs to
	 *                    wp-content/plugins/{plugin-name}/logs/{channel}.log.
	 * @return void
	 */
	public static function debug( $message, ?string $prefix = null, ?string $channel = 'debug' ): void {
		if ( defined( '\WP_DEBUG' ) && \WP_DEBUG ) {
			$formatted = $prefix ? "{$prefix}: {$message}" : $message;

			$log_dir = trailingslashit( SITE_FUNCTIONALITY_PATH ) . 'logs/';

			if ( ! file_exists( $log_dir ) ) {
				wp_mkdir_p( $log_dir );
			}

			$log_file = $log_dir . sanitize_file_name( "{$channel}.log" );
			$entry    = '[' . gmdate( 'Y-m-d H:i:s' ) . '] ' . $formatted . PHP_EOL;

			// Write only to the channel file, not to PHP error_log
			@file_put_contents( $log_file, $entry, FILE_APPEND | LOCK_EX );
		}
	}

	/**
	 * Set processing data
	 *
	 * @param string $prop
	 * @param mixed  $value
	 * @return void
	 */
	public function set_data( $prop, $value ): void {
		$this->data[ $prop ] = $value;
	}

	/**
	 * Get processing data
	 *
	 * @param string $prop
	 * @return array $this->data
	 */
	public function get_data( $prop ) {
		return $this->data[ $prop ];
	}
}

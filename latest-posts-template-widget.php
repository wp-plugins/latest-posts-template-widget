<?php
/**
 * Plugin Name: Latest Posts Template Widget
 * Plugin URI:
 * Description: Latest Posts Template Widget is widget that display latest entries.
 * Version: 0.2
 * Author: Takashi Kitajima
 * Author URI: http://2inc.org
 * Created: June 4, 2014
 * Modified: September 5, 2015
 * Text Domain: lptw
 * Domain Path: /languages/
 * License: GPL2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
class Latest_Posts_Template_Widget {
	const NAME = 'lptw';
	const DOMAIN = 'lptw';

	/**
	 * Constructor
	 */
	public function __construct() {
		include_once( plugin_dir_path( __FILE__ ) . 'system/widget.php' );
		// 有効化した時の処理
		register_activation_hook( __FILE__, array( __CLASS__, 'activation' ) );
		// アンインストールした時の処理
		register_uninstall_hook( __FILE__, array( __CLASS__, 'uninstall' ) );

		add_action( 'plugins_loaded', array( $this, 'plugin_loaded' ) );
		add_action( 'widgets_init', array( $this, 'widget' ) );
	}

	/**
	 * activation
	 * 有効化した時の処理
	 */
	public static function activation() {
	}

	/**
	 * uninstall
	 * アンインストールした時の処理
	 */
	public static function uninstall() {
	}

	/**
	 * plugin_loaded
	 */
	public function plugin_loaded() {
		
		load_plugin_textdomain( self::DOMAIN, false, basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * widget
	 * ウィジェットの有効化
	 */
	public function widget() {
		register_widget( 'Latest_Posts_Template_Widget_Widget' );
	}
}
$Latest_Posts_Template_Widget = new Latest_Posts_Template_Widget();

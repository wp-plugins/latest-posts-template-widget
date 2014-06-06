<?php
/**
 * Plugin Name: Latest Posts Template Widget
 * Plugin URI:
 * Description: Latest Posts Template Widget is widget that display latest entries.
 * Version: 0.1
 * Author: Takashi Kitajima
 * Author URI: http://2inc.org
 * Created: June 4, 2014
 * Modified:
 * Text Domain: lptw
 * Domain Path: /languages/
 * License: GPL2
 *
 * Copyright 2014 Takashi Kitajima (email : inc@2inc.org)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
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

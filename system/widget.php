<?php
/**
 * Name: Latest Posts Template Widget Widget
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
class Latest_Posts_Template_Widget_Widget extends WP_Widget {

	const NAME = 'lptw';
	const DOMAIN = 'lptw';

	private $defaults = array();

	/**
	 * __construct
	 */
	public function __construct() {
		parent::__construct( self::NAME, 'Latest Posts Template Widget', array(
			'classname' => 'latest_posts_template_widget',
			'description' => __( 'Latest Posts Template Widget is widget that display latest entries.', self::DOMAIN )
		) );
		$this->defaults = array(
			'title'      => __( 'Latest Posts', self::DOMAIN ),
			'number'     => 5,
			'post_types' => array( 'post' ),
			'template'   => '',
		);
	}

	/**
	 * form
	 * @param array $instance
	 */
	public function form( $instance ) {
		$instance = $this->get_options( $instance );
		?>
		<p>
			<?php
			$id = $this->get_field_id( 'title' );
			$name = $this->get_field_name( 'title' );
			?>
			<label>
				<?php esc_html_e( 'Title', self::DOMAIN ); ?>:
				<input class="widefat" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</label>
		</p>
		<p>
			<?php
			$id = $this->get_field_id( 'number' );
			$name = $this->get_field_name( 'number' );
			?>
			<label>
				<?php esc_html_e( 'Number of posts', self::DOMAIN ); ?>:
				<input id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" type="text" value="<?php echo esc_attr( $instance['number'] ); ?>" size="3" />
			</label>
		</p>
		<p>
		<?php
			$post_types = get_post_types( array(
				'public'   => true,
				'_builtin' => false
			), 'objects', 'and' );
			array_unshift( $post_types, get_post_type_object( 'post' ), get_post_type_object( 'page' ) );
			$id = $this->get_field_id( 'post_types' );
			$name = $this->get_field_name( 'post_types' ).'[]';
			?>
			<?php esc_html_e( 'Post Types', self::DOMAIN ); ?>:
			<?php foreach ( $post_types as $post_type ) : ?>
			<label style="display:block"><input type="checkbox" value="<?php echo esc_attr( $post_type->name ); ?>"<?php checked( in_array( $post_type->name, $instance['post_types'] ) ); ?> id="<?php echo esc_attr( $id . $post_type->name ); ?>" name="<?php echo esc_attr( $name ); ?>" /> <?php echo esc_html( $post_type->label ); ?></label>
			<?php endforeach; ?>
		</p>
		<div>
			<?php
			$id = $this->get_field_id( 'template' );
			$name = $this->get_field_name( 'template' );
			?>
			<label for="<?php echo esc_attr( $id ); ?>"><?php _e( 'Template', self::DOMAIN ); ?>:</label>
			<textarea id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" class="widefat" rows="8"><?php echo $instance['template']; ?></textarea>
			<p class="description">
				<?php _e( 'you can use "<a href="https://codex.wordpress.org/Function_Reference/$post" target="?blank">$post</a>" field. e.g {post_title}', self::DOMAIN ); ?><br />
				<?php _e( 'Permalink: {permalink}', self::DOMAIN ); ?><br />
				<?php _e( 'Thumbnail: {thumbnail}, {thumbnail full}', self::DOMAIN ); ?>
			</p>
		</div>
		<?php
	}

	/**
	 * update
	 * @param  array  $new_instance  Values just sent to be saved.
	 * @param  array  $old_instance  Previously saved values from database.
	 */
	public function update( $new, $old ) {
		if ( !isset( $new['post_types'] ) )
			$new['post_types'] = array();
		$instance = wp_parse_args( $new, $old );
		return $instance;
	}

	/**
	 * widget
	 * @param  array  $args      Widget arguments.
	 * @param  array  $instance  Saved values from database.
	 */
	public function widget( $args, $instance ) {
		global $post;
		$instance = $this->get_options( $instance );

		$posts_latest = get_posts( array(
			'posts_per_page' => $instance['number'],
			'post_type' => $instance['post_types']
		) );
		?>

		<?php echo $args['before_widget']; ?>
		<?php echo $args['before_title']; ?>
			<?php echo esc_html( $instance['title'] ); ?>
		<?php echo $args['after_title']; ?>
		<ul>
			<?php foreach ( $posts_latest as $post ): setup_postdata( $post ); ?>
			<li>
				<?php
				echo preg_replace_callback( '/\{(.+?)\}/',
					array( $this, '_parse_template' ),
					$instance['template']
				);
				?>
			</li>
			<?php endforeach; wp_reset_postdata(); ?>
		</ul>
		<?php echo $args['after_widget']; ?>
		<?php
	}
	public function _parse_template( $matches ) {
		global $post;
		if ( preg_match( '/^thumbnail( .+)?$/', $matches[1], $reg ) ) {
			$size = 'post-thumbnail';
			if ( !empty( $reg[1] ) ) {
				$size = trim( $reg[1] );
			}
			return get_the_post_thumbnail( get_the_ID(), $size );
		}
		switch ( $matches[1] ) {
			case 'permalink' :
				return get_permalink();
				break;
			case 'post_date' :
				return get_the_date();
				break;
		}
		if ( isset( $post->$matches[1] ) )
			return $post->$matches[1];
	}

	/**
	 * get_options
	 */
	private function get_options( $instance ) {
		$instance = wp_parse_args( ( array ) $instance, $this->defaults );
		return $instance;
	}
}
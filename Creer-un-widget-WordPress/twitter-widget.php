<?php

class Twitter_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'twitter_widget', // Base ID
			'Twitter Widget', // Name
			array( 'description' => __( 'A Twitter Widget for your sidebar', 'your_text_domain' ), ) // Args
		);
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
 	public function form( $instance ) {
 		$title = ( isset( $instance[ 'title' ] ) ) ? $instance[ 'title' ] : __( 'Twitter Widget', 'your_text_domain' );
 		$account = ( isset( $instance[ 'account' ] ) ) ? $instance[ 'account' ] : '';
		
		?>
<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<p>
	<label for="<?php echo $this->get_field_id( 'account' ); ?>"><?php _e( 'Username:' ); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id( 'account' ); ?>" name="<?php echo $this->get_field_name( 'account' ); ?>" type="text" value="<?php echo esc_attr( $account ); ?>" />
</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['account'] = strip_tags( $new_instance['account'] );

		return $instance;
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );


		$twitter_api_url = 'https://api.twitter.com/1.1/statuses/user_timeline.json?include_entities=false&include_rts=false&screen_name=' . $instance['account'] . '&count=3';
		$json_flux = file_get_contents($twitter_api_url);
		if($json_flux === false) {
			return false;
		}

		$tweets = json_decode($json_flux);
		if( is_array($tweets) && sizeof($tweets) > 0 ) {

			echo $before_widget;
			if ( ! empty( $title ) )
				echo $before_title . $title . $after_title;
			
				echo '<ul>';
				foreach ($tweets as $tweet) {
					echo '<li>' . format_tweet($tweet->text) . '</li>';
				}
				echo '</ul>';
			
			echo $after_widget;
		}
	}

}
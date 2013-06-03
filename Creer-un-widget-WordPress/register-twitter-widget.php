<?php

function register_twitter_widget() {
	register_widget('Twitter_Widget');
}
add_action('widgets_init', 'register_twitter_widget');
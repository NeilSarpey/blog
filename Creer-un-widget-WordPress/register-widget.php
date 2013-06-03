<?php

function register_my_widget() {
	register_widget('My_Widget');
}
add_action('widgets_init', 'register_my_widget');
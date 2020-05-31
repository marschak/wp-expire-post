if (!wp_next_scheduled('expire_posts')){
  wp_schedule_event(time(), 'twicedaily', 'expire_posts'); // this can be hourly, twicedaily, or daily
}

add_action('expire_posts', 'expire_posts_function');

function expire_posts_function() {
	
	$today = date('Ymd');
	$args = array(
		'post_type' => array('show'), // post types you want to check
		'posts_per_page' => -1 
	);
	$posts = get_posts($args);
	foreach($posts as $p){
		
		if ( have_rows( 'tickets_and_date' , $p->ID ) ) : 
			while ( have_rows( 'tickets_and_date' , $p->ID ) ) : the_row(); 
				
				$repeater = get_field('tickets_and_date', $p->ID, false, false); 
				$last_row = end($repeater);
				//$expiredate = $last_row['event_date']; 
				$expiredate = $last_row['field_5dc01114bd231']; 

				$expiredateformat = date("Ymd", strtotime($expiredate));
				if ($expiredateformat) {
					if($expiredateformat < $today){
						
						$postdata = array(
							'ID' => $p->ID,
							'post_status' => 'draft'
						);
						wp_update_post($postdata);
					}else{
						
					}
				}
			endwhile; 
		else : 
		endif; 
	}
}

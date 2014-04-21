<?php do_action('geodir_before_listing_listview'); //global $wp_query; echo $wp_query->request; ?>

<ul class="geodir_category_list_view clearfix">
	
	<?php if (have_posts()) :
    		
				do_action('geodir_before_listing_post_listview');
					
         while (have_posts()) : the_post(); global $post,$wpdb,$listing_width,$preview;  ?> 
            
					<li id="post-<?php echo $post->ID;?>" class="clearfix" <?php if($listing_width) echo "style='width:{$listing_width}%;'"; // Width for widget listing ?> >
							
			<div class="geodir-post-img"> 
			<?php if($fimage = geodir_show_featured_image($post->ID, 'list-thumb', true, false, $post->featured_image)){ ?>
							
									<a  href="<?php the_permalink(); ?>">
											<?php  echo $fimage;?>
									</a>
									
				<?php if($post->is_featured){?>
									<a><span class="geodir_featured_img">&nbsp;</span></a>
									<?php } ?>
									
									<?php if(round(abs(strtotime($post->post_date)-strtotime(date('Y-m-d')))/86400)<30){?>                    <span class="geodir_new_listing"><?php _e('new',GEODIRECTORY_TEXTDOMAIN); ?></span>
									<?php } ?>
									
							
			<?php }  ?>
							
							</div>
						 
						 <div class="geodir-content"> 
						 				
									<?php do_action('geodir_before_listing_post_title', 'listview', $post ); ?>
										                          
									<h3>
											<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
													
													<?php the_title(); ?>
											
											</a>	
								</h3>
									
									<?php do_action('geodir_after_listing_post_title', 'listview', $post ); ?>
								
									<?php /// Print Distance
				if(isset($_REQUEST['sgeo_lat']) && $_REQUEST['sgeo_lat']!=''){
				
					$startPoint = array( 'latitude'	=> $_REQUEST['sgeo_lat'], 'longitude' => $_REQUEST['sgeo_lon']);	
					
					$endLat = geodir_get_post_meta($post->ID,'post_latitude',true);
											$endLon = geodir_get_post_meta($post->ID,'post_longitude',true);
											$endPoint = array( 'latitude'	=> $endLat, 'longitude'	=> $endLon);
											$uom = get_option('geodir_search_dist_1');
											$distance = geodir_calculateDistanceFromLatLong ($startPoint,$endPoint,$uom);?>
										 <h3>
					<?php
					
					 if (round((int)$distance,2) == 0){
												$uom = get_option('geodir_search_dist_2');
												$distance = geodir_calculateDistanceFromLatLong ($startPoint,$endPoint,$uom);
												echo round($distance).' '.$uom.'<br />';
											}else{
												echo round($distance,2).' '.$uom.'<br />';
										}
					?>
											</h3>
									<?php } ?>
									
									<span class="geodir-address">
					<?php echo ucfirst($post->post_address);?>
									</span>
									
								 <?php do_action('geodir_before_listing_post_excerpt', $post ); ?>
								 <?php echo geodir_show_listing_info('listing');?>       
				<p><?php the_excerpt(); ?></p>
									
									<?php do_action('geodir_after_listing_post_excerpt', $post ); ?>
							</div><!-- gd-content ends here-->
							<div class="geodir-addinfo clearfix">
								 
								 <?php 
					$comment_count = $post->rating_count; 
					$post_ratings = $post->overall_rating;
					if($post_ratings != 0 && !$preview){
						 if($comment_count > 0)
				$post_avgratings = ($post_ratings / $comment_count);
			else
				$post_avgratings = $post_ratings;
						do_action('geodir_before_review_rating_stars_on_listview' , $post_avgratings , $post->ID) ;
						echo geodir_get_rating_stars($post_avgratings,$post->ID);
						do_action('geodir_after_review_rating_stars_on_listview' , $post_avgratings , $post->ID);
					}
				?>
								 
								 <a href="<?php comments_link(); ?>" class="geodir-pcomments">
						<?php comments_number( __('no review',GEODIRECTORY_TEXTDOMAIN), __('1 review',GEODIRECTORY_TEXTDOMAIN), __('% reviews',GEODIRECTORY_TEXTDOMAIN) ); ?>
								 </a>
									
									
								 
								 <?php  geodir_favourite_html($post->post_author,$post->ID); ?>
								 
								 <?php
				 global $wp_query ;
					$show_pin_point = $wp_query->is_main_query();
				 if( !empty( $show_pin_point) && is_active_widget( false, "","geodir_map_v3_listing_map" ) ){ 
				 
						if($json_info = json_decode($post->marker_json))
							$marker_icon = $json_info->icon;
				 ?>
								 <span class="geodir-pinpoint" style=" background:url('<?php if(isset($marker_icon)){ echo $marker_icon;}?>') no-repeat scroll left center transparent; background-size:auto 60%;"><a href="javascript:void(0)" onclick="openMarker('listing_map_canvas' ,'<?php echo $post->ID; ?>')" onmouseover="animate_marker('listing_map_canvas' ,'<?php echo $post->ID; ?>')" onmouseout="stop_marker_animation('listing_map_canvas' ,'<?php echo $post->ID; ?>')" ><?php _e('Pinpoint',GEODIRECTORY_TEXTDOMAIN);?></a></span>
								 <?php } ?>
								 
								 <?php if( $post->post_author == get_current_user_id() ){ ?>
										<?php 
													$addplacelink = get_permalink( get_option('geodir_add_listing_page') );
													$editlink = geodir_getlink($addplacelink,array('pid'=>$post->ID),false);
													$upgradelink = geodir_getlink($editlink,array('upgrade'=>'1'),false);
													
													$ajaxlink = geodir_get_ajax_url();
													$deletelink = geodir_getlink($ajaxlink,array('geodir_ajax'=>'add_listing','ajax_action'=>'delete','pid'=>$post->ID),false);
													
											?>
											
											<span class="geodir-authorlink clearfix">
											
											<?php if(isset($_REQUEST['geodir_dashbord']) && $_REQUEST['geodir_dashbord']){
														
														do_action('geodir_before_edit_post_link_on_listing');
											?>
											
											<a href="<?php echo $editlink;?>" class="geodir-edit" title="<?php _e('Edit Listing',GEODIRECTORY_TEXTDOMAIN);?>"><?php _e('edit',GEODIRECTORY_TEXTDOMAIN);?></a> 
											<a href="<?php echo $deletelink;?>" class="geodir-delete" title="<?php _e('Delete Listing',GEODIRECTORY_TEXTDOMAIN);?>"><?php _e('delete',GEODIRECTORY_TEXTDOMAIN);?></a> 
											<?php 
													do_action('geodir_after_edit_post_link_on_listing');
											} ?>
											</span>
									 
								 <?php }?>
								 
							</div><!-- geodir-addinfo ends here-->
					</li>
    		
				<?php 
				endwhile; 
		
				do_action('geodir_after_listing_post_listview');
		
	else:
		
		if(isset($_REQUEST['list']) && $_REQUEST['list'] == 'favourite')
			echo '<li class="no-listing">'.__('No favourite listings found which match your selection.',GEODIRECTORY_TEXTDOMAIN).'</li>'; 
		else
			echo '<li class="no-listing">'.__('No listings found which match your selection.',GEODIRECTORY_TEXTDOMAIN).'</li>'; 
			 
	endif;

	?>
</ul>  <!-- geodir_category_list_view ends here-->

<div class="clear"></div>
<?php do_action('geodir_after_listing_listview');   

//Additional Resources section for each post
function additional_resources_filter_section_specific_post() {
    $no_img = get_field('slt_no_img','option');
    $link_text = get_field('word_learn_more', 'option');
?>
    <div class="slt-filter-block">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="tab">
                        <?php
                            $terms = [
                                [__('White Papers', THEME_TEXTDOMAIN), 'white_paper','additional_resources_white_papers'],
                                [__('E-books', THEME_TEXTDOMAIN), 'ebook','additional_resources_ebooks'],
                                [__('Recorded Webinars', THEME_TEXTDOMAIN), 'webinar','additional_resources_webinars'],
                                [__('Upcoming Events', THEME_TEXTDOMAIN), 'event','additional_resources_upcoming_events'],
                                [__('Download', THEME_TEXTDOMAIN), 'free_download','additional_resources_download']
                            ];

                            $i = 0;
                            foreach ($terms as $_terms):
                            $i++;
                            $homeSltID = "homeSltID".$i;
                        ?>
                        <button class="tablinks" id="<?php echo $homeSltID; ?>" onclick="homeSltFilter(event, '<?php echo $_terms[1]; ?>')"><?php echo $_terms[0]; ?></button>
                        <?php                            
                            endforeach;
                            wp_reset_query();
                        ?>

                    </div>
                </div>
            </div>

            <?php
                $i = 0;
                foreach ($terms as $_terms):
            ?>
            <div id="<?php echo $_terms[1]; ?>" class="tabcontent slt-list-block-sub">
                <div class="row">
                    <div class="owl-carousel">
                        <?php
                            $get_relation_post = get_field($_terms[2]);

                            $list_block_type = '';
                            $list_type = '';

                            if ($_terms[1] == "white_paper") {

                                $get_field_name = 'download_pdf';
                                $link_text = __('Download', THEME_TEXTDOMAIN);
                                $list_block_type = 'white-paper-list-block register-block';
                                $list_type = 'download-register list';

                            } else if ($_terms[1] == "ebook") {

                                $get_field_name = 'download_pdf';
                                $link_text = __('Download', THEME_TEXTDOMAIN);
                                $list_block_type = 'ebook-list-block register-block';
                                $list_type = 'download-register list';

                            } else if ($_terms[1] == "webinar") {

                                $get_field_name = 'webinar_video_url';
                                $link_text = get_field('word_watch_now', 'option');
                                $list_duration_text = get_field('word_duration', 'option');                                    
                                $list_type = 'popup-video';                                    

                            } else if ($_terms[1] == "event") {

                                $link_text = __('Register', THEME_TEXTDOMAIN);
                                $list_type = 'register-event';
                                
                            } else if ($_terms[1] == "free_download") {
                                
                                $get_field_name = 'download_pdf';
                                $link_text = __('Download', THEME_TEXTDOMAIN);
                                $list_block_type = 'free-download-list-block register-block';
                                $list_type = 'download-register list';

                            }

                            if ($get_relation_post) {

                                foreach( $get_relation_post as $relation_post_id  ):

                                    $list_link = '';
                                    $pdf_link = '';
                                    $meta_values = ''; //meta value for upcoming event
                                    $list_duration = '';
                                    $list_tag = $_terms[0];

                                    $list_short_desc = mb_strimwidth(get_the_excerpt($relation_post_id), 0, 240, ' [...]');
                                    if (!$list_short_desc) $list_short_desc = '';

                                    if ($get_field_name) {
                                        if ($get_field_name == 'download_pdf') {
                                            if (get_field('download_redirect', $relation_post_id)) $list_link = get_field('download_redirect', $relation_post_id);
                                        } else {
                                            $list_link = get_field($get_field_name, $relation_post_id);
                                        }
                                    }

                                    if ($_terms[1] == "webinar") {

                                        $list_short_desc = '';
                                        $list_duration = get_field('webinar_duration', $relation_post_id);

                                    } else if ($_terms[1] == "event") {

                                        $meta_values = get_post_meta( $relation_post_id );
                                        if ($meta_values) {
                                            $meta_salesforce_id = $meta_values['salesforce_id'][0];
                                            $meta_date = $meta_values['date'][0];
                                            $meta_date = date('jS F, Y', strtotime($meta_date));
                                            $meta_city = $meta_values['ville'][0];
                                            $meta_language = $meta_values['language'][0];
                                            $meta_time = $meta_values['heure'][0];
                                            $list_link = $meta_values['register_url'][0];
                                        }

                                        //Get event icon
                                        $post_icon = get_field('event_icon', $relation_post_id);
                                        if ($post_icon) $post_icon = wp_get_attachment_image_url($post_icon, 'thumbnail');

                                    } else {

                                        $pdf_link = $list_link;
                                        $list_link = ''; //hide direct link PDF file

                                    }

                                    if (!$list_link) $list_link = '#';
                        ?>
                                    <div class="list-block <?php echo $list_block_type;?>">
                                        <a href="<?php echo $list_link; ?>" target="_blank" class="<?php echo $list_type;?>">
                                            <?php if ($_terms[1] != "event"): ?>
                                                <figure class="thumbnail">
                                                    <?php
                                                        if ( get_post_thumbnail_id($relation_post_id) ) {
                                                            echo wp_get_attachment_image( get_post_thumbnail_id($relation_post_id), 'large');
                                                        } else {
                                                            echo '<img src="'.$no_img.'" />';
                                                        }
                                                    ?>
                                                </figure>
                                            <?php endif; ?>
                                            <div class="desc event-item">
                                                
                                                <?php if ($_terms[1] == "event") echo '<div class="event-header">';?>
                                                <?php if ($meta_date && $_terms[1] != "free_download") echo '<div class="event-date">' . $meta_date . '</div>';?>
                                                <div class="top-block">
                                                    <p class="tag"><?php echo $list_tag; ?></p>
                                                </div>
                                                <?php if ($_terms[1] == "event" && $post_icon) echo '<div class="event-icon"><img src="' . $post_icon . '" loading="lazy" alt="" /></div>';?>
                                                <?php if ($_terms[1] == "event") echo '</div>';?>

                                                <h4 class="title"><?php echo get_the_title($relation_post_id); ?></h4>
                                                <?php if ($list_short_desc) echo '<div class="short-desc">' . $list_short_desc . '</div>'; ?>
                                                <?php if ($list_duration) echo '<div class="duration">' . $list_duration_text . ' ' . $list_duration . '</div>';?>
                                                <?php 
                                                    if ($meta_values) {
                                                        echo '<ul class="meta-list">';
                                                        if ($meta_city) echo '<li class="location">' . $meta_city . '</li>';
                                                        if ($meta_language) echo '<li class="language">' . $meta_language . '</li>';
                                                        if ($meta_time) echo '<li class="time">' . $meta_time . '</li>';
                                                        echo '</ul>';
                                                    }
                                                ?>
                                                <div class="link"><?php echo $link_text; ?></div>
                                            </div>
                                            <?php if ($pdf_link) echo '<span class="pdf-link" href="'. $pdf_link .'" style="display:none;"></span>'; ?>
                                        </a>
                                    </div>
                        <?php
                                endforeach;

                            } else {

                                $args = array(
                                    'post_type' => $_terms[1],
                                    'orderby' => 'date',
                                    'order' => 'DESC',
                                    'posts_per_page' => 20, //limit for faster query
                                );

                                $query = new WP_Query( $args );

                                if ($query->have_posts()) {

                                    while ($query->have_posts()) {
                                    $query->the_post();
                                    $list_link = '';
                                    $pdf_link = '';
                                    $meta_values = ''; //meta value for upcoming event
                                    $list_duration = '';
                                    $list_tag = $_terms[0];

                                    $list_short_desc = mb_strimwidth(get_the_excerpt(), 0, 240, ' [...]');
                                    if (!$list_short_desc) $list_short_desc = '';

                                    if ($get_field_name) {
                                        if ($get_field_name == 'download_pdf') {
                                            if (get_field('download_redirect')) $list_link = get_field('download_redirect');
                                        } else {
                                            $list_link = get_field($get_field_name);
                                        }
                                    }

                                    if ($_terms[1] == "webinar") {

                                        $list_short_desc = '';
                                        $list_duration = get_field('webinar_duration');

                                    } else if ($_terms[1] == "event") {

                                        $meta_values = get_post_meta( get_the_ID() );
                                        if ($meta_values) {
                                            $meta_salesforce_id = $meta_values['salesforce_id'][0];
                                            $meta_date = $meta_values['date'][0];
                                            $meta_date = date('jS F, Y', strtotime($meta_date));
                                            $meta_city = $meta_values['ville'][0];
                                            $meta_language = $meta_values['language'][0];
                                            $meta_time = $meta_values['heure'][0];
                                            $list_link = $meta_values['register_url'][0];
                                        }

                                        //Get event icon
                                        $post_icon = get_field('event_icon', $post_id);
                                        if ($post_icon) $post_icon = wp_get_attachment_image_url($post_icon, 'thumbnail');

                                    } else {

                                        $pdf_link = $list_link;
                                        $list_link = ''; //hide direct link PDF file

                                    }

                                    if (!$list_link) $list_link = '#';
                        ?>
                                        <div class="list-block <?php echo $list_block_type;?>">
                                            <a href="<?php echo $list_link; ?>" target="_blank" class="<?php echo $list_type;?>">
                                                <?php if ($_terms[1] != "event"): ?>
                                                    <figure class="thumbnail">
                                                        <?php
                                                            if ( get_post_thumbnail_id() ) {
                                                                echo wp_get_attachment_image( get_post_thumbnail_id(), 'large');
                                                            } else {
                                                                echo '<img src="'.$no_img.'" />';
                                                            }
                                                        ?>
                                                    </figure>
                                                <?php endif; ?>
                                                <div class="desc event-item">
                                                    
                                                    <?php if ($_terms[1] == "event") echo '<div class="event-header">';?>
                                                    <?php if ($meta_date && $_terms[1] != "free_download") echo '<div class="event-date">' . $meta_date . '</div>';?>
                                                    <div class="top-block">
                                                        <p class="tag"><?php echo $list_tag; ?></p>
                                                    </div>
                                                    <?php if ($_terms[1] == "event" && $post_icon) echo '<div class="event-icon"><img src="' . $post_icon . '" loading="lazy" alt="" /></div>';?>
                                                    <?php if ($_terms[1] == "event") echo '</div>';?>

                                                    <h4 class="title"><?php the_title(); ?></h4>
                                                    <?php if ($list_short_desc) echo '<div class="short-desc">' . $list_short_desc . '</div>'; ?>
                                                    <?php if ($list_duration) echo '<div class="duration">' . $list_duration_text . ' ' . $list_duration . '</div>';?>
                                                    <?php 
                                                        if ($meta_values) {
                                                            echo '<ul class="meta-list">';
                                                            if ($meta_city) echo '<li class="location">' . $meta_city . '</li>';
                                                            if ($meta_language) echo '<li class="language">' . $meta_language . '</li>';
                                                            if ($meta_time) echo '<li class="time">' . $meta_time . '</li>';
                                                            echo '</ul>';
                                                        }
                                                    ?>
                                                    <div class="link"><?php echo $link_text; ?></div>
                                                </div>
                                                <?php if ($pdf_link) echo '<span class="pdf-link" href="'. $pdf_link .'" style="display:none;"></span>'; ?>
                                            </a>
                                        </div>
                        <?php
                                    } //end while
                                }

                            } //end if $get_relation_post

                            wp_reset_postdata();
                            
                        ?>
                    </div>
                </div>
            </div>
            <?php endforeach; wp_reset_query(); ?>
        </div>
    </div>

    <?php
}
add_shortcode( 'additional_resources_filter_section_specific_post_shortcode','additional_resources_filter_section_specific_post' );

//Get Date, country and provience of the client after submitting the form


function custom_post_date_callback(){
    return date('F jS, Y');
}

add_shortcode('custom_post_date', 'custom_post_date_callback');




function custom_location_callback(){
	$user_ip=  getenv('REMOTE_ADDR');
    $transient_name = 'ip_api_'.$user_ip;
    if ( false === ( $q = get_transient( $transient_name ) ) ) {
        $q = file_get_contents("http://ip-api.com/php/$user_ip");
        set_transient( $transient_name, $q, 1 * YEAR_IN_SECONDS );
    }
    $geo= unserialize($q);
    $country= $geo["country"];
    return $country;
}
							   

add_shortcode('custom_location', 'custom_location_callback');

function custom_province_callback(){
	
	$user_ip=  getenv('REMOTE_ADDR');
    $transient_name = 'ip_api_'.$user_ip;
    if ( false === ( $q = get_transient( $transient_name ) ) ) {
        $q = file_get_contents("http://ip-api.com/php/$user_ip");
        set_transient( $transient_name, $q, 1 * YEAR_IN_SECONDS );
    }
    $geo= unserialize($q);
    $province= $geo['regionName'];
    return $province;
}
    
	


add_shortcode('custom_province', 'custom_province_callback');


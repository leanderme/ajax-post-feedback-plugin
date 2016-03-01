<?php
/** 
 * Main functions class
 * 
 * @author Leander Melms <leandermelms.me> 
 */ 

class POST_LOCK {

 function __construct() 
    {     
        add_action('wp_enqueue_scripts', array(&$this, 'enqueue_scripts'));
        add_filter('the_excerpt', array(&$this, 'article_lock_content'));
        add_action('publish_post', array(&$this, 'setup_likes'));
        add_action('wp_ajax_pf-likes', array(&$this, 'ajax_callback'));
        add_action('wp_ajax_nopriv_pf-likes', array(&$this, 'ajax_callback'));
        add_action('wp_ajax_pf_ajax_headwords', array(&$this, 'ajax_headwords'));
        add_action('wp_ajax_nopriv_pf_ajax_headwords', array(&$this, 'ajax_headwords'));
        add_filter('the_content', array(&$this,'article_lock_content'));
        add_action('add_meta_boxes', array(&$this,'article_lock_metabox_add'));
        add_action('save_post', array(&$this,'article_lock_save'));
     }

    public function enqueue_scripts()
     {    

/*          wp_deregister_script('jquery');
          wp_deregister_script('jquery-ui-core');
          wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"), false, 'latest', false);
          wp_register_script('jquery-ui', ("//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"), false, 'latest', false);*/
          wp_enqueue_script('jquery');
          wp_enqueue_script('jquery-ui-core');
          wp_enqueue_style( 'pf-likes', PFURL . '/css/pf-likes.css' );
          wp_enqueue_style( 'icon-font', PFURL . '/css/font-awesome.css' );
          wp_enqueue_style( 'options-css', PFURL . '/css/options.css' );
          wp_enqueue_script('mod', PFURL . "/js/modernizr.custom.js", array('jquery'),'2.6.2',true);  
          wp_enqueue_script('mod-icons', PFURL . "/js/icons.js", array('jquery'),'1.0',true);  
          wp_enqueue_script('jquery-easing', PFURL . "/js/jquery.easing.js", array('jquery'),'1.3',true);  
          wp_enqueue_script('jquery-tipsy-js', PFURL . "/js/jquery.tipsy.js", array('jquery'),'1.0',true);  
          wp_enqueue_script( 'pf-likes', PFURL .  '/js/pf-likes.js', array('jquery'),'1.0',true );
          wp_enqueue_script( 'pf-unlock', PFURL .  '/js/pf-unlock.js', array('jquery'),'1.0',true );
          wp_localize_script('pf-likes', 'pf', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
          ));
          wp_localize_script( 'pf-likes', 'pf_likes', array('ajaxurl' => admin_url('admin-ajax.php')) );
     }


    public function setup_likes( $post_id ) {
          if(!is_numeric($post_id)) return;
          add_post_meta($post_id, '_pf_pos', '0', true);
          add_post_meta($post_id, '_pf_neu', '0', true);
          add_post_meta($post_id, '_pf_neg', '0', true);
     }

    public function output_share_html($use_permalink = false){
        
        $options = pf_get_settings( PFPATH .'/admin/settings/pf-settings.php' );
        $count   = intval(0);
        if( !isset($options['pfsettings_pf-social_pf_social_html']) ) $options['pfsettings_pf-social_pf_social_html'] = 'Hey, like this post? Why not share it with a buddy?';
        if($options['pfsettings_pf-social_pf_social_services_twitter'])  $count++;
        if($options['pfsettings_pf-social_pf_social_services_facebook']) $count++;
        if($options['pfsettings_pf-social_pf_social_services_gplus'])    $count++;

        #$col = floor(12/$count);

        $permalink = get_permalink();
        $title     = get_the_title();
        $excerpt   = $options['pfsettings_pf-social_pf_fb_summary'];
        
        $output = '<ul class="hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a">';
        if($options['pfsettings_pf-social_pf_social_html'])               $output .= '<p class="pf-text">'. $options['pfsettings_pf-social_pf_social_html'] .'</p>';
        if($options['pfsettings_pf-social_pf_social_services_twitter']):  $output .= '<li><a class="hi-icon pf-twitter" href="http://twitter.com/share?url='.$permalink.'&text='. $title .  " (" . $permalink . ")";
        if($options['pfsettings_pf-social_pf_twitter_u'])                 $output .= ' via @' . $options['pfsettings_pf-social_pf_twitter_u'];
                                                                          $output .= '" target="_blank"><i class="icon-twitter"></i><span></span></a></li>';
        endif;
        if($options['pfsettings_pf-social_pf_social_services_facebook'])  $output .= "<li><a class='hi-icon pf-fb' href='http://www.facebook.com/sharer/sharer.php?s=100&p[url]=".$permalink."&p[images][0]=&p[title]=".get_the_title()."&p[summary]=".$excerpt."' target='_blank'  class='btn post-share-btn facebook-btn'><i class='icon-facebook'></i><span></span></a></li>";
        if($options['pfsettings_pf-social_pf_social_services_gplus'])     $output .= '<li><a class="hi-icon pf-google-plus" href="https://plus.google.com/share?url='.$permalink.'" target="_bank"><i class="icon-google-plus"></i></a></li>';
        $output .= '</ul>';
        $output .= '<div class="clear clearfix"></div>';
        
        return $output;
      }
     

     public function ajax_headwords(){
      global $post;

      $id   = $_POST['id'];
      $p_id = $_POST['post_id'];
     
      $data = get_post_meta($p_id, 'pf_headwords', true);
      $new = array();

      $count = count($data);

        for ( $i = 0; $i < $count; $i++ ) {
                if($data[$i]['name'] == $id){
                  $data[$i]['rating']++;
              }
        }

        update_post_meta($p_id,'pf_headwords',$data);

      die();
     }
     
     public function ajax_callback($post_id,$case = 'like')  {

          $options = array();
          if( !isset($options['add_to_posts']) ) $options['add_to_posts'] = '0';
          if( !isset($options['add_to_pages']) ) $options['add_to_pages'] = '0';
          if( !isset($options['add_to_other']) ) $options['add_to_other'] = '0';
          if( !isset($options['zero_postfix']) ) $options['zero_postfix'] = '';
          if( !isset($options['one_postfix']) ) $options['one_postfix'] = '';
          if( !isset($options['more_postfix']) ) $options['more_postfix'] = '';

          if( isset($_POST['likes_id']) ) {
               $case = $_POST['case'];
               $post_id = str_replace('pf-', '', $_POST['likes_id']);

               echo $this->like_this($post_id, $options['zero_postfix'], $options['one_postfix'], $options['more_postfix'], 'update', $case);
          } else {
               $case = $_POST['case'];
               echo $case;
               $post_id = str_replace('pf-likes-', '', $_POST['post_id']);
               echo $this->like_this($post_id, $options['zero_postfix'], $options['one_postfix'], $options['more_postfix'], 'get', 'dislike');
          }
          
          exit;
     }

     public function like_this($post_id, $zero_postfix = false, $one_postfix = false, $more_postfix = false, $action = 'get', $case = 'like') {
          global $wpdb;
          if(!is_numeric($post_id)) return;
          $zero_postfix = strip_tags($zero_postfix);
          $one_postfix = strip_tags($one_postfix);
          $more_postfix = strip_tags($more_postfix);        
          
          switch($action) {
          
               case 'get':
                if($case == 'like'):
                    $likes = get_post_meta($post_id, '_pf_pos', true);
                    if( !$likes ){
                         $likes = 0;
                         add_post_meta($post_id, '_pf_pos', $likes, true);
                    }
                    
                    if( $likes == 0 ) { $postfix = $zero_postfix; }
                    elseif( $likes == 1 ) { $postfix = $one_postfix; }
                    else { $postfix = $more_postfix; }
                    
                    return '<span class="pf-likes-count pf-invisible">'. $likes .'</span>';
                elseif($case == 'neutral'):
                    $neutrals = get_post_meta($post_id, '_pf_neu', true);
                    if( !$neutrals ){
                         $neutrals = 0;
                         add_post_meta($post_id, '_pf_neu', $neutrals, true);
                    }
                    
                    if( $neutrals == 0 ) { $postfix = $zero_postfix; }
                    elseif( $neutrals == 1 ) { $postfix = $one_postfix; }
                    else { $postfix = $more_postfix; }
                    
                    return '<span class="pf-neutrals-count pf-invisible">'. $neutrals .'</span>';
                elseif($case == 'dislike'):
                    $dislikes = get_post_meta($post_id, '_pf_neg', true);
                    if( !$dislikes ){
                         $dislikes = 0;
                         add_post_meta($post_id, '_pf_neg', $dislikes, true);
                    }
                    
                    if( $dislikes == 0 ) { $postfix = $zero_postfix; }
                    elseif( $dislikes == 1 ) { $postfix = $one_postfix; }
                    else { $postfix = $more_postfix; }
                    
                    return '<span class="pf-dislikes-count pf-invisible">'. $dislikes .'</span>';
                endif;    

               break;
                    
               case 'update':
                    if($case == 'like'):
                         $likes = get_post_meta($post_id, '_pf_pos', true);
                         if( isset($_COOKIE['pf_likes'. $post_id]) ) return $likes;
                         
                         $likes++;
                         update_post_meta($post_id, '_pf_pos', $likes);
                         setcookie('pf_likes'. $post_id, $post_id, time()*20, '/');
                         
                         if( $likes == 0 ) { $postfix = $zero_postfix; }
                         elseif( $likes == 1 ) { $postfix = $one_postfix; }
                         else { $postfix = $more_postfix; }
                         
                         return '<span class="pf-likes-count pf-invisible">'. $likes .'</span>';
                    endif;
                    if($case == 'neutral'):
                         $neutrals = get_post_meta($post_id, '_pf_neu', true);
                         if( isset($_COOKIE['pf_neu_'. $post_id]) ) return $neutrals;
                         $neutrals++;
                         update_post_meta($post_id, '_pf_neu', $neutrals);
                         setcookie('pf_neu_'. $post_id, $post_id, time()*20, '/');
                         return '<span class="pf-neutrals-count pf-invisible">'. $neutrals . '</span>';
                    endif;
                    if($case == 'dislike'):
                         $dislikes = get_post_meta($post_id, '_pf_neg', true);
                         if( isset($_COOKIE['pf_neg_'. $post_id]) ) return $dislikes;
                         
                         $dislikes++;
                         update_post_meta($post_id, '_pf_neg', $dislikes);
                         setcookie('pf_neg_'. $post_id, $post_id, time()*20, '/');
                         return '<span class="pf-dislikes-count pf-invisible">'. $dislikes . '</span>';
                    endif;
                    //   if($case == 'link-like'):
                    //     $post_id = $_POST['postid'];
                    //     $post_id = mysql_real_escape_string($post_id);
                    //     $wpdb->query("UPDATE wp_postmeta SET meta_value = meta_value+1 WHERE post_id = '$post_id' AND meta_key = '_link_likes'");
                    //   endif;
               break;

          
          }
     }
     
     public function shortcode( $atts ) {
          extract( shortcode_atts( array(
          ), $atts ) );
          
          return $this->pf_show_votes();
     }

     public function pf_show_votes() {
        global $post;

         $likes     = get_post_meta($post->ID, '_pf_pos', true);
         $neutrals  = get_post_meta($post->ID, '_pf_neu', true);
         $negs      = get_post_meta($post->ID, '_pf_neg', true);

         $output  = '<div class="pf-spacing"></div>';
         $output .= '<ul class="pf-rating-info pf-icons">';
         $output .= '<li class="like">' . '<div class="pf-like-small pf-count">' . $likes . '</div>' . '</li>';
         $output .= '<li class="neutral">' . '<div class="pf-neutral-small pf-count">' . $neutrals . '</div>' . '</li>';
         $output .= '<li class="dislike">' . '<div class="pf-dislike-small pf-count">' . $negs . '</div>' . '</li>';
         $output .= '</ul>';
         $output .= '<div class="clear"></div>';
         $output .= '<div class="pf-spacing"></div>';
         $output .= '<br>';
         return $output;
     }
     

      public static function pf_show_positives() {
        global $post;

         $likes   = get_post_meta($post->ID, '_pf_pos', true);

         return $likes;
     }

    public static function pf_show_neutrals() {
        global $post;

         $neutrals = get_post_meta($post->ID, '_pf_neu', true);

         return $neutrals;
     }

    public static function pf_show_negatives() {
        global $post;

         $dislikes = get_post_meta($post->ID, '_pf_neg', true);

         return $dislikes;
     }


     public function do_likes() {
        global $post;

        $likes = get_post_meta($post->ID, '_pf_pos', true);
        $options = array();
          if( !isset($options['zero_postfix']) ) $options['zero_postfix'] = '';
          if( !isset($options['one_postfix']) ) $options['one_postfix'] = '';
          if( !isset($options['more_postfix']) ) $options['more_postfix'] = '';
          
          $output = $this->like_this($post->ID, $options['zero_postfix'], $options['one_postfix'], $options['more_postfix'],'get','like');
  
          $class = 'pf-likes';
          $title = __('Like this', 'pf');
          if( isset($_COOKIE['pf_likes'. $post->ID]) ){
               $class = 'pf-likes pf-active';
               $title = __('You already like this', 'pf');
          }
          
          return '<li><a href="#" rel="tipsy" original-title="'.$likes.'"  class="'. $class .'" id="pf-'. $post->ID .'" title="'. $likes .'">'. $output .'</a></li>';
     }

     public function do_neutrals() {
        global $post;
        $neutrals = get_post_meta($post->ID, '_pf_neu', true);
        $options = array();
          if( !isset($options['zero_postfix']) ) $options['zero_postfix'] = '';
          if( !isset($options['one_postfix']) ) $options['one_postfix'] = '';
          if( !isset($options['more_postfix']) ) $options['more_postfix'] = '';
          
          $output = $this->like_this($post->ID, $options['zero_postfix'], $options['one_postfix'], $options['more_postfix'],'get','neutral');
  
          $class = 'pf-neutrals';
          $title = __('Like this', 'pf');
          if( isset($_COOKIE['pf_neutrals_'. $post->ID]) ){
               $class = 'pf-neutrals pf-active';
               $title = __('You already voted this', 'pf');
          }
          
          return '<li><a href="#" rel="tipsy" original-title="'.$neutrals.'"  class="'. $class .'" id="pf-'. $post->ID .'" title="'. $neutrals .'">'. $output .'</a></li>';
     }

     public function do_dislikes() {
        global $post;
        $negs = get_post_meta($post->ID, '_pf_neg', true);
        $options = array();
          if( !isset($options['zero_postfix']) ) $options['zero_postfix'] = '';
          if( !isset($options['one_postfix']) ) $options['one_postfix'] = '';
          if( !isset($options['more_postfix']) ) $options['more_postfix'] = '';
          
          $output = $this->like_this($post->ID, $options['zero_postfix'], $options['one_postfix'], $options['more_postfix'],'get','dislike');
  
          $class = 'pf-dislikes';
          $title = __('Like this', 'pf');
          if( isset($_COOKIE['pf_dislikes_'. $post->ID]) ){
               $class = 'pf-dislikes pf-active';
               $title = __('You already dislike this', 'pf');
          }
          
          return '<li><a href="#" rel="tipsy"  original-title="'.$negs.'" class="'. $class .'" id="pf-'. $post->ID .'" title="'. $negs .'">'. $output .'</a></li>';
     }


    public function article_lock_content($content) {
    
        global $post;
        $options             = pf_get_settings( PFPATH .'/admin/settings/pf-settings.php' );
        $article_lock_data   = get_post_meta($post->ID, 'article_lock_data', true);  
        $voted               = false;
        
        if(is_array($article_lock_data)):
          if($article_lock_data["on"] == 'off'){
            return $content;
          }
        endif;

        if(!$options['pfsettings_setup_pf_enable']) {
            return $content;
        }

        if(is_page_template()) return $content;
        if(get_post_type() == 'slide') return $content;
        if(is_page() || is_front_page() ||  is_home() || is_category() || is_tag() || is_author() || is_date() || is_search()) return $content;


        if(is_array($article_lock_data)):     
          $all_set = true;
          foreach($article_lock_data as $key => $value) {
              if(!isset($value)) {
                  $all_set = false;
              }
          }
        endif;

        if($article_lock_data['length'] == '') $article_lock_data['length'] = $options['pfsettings_setup_pf_preview_length'];

        $already_liked    = isset($_COOKIE['pf_likes'. $post->ID]);
        $already_neutral  = isset($_COOKIE['pf_neu_'. $post->ID]);
        $already_disliked = isset($_COOKIE['pf_neg_'. $post->ID]); 

        if(!empty($already_liked) || !empty($already_neutral) || !empty($already_disliked) ){
            $voted = true;
        }


        if($all_set && $options['pfsettings_setup_pf_enable'] && $voted !== true) {

            $img_count  = substr_count($content, '<img');
            $word_count = str_word_count($content, 0);
            $code_count = substr_count($content, '<pre');
            $img_dot    = '';
            $code_dot   = '';
            $both_dot   = '';
            $step_one   = '';
            $step_two   = '';
            $step_three = '';
            $progress   = '<div id="progressbar" data-width="">';
            $progress  .= '<span style="width: 50%"></span>';
            $progress  .= '</div>';

            $url = PFURL.'/images/lock.png';
            $article = '';
            $article .= $progress;
            $article .= '<br /><div id="article_lock">';

            $article .= '<div class="steps-wizard pf-invisible">';
            $article .= '<div class="step1 step-active">1</div>';
            $article .= '<div class="step2">2</div>';
            $article .= '<div class="step3">3</div>';
            $article .= '</div>';
            $article .= '<a href="#" class="navigation pf-invisible" style="display:none !important;" id="nav-next" data-nav="next">Next</a>';
            $article .= '<div class="steps-content">';

            $step_one .= '<section class="content-active">';
            $step_one .= '<div class="pf-intro-text">';
            $step_one .= "This is a <strong>preview</strong>.";
            $full_article_contains = " The full article contains";

           if($img_count > 0 && $options['pfsettings_setup_pf_show_imgs'] || $code_count > 0 && $options['pfsettings_setup_pf_show_code']){
              $step_one .= $full_article_contains;
           }
           if($img_count > 0 && $code_count < 0 || $img_count > 0 && $code_count == ''){
                $img_dot = '. ';
           }
           if($img_count < 0 && $code_count > 0 || $img_count == '' && $code_count > 0){
              $code_dot = '. ';
            }
            if($img_count > 0  && $code_count > 0){
              $both_dot = '. ';
            }
            if($img_count > 0 && $options['pfsettings_setup_pf_show_imgs']) {
                if($added > 0) $step_one .= ",";
                $step_one .= " <strong>{$img_count}</strong> image(s)". $img_dot;
            }
            if($code_count > 0 && $options['pfsettings_setup_pf_show_code']) {
                if($added > 0) $step_one .= ",";
                $step_one .= " <strong>{$code_count}</strong> code snippet(s)" . $code_dot;
            }
            $step_one .= $both_dot;
            $step_one .=  $options['pfsettings_setup_pf_html_intro'];
            $step_one .= '</div>';
            $step_one .= '<ul class="pf-icons">';
            $step_one .= $this->do_likes() . $this->do_neutrals() . $this->do_dislikes();
            $step_one .= '</ul>';
            $step_one .= '<div class="clear"></div>';
            $step_one .= '</section>';
            $article  .= $step_one;
            $step_two .= '<section>';
            $step_two .= '<div class="pf-intro-text">';
            $step_two .= $options['pfsettings_pf-headwords_pf_headwords_html'];
            $step_two .= '</div>';
            $step_two .= $this->headwords_html();
            $step_two .= '</section>';
            $article  .= $step_two;
            $step_three .= '<section>';
            $step_three .= '<div align="center">';
            $step_three .=  $this->output_share_html();
            $step_three .= '<div class="clear clearfix"></div>';
            $step_three .= '<a class="pf-btn pf-block">Continue</a>';
            $step_three .= '</div>';
            $step_three .= '</section>';
            $article  .= $step_three;
            $article .= "</div>";
            $article .= "</div>";
            $article .= '';
            $truncated_article = $this->truncate($content, $article_lock_data['length'], "...", true, true);
                

            if(is_singular('post')) return $truncated_article.$article;     
        }
        
        if($voted == true){
          $msg  = '<section>'; 
          $msg .= '<div class="tn-box tn-box-color-2">';
          $msg .= '<p>Thank you for your vote!</p>';
          $msg .= '<div class="tn-progress"></div>';
          $msg .= '</div>';        
          $msg .= '</section>';
          return $msg . $content . $this->pf_show_votes();
        }

        return $content.$this->pf_show_votes();
    
    }
    

    public function article_lock_metabox_add() {
        add_meta_box('article-lock', 'Article Lock', array(&$this, 'article_lock_do'), 'post', 'side', 'high' );
    }


    public function article_lock_do($post) {
        $options            = pf_get_settings( PFPATH .'/admin/settings/settings-general.php' );
        $article_lock_data  = get_post_meta($post->ID, 'article_lock_data', true);
        $length             = isset($article_lock_data['length']) ? esc_attr($article_lock_data['length']) : $options['preview_size'];
        $on                 = isset($article_lock_data['on']) ? esc_attr($article_lock_data['on']) : 'off';

        wp_nonce_field('my_meta_box_article_lock', 'meta_box_article_lock'); ?>
      <p>
        <strong>Preview size</strong> (in number of characters)
        <input type="text" id="article_lock_preview_size" class="widefat" name="article_lock_preview_size" maxlength="5" size="5" autocomplete="off" value="<?php echo $length; ?>" />
      </p>
      <div>
        <strong>Activate</strong> lock
        <input type="checkbox" name="article_lock_on" id="article_lock_on" value="on" <?php checked($on, 'on'); ?> />
    </div>
  <?php 
  }

  public function article_lock_save($post_id) {

        if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return; 
        if(!isset($_POST['meta_box_article_lock']) || !wp_verify_nonce($_POST['meta_box_article_lock'], 'my_meta_box_article_lock')) return;
      
        if(!current_user_can('edit_post')) return;
      
        $length   = $_POST['article_lock_preview_size'];
        $on       = (isset($_POST['article_lock_on']) && $_POST['article_lock_on']) ? 'on' : 'off';
    
        $article_lock = array('length' => $length,'on' => $on);
        update_post_meta($post_id, 'article_lock_data', $article_lock);
  }

  public function truncate($text, $length = 100, $ending = '...', $exact = true, $considerHtml = false) {
        if ($considerHtml) {

            if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                return $text;
            }
            preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
            $total_length = strlen($ending);
            $open_tags = array();
            $truncate = '';
            $left_over = '';
          
            foreach ($lines as $line_matchings) {
                if (!empty($line_matchings[1])) {
                    if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {

                    } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                        $pos = array_search($tag_matchings[1], $open_tags);
                        if ($pos !== false) {
                            unset($open_tags[$pos]);
                        }
                    } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                        array_unshift($open_tags, strtolower($tag_matchings[1]));
                    }
                    $truncate .= $line_matchings[1];
                }
                $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
                if ($total_length+$content_length> $length) {
                    $left = $length - $total_length;
                    $entities_length = 0;
                    if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                        foreach ($entities[0] as $entity) {
                            if ($entity[1]+1-$entities_length <= $left) {
                                $left--;
                                $entities_length += strlen($entity[0]);
                            } else {
                                break;
                            }
                        }
                    }
                    $truncate  .= substr($line_matchings[2], 0, $left+$entities_length);
                    $left_over .= $text;
                    break;
                } else {
                    $truncate .= $line_matchings[2];
                    $total_length += $content_length;
                }
                if($total_length>= $length) {
                    break;
                }
            }
        } else {
            if (strlen($text) <= $length) {
                return $text;
            } else {
              $total_l = strlen($text);
                $truncate  = substr($text, 0, $length - strlen($ending));
                $left_over = substr($text,$length - strlen($ending),$total_l);
            }
        }
        if (!$exact) {
            $spacepos = strrpos($truncate, ' ');
            if (isset($spacepos)) {
                $truncate = substr($truncate, 0, $spacepos);
            }
        }
        $truncate .= $ending; 
        if($considerHtml) {
            foreach ($open_tags as $tag) {
                $truncate .= '</' . $tag . '>';
            }
        }
        return '<div class="pf-preview">' . $truncate . '</div>'.'<div class="pf-invisible">' . $left_over . '</div>';
    }

    public function headwords_html(){
      global $post;
      
      $pf_data = get_post_meta($post->ID,'pf_headwords',true);
      if($pf_data):
         $headwords = '<table border="0" class="pf-table">';
               foreach($pf_data as $t){
                   if($t != ''){
                     $headwords .= '<tr class="pf-list">';
                     $headwords .= '<td>'.'<a href="#" class="pf-headword" id="'. $t['name'] .'" data-num="'.$post->ID.'">' . '<span data-hover="'.$t['name'].'">' . $t['name'] . '</span>' . '</a>'.'</td>';
                     $headwords .= '<td>'.'<i>' . $t['desc'] . '</i>' . '</td>';
                     $headwords .= '<td>'. '<span class="pf-badge">' . $t['rating'] . '</span>' . '</td>';
                     $headwords .= '</tr>';
                   }
               }
        $headwords .= '</table>';
      return $headwords;
      else:
      return 'Please set headwords in the backend to display them.';
    endif;
    }
     
}
global $pf_likes;
$pf_likes = new POST_LOCK();


function pf_likes() {
    global $pf_likes;
    return $pf_likes->pf_show_positives();      
}

function pf_neutrals() {
    global $pf_likes;
    return $pf_likes->pf_show_neutrals();      
}

function pf_dislikes() {
    global $pf_likes;
    return $pf_likes->pf_show_negatives();      
}
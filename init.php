<?php
/*
  Plugin Name: Post Feedback
  Plugin URI: http://leandermests.me/
  Description: Post Feedback
  Version: 1.1.2
  Author: Leander Melms
  Author URI: http://leandermests.me/
 */

class POST_FEEDBACK {

    private $plugin_path;
    private $plugin_url;
    private $l10n;
    private $pf;

    public function __construct() 
    {   
        // Globals
        if(!defined('PFURL')):
            define('PFURL', WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) ) );
            define('PFPATH', WP_PLUGIN_DIR."/".dirname( plugin_basename( __FILE__ ) ) );
            define('PF_PREFIX','pf_feedback_');
            define('PF_VERSION', '1.0');
        endif;

        global $wpdb;
        $wpdb->pf_version           = PF_VERSION;
        $wpdb->headwords_table      = $wpdb->prefix . PF_PREFIX . 'headwords';
        $wpdb->log_table           = $wpdb->prefix . PF_PREFIX . 'log';

        // Vars
        $this->plugin_path = plugin_dir_path( __FILE__ );
        $this->plugin_url = plugin_dir_url( __FILE__ );
        $this->l10n = 'postfeedback-framework';

        $this->pf_actions();
        $this->pf_res();
        $this->pf_setup();

    }

    public function pf_actions(){

        add_action( 'admin_menu', array(&$this, 'admin_menu'), 99 );            // Admin Menu
        add_action('wp_ajax_pf_ajax_post_action', 'pf_ajax_callback');          // Ajax Call
        
        // Admin Scripts
        if ( is_admin() ) {
            add_action('admin_enqueue_scripts', array(&$this,'pf_admin_scripts' ));
        }
    }

    public function pf_res(){
        require_once($this->plugin_path .'/admin/pf-framework.php' );           // Settings Framework
        require_once($this->plugin_path . '/lib/pf-admin-save.php');            // Ajax Save Settings
        require_once($this->plugin_path . '/lib/pf-functions.php');             // Main Fuctions
        require_once($this->plugin_path . '/lib/pf-post-type.php');             // Social Fuctions    
    }

    public function pf_setup(){
        $this->pf = new PFFramework( $this->plugin_path .'/admin/settings/pf-settings.php' );     
    }

    public static function pf_admin_scripts() {
      wp_enqueue_script('pfadmin',  PFURL . '/js/pf-admin.js', array('jquery','jquery-ui-tabs'), '1.0');
      wp_enqueue_style( 'pfchosen-style', PFURL .  '/admin/css/style.css' );
    }


    public function admin_menu() {
        $page_hook = add_menu_page( __( 'PF', $this->l10n ), __( 'PF', $this->l10n ), 'update_core', 'PF', array(&$this, 'settings_page') );
        add_submenu_page( 'PF', __( 'Statistics', $this->l10n ), __( 'Statistics', $this->l10n ), 'update_core', 'pf-statt', array(&$this, 'sub_settings_charts') );
    }
    
    public function settings_page() {
        ?>
        <div class="wrap pf-options">
            <div id="icon-options-general" class="icon32"></div>
            <h2>Post Feedback Settings</h2>
            <div class="options-wrapr">
                <?php 
                    $this->pf->settings();
                    $pf_data = pf_get_settings( $this->plugin_path .'/admin/settings/pf-settings.php' );
                ?>
            </div>
        </div>
        <?php
    }

    public function sub_settings_charts(){

        echo '<section>';
        echo '<h3>'. 'Top 10 Most Liked Posts' . '</h3>';
        echo '</section>';

        global $wp_query;
        $args = array(
            'post_type' => 'post',
            'meta_key' => '_pf_pos',
            'orderby' => 'meta_value_num',
            'posts_per_page' => 10
         );

         $pf_query = new WP_Query($args);
         echo '<table cellspacing="0" class="wp-list-table widefat fixed likes">';
         echo '<thead><tr><th class="manage-column column-cb check-column" id="cb" scope="col">';
         echo '<th>';
         _e('Post Title', $this->l10n);
         echo '</th><th>';
         _e('Like Count', $this->l10n);
         echo '</th><th>';
         _e('Neutral Count', $this->l10n);
         echo '</th><th>';
         _e('Dislike Count', $this->l10n);
         echo '</th>';
         echo '<tr></thead>';
         echo '<tbody class="list:likes" id="the-list">';
         if ( $pf_query->have_posts() ) :
            while ( $pf_query->have_posts() ) : $pf_query->the_post();
                $negs       = get_post_meta(get_the_ID(), '_pf_neg', true);
                $neutrals   = get_post_meta(get_the_ID(), '_pf_neu', true);
                $likes      = get_post_meta(get_the_ID(), '_pf_pos', true);
                   echo '<tr>';
                   echo '<th class="check-column" scope="row" align="center"></th>';
                   echo '<td><a href="' . get_permalink() . '" title="' . get_the_title().'" rel="nofollow" target="_blank">' . get_the_title() . '</a></td>';
                   echo '<td>'.$likes.'</td>';
                   echo '<td>'.$neutrals.'</td>';
                   echo '<td>'.$negs.'</td>';
                   echo '</tr>';
            endwhile;
            endif;
                echo '</tbody></table>';
    }
    
    public function validate_settings( $input ){
        return $input;
    }


    public static function pf_generate_options_css($newdata) {
        $css_dir = PFPATH . '/css/'; 
        ob_start(); 
        require($css_dir . 'user-styles.php'); 

        $css = ob_get_clean();
        file_put_contents($css_dir . 'options.css', $css, LOCK_EX); // Save it
    }

}

new POST_FEEDBACK();


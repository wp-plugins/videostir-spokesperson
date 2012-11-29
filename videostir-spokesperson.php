<?php

/*
  Plugin Name: VideoStir Spokesperson
  Plugin URI: http://wordpress.org/extend/plugins/videostir-spokesperson/
  Description: With this plugin you can easily adjust and embed VideoStir clip into your website pages and posts.
  Version: 1.1.1
  Author: VideoStir team
  Author URI: http://videostir.com/?utm_source=wp-plugin&utm_medium=plugin&utm_campaign=wp-plugin
 */

class VideoStir {

    var $prefix = 'videostir_';
    var $table_name;
    var $icon;
    var $logo;

    function __construct()
    {
        global $wpdb;

        $this->icon = get_bloginfo('url').'/wp-content/plugins/videostir-spokesperson/img/icon.png';
        $this->logo = get_bloginfo('url').'/wp-content/plugins/videostir-spokesperson/img/logo.png';

        $this->table_name = $wpdb->prefix.$this->prefix.'videos';

        register_activation_hook(__FILE__,   array(&$this, 'install'));
        register_deactivation_hook(__FILE__, array(&$this, 'uninstall'));

        add_action('admin_menu', array($this, 'config_page'));
        add_action('wp_footer',  array($this, 'vs_wp_footer'));
        
        wp_enqueue_script('jquery');
        wp_enqueue_script('swfobject');
        wp_enqueue_script('videostir-spokesperson.plugin', plugins_url('/js/videostir.wp.plugin.js', __FILE__), array('jquery', 'swfobject'));
        wp_enqueue_script('videostir-spokesperson.player', plugins_url('/js/1.2.1/videostir.player.min.js', __FILE__), array('videostir-spokesperson.plugin'));
    }

    function vs_wp_footer()
    {
        global $wpdb, $wp_query;

        $videos = $wpdb->get_results('SELECT * FROM `'.$this->table_name.'` WHERE `active` = 1;', ARRAY_A);

        $page = $wp_query->get_queried_object();
        $pageid = $page->ID;
        $home = '';

        if ($pageid == '') {
            $home = '0';
        }

        if (is_home() || is_front_page()) {
            $home = '0';
        }

        foreach ($videos as $video) {
            $pages = explode(',', $video['pages']);

            $print = false;
            if ($pages[0] != '') {
                if ($home != '') {
                    if (in_array(0, $pages)) {
                        echo VideoStir::createPlayerJs($video);
                        $print = true;
                    }
                }

                if (!$print) {
                    if (in_array($pageid, $pages)) {
                        echo VideoStir::createPlayerJs($video);
                    }
                }
            }
        }
    }
    
    /**
     * Makes JavaScript for embedding video clip by DB row record.
     * 
     * @param array $videoRow
     * @return string
     */
    static function createPlayerJs(array $videoRow)
    {
        $js = '';
        
        if (!empty($videoRow)) {
            $js.= '<script type="text/javascript">VideoStir.Player.show(';
            $js.= (is_array(unserialize($videoRow['position']))) ? json_encode(unserialize($videoRow['position'])) : unserialize($videoRow['position']);
            $js.= ', '.$videoRow['width'];
            $js.= ', '.$videoRow['height'];
            $js.= ', "'.$videoRow['url'].'"';
            $js.= ', '.json_encode(unserialize($videoRow['settings']));
            $js.= ');</script>'.PHP_EOL;
        }
        
        return $js;
    }

    function install()
    {
        global $wpdb;

        $query = '
        CREATE TABLE IF NOT EXISTS `'.$this->table_name.'`
        (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT
        ,   `name` VARCHAR(64) COLLATE utf8_unicode_ci NOT NULL
        ,   `pages` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL
        ,   `active` TINYINT UNSIGNED NOT NULL
        
        ,   `position` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL
        ,   `width` INT UNSIGNED NOT NULL
        ,   `height` INT UNSIGNED NOT NULL
        ,   `url` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL
        ,   `settings` TEXT COLLATE utf8_unicode_ci NOT NULL
        
        ,   PRIMARY KEY (`id`)
        )
        ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';

        $wpdb->query($query);
    }

    function uninstall()
    {
        global $wpdb;

        $sql = 'DROP TABLE `'.$this->table_name.'`';
        $wpdb->query($sql);
    }

    function config_page()
    {
        add_menu_page('VideoStir', 'VideoStir', 8, 'videostir_options', array(&$this, 'cf_all_video'), $this->icon);
        
        add_submenu_page('videostir_options', 'All Videos', 'All videos', 8, 'videostir_options', array(&$this, 'cf_all_video'));
        add_submenu_page('videostir_options', 'Add New Video', 'Add new', 8, 'videostir_options_sub', array(&$this, 'cf_actions'));
    }

    function cf_all_video()
    {
        include 'page-all-video.php';
    }

    function cf_actions()
    {
        if (!isset($_GET['action'])) {
            include 'page-add-new.php';
        } else {
            if ($_GET['action'] == 'delete') {
                include 'page-delete.php';
            } else {
                if($_GET['action'] == 'active') {
                    include 'page-active.php';
                } else {
                    include 'page-edit.php';
                }
            }
        }
    }

}

new VideoStir();

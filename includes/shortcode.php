<?php

namespace fabcalpre;

require_once FAB_BASE_PLUGIN_DIR_PATH . 'fab_base/fab_shortcode.php';

if (!class_exists('fabcalpre\shortcode')) {
    class shortcode extends \fab\fab_shortcode
    {
        public $onlyregister_user = false;
        public $shortcode_attribs = array('c' => false, 'a' => 'home');
        
        public function __construct($parent, $shortcode_name)
        {
            $this->shortcode_name = $shortcode_name;
            $this->parent = $parent;
            add_action('init', array($this, 'register_shortcode'));
            add_action('wp_enqueue_scripts', array($this, 'load_scripts_styles'));
            add_filter('show_admin_bar', array(&$this, 'show_admin_bar'));
        }

        public function load_scripts_styles()
        {
            // sweetalert2
            wp_register_script('sweetalert2', 'https://cdn.jsdelivr.net/npm/sweetalert2@9', array(), '1.0');
            wp_enqueue_script('sweetalert2');

            // fancybox3
            wp_register_style('fancybox3-css', 'https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css', array(), '3.5.7');
            wp_enqueue_style('fancybox3-css');

            wp_register_script('fancybox3', 'https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js', array('jquery'), '3.5.7');
            wp_enqueue_script('fancybox3');

            wp_register_style('fabcalpre-css', FAB_PLUGIN_DIR_URL . 'assets/fabcalpre.css', array(), '1.1');
        }

        public function load_assets()
        {
            // wp_enqueue_style
            // wp_enqueue_script
            wp_enqueue_style('fabcalpre-css');
            return false;
        }

        public function view_shortcode($atts, $content = null)
        {
            $this->shortcode_attribs = shortcode_atts($this->shortcode_attribs, $atts);

            $this->load_assets();

            $this->before_render();
            ob_start();
            //$this->update_page_installed();
            if ($this->shortcode_attribs['c'] == false) {
                //$this->render_by();
            } else {
                $this->parent->current_controller = $this->shortcode_attribs['c'];
                $this->parent->current_controller_obj = $this->parent->load_controller($this->shortcode_attribs['c']);
                $this->render($this->shortcode_attribs['a']);
            }
            return ob_get_clean();
        }

        public function show_admin_bar($bool)
        {
            return false;
        }
    }
}

<?php
class OKG_Categories_Display {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));
        add_filter('widget_text', 'do_shortcode');
    }
    
    public function generate_categories_html($args = array()) {
        $defaults = array(
            'category_count' => 8,
            'order_by' => 'rand',
            'order' => 'ASC',
            'show_empty' => false
        );
        
        $settings = wp_parse_args(get_option('okg_settings'), $defaults);
        $args = wp_parse_args($args, $settings);
        
        // TÜM KATEGORİLERİ ÇEK
        $categories = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => !$args['show_empty'],
            'orderby' => $args['order_by'],
            'order' => $args['order']
        ));
        
        if (empty($categories) || is_wp_error($categories)) {
            return '<!-- No categories found -->';
        }
        
        // RASTGELE KARIŞTIR
        if ($args['order_by'] === 'rand') {
            shuffle($categories);
        }
        
        // SAYI LIMITI UYGULA
        if ($args['category_count'] > 0) {
            $categories = array_slice($categories, 0, $args['category_count']);
        }
        
        ob_start();
        include OKG_PLUGIN_PATH . 'templates/categories-template.php';
        return ob_get_clean();
    }
    
    public function enqueue_frontend_scripts() {
        wp_enqueue_style('okg-frontend-style', OKG_PLUGIN_URL . 'assets/css/frontend-style.css', array(), OKG_VERSION);
    }
}
?>

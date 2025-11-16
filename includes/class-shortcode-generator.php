<?php
class OKG_Shortcode_Generator {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_shortcode('auto_categories', array($this, 'shortcode_handler'));
    }
    
    public function shortcode_handler($atts) {
        $atts = shortcode_atts(array(
            'count' => '',
            'order_by' => '',
            'order' => '',
            'show_empty' => '',
            'display_type' => ''
        ), $atts);
        
        $args = array();
        
        if (!empty($atts['count'])) {
            $args['category_count'] = intval($atts['count']);
        }
        
        // ... diğer parametreler aynı
        
        $categories_display = OKG_Categories_Display::get_instance();
        return $categories_display->generate_categories_html($args);
    }
}
?>

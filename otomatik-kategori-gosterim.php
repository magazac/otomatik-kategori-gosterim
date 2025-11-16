<?php
/**
 * Plugin Name: Otomatik Kategori Gösterim
 * Plugin URI: https://github.com/magazac/otomatik-kategori-gosterim
 * Description: WooCommerce kategorilerini otomatik olarak footer ve diğer alanlarda gösterir
 * Version: 1.2.0
 * Author: Magazac
 * Author URI: https://magazac.com
 * Text Domain: otomatik-kategori-gosterim
 * Domain Path: /languages
 * Requires at least: 5.8
 * Tested up to: 6.4
 * WC requires at least: 6.0
 */

// Güvenlik kontrolü
if (!defined('ABSPATH')) {
    exit;
}

// Eklenti sabitleri
define('OKG_PLUGIN_URL', plugin_dir_url(__FILE__));
define('OKG_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('OKG_VERSION', '1.2.0');

// Temel sınıfları yükle
require_once OKG_PLUGIN_PATH . 'includes/class-admin-settings.php';
require_once OKG_PLUGIN_PATH . 'includes/class-categories-display.php';
require_once OKG_PLUGIN_PATH . 'includes/class-shortcode-generator.php';
require_once OKG_PLUGIN_PATH . 'includes/class-github-updater.php';

class OtomatikKategoriGosterim {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('plugins_loaded', array($this, 'init'));
        register_activation_hook(__FILE__, array($this, 'activate'));
    }
    
    public function init() {
        // Admin ayarlarını başlat
        OKG_Admin_Settings::get_instance();
        
        // Kategori görüntüleme sınıfını başlat
        OKG_Categories_Display::get_instance();
        
        // Kısa kod sınıfını başlat
        OKG_Shortcode_Generator::get_instance();
        
        // GitHub güncelleme sınıfını başlat
        OKG_Github_Updater::get_instance();
        
        // Çoklu dil desteği
        load_plugin_textdomain('otomatik-kategori-gosterim', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    
    public function activate() {
        // Varsayılan ayarları kaydet
        $default_settings = array(
            'category_count' => 8,
            'order_by' => 'rand',
            'order' => 'ASC',
            'show_empty' => false
        );
        
        if (!get_option('okg_settings')) {
            update_option('okg_settings', $default_settings);
        }
        
        // Eklenti sürümünü kaydet
        update_option('okg_version', OKG_VERSION);
    }
}

// Eklentiyi başlat
OtomatikKategoriGosterim::get_instance();
?>
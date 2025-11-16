<?php
class OKG_Admin_Settings {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    public function add_admin_menu() {
        add_options_page(
            'Otomatik Kategori Gösterim',
            'Otomatik Kategoriler',
            'manage_options',
            'otomatik-kategori-gosterim',
            array($this, 'admin_page')
        );
    }
    
    public function register_settings() {
        register_setting('okg_settings_group', 'okg_settings');
        
        add_settings_section(
            'okg_main_section',
            'Temel Ayarlar',
            array($this, 'main_section_callback'),
            'otomatik-kategori-gosterim'
        );
        
        // ... diğer ayar alanları aynı, sadece 'apc_' -> 'okg_' olarak değişecek
    }
    
    public function main_section_callback() {
        echo '<p>WooCommerce kategorilerinizin nasıl görüntüleneceğini ayarlayın.</p>';
    }
    
    public function category_count_callback() {
        $options = get_option('okg_settings');
        $value = isset($options['category_count']) ? $options['category_count'] : 8;
        echo '<input type="number" name="okg_settings[category_count]" value="' . esc_attr($value) . '" min="1" max="50" />';
        echo '<p class="description">Footer\'da gösterilecek kategori sayısı</p>';
    }
    
    // ... diğer callback fonksiyonları aynı, sadece 'apc_' -> 'okg_' 
    
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1>Otomatik Kategori Gösterim</h1>
            
            <form method="post" action="options.php">
                <?php
                settings_fields('okg_settings_group');
                do_settings_sections('otomatik-kategori-gosterim');
                submit_button();
                ?>
            </form>
            
            <div class="okg-usage-info">
                <h2>Kullanım Bilgileri</h2>
                
                <h3>Kısa Kod Kullanımı</h3>
                <p>Sayfa veya yazı içinde kullanmak için:</p>
                <code>[auto_categories]</code>
                
                <h3>PHP Kodu Kullanımı</h3>
                <p>Tema dosyalarında kullanmak için:</p>
                <code>&lt;?php echo do_shortcode('[auto_categories]'); ?&gt;</code>
                
                <h3>Özelleştirilmiş Kısa Kod</h3>
                <p>Özel ayarlar için:</p>
                <code>[auto_categories count="5" order_by="count" order="DESC"]</code>
            </div>
        </div>
        <?php
    }
    
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'settings_page_otomatik-kategori-gosterim') {
            return;
        }
        
        wp_enqueue_style('okg-admin-style', OKG_PLUGIN_URL . 'assets/css/admin-style.css', array(), OKG_VERSION);
        wp_enqueue_script('okg-admin-script', OKG_PLUGIN_URL . 'assets/js/admin-script.js', array('jquery'), OKG_VERSION, true);
    }
}
?>

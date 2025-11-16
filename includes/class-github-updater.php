<?php
class OKG_Github_Updater {
    
    private static $instance = null;
    private $github_username = 'magazac';
    private $github_repo = 'otomatik-kategori-gosterim';
    private $plugin_slug = 'otomatik-kategori-gosterim';
    private $plugin_file = 'otomatik-kategori-gosterim/otomatik-kategori-gosterim.php';
    
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_filter('pre_set_site_transient_update_plugins', array($this, 'check_update'));
        add_filter('plugins_api', array($this, 'plugin_info'), 10, 3);
        add_filter('upgrader_post_install', array($this, 'post_install'), 10, 3);
    }
    
    public function check_update($transient) {
        if (empty($transient->checked)) {
            return $transient;
        }
        
        $remote_version = $this->get_remote_version();
        
        if ($remote_version && version_compare(OKG_VERSION, $remote_version, '<')) {
            $obj = new stdClass();
            $obj->slug = $this->plugin_slug;
            $obj->new_version = $remote_version;
            $obj->url = "https://github.com/{$this->github_username}/{$this->github_repo}";
            $obj->package = "https://github.com/{$this->github_username}/{$this->github_repo}/releases/download/v{$remote_version}/otomatik-kategori-gosterim.zip";
            $obj->tested = '6.4';
            $obj->requires_php = '7.4';
            
            $transient->response[$this->plugin_file] = $obj;
        }
        
        return $transient;
    }
    
    public function plugin_info($false, $action, $response) {
        if ($action !== 'plugin_information') {
            return $false;
        }
        
        if (!isset($response->slug) || $response->slug !== $this->plugin_slug) {
            return $false;
        }
        
        $remote_info = $this->get_remote_info();
        
        if ($remote_info) {
            $response->last_updated = $remote_info->last_updated;
            $response->slug = $remote_info->slug;
            $response->plugin_name  = $remote_info->plugin_name;
            $response->name = $remote_info->name;
            $response->version = $remote_info->version;
            $response->author = $remote_info->author;
            $response->homepage = $remote_info->homepage;
            $response->download_link = $remote_info->download_link;
            $response->sections = $remote_info->sections;
            
            return $response;
        }
        
        return $false;
    }
    
    public function post_install($true, $hook_extra, $result) {
        global $wp_filesystem;
        
        $plugin_path = plugin_dir_path(__FILE__);
        $wp_filesystem->move($result['destination'], $plugin_path);
        $result['destination'] = $plugin_path;
        
        return $result;
    }
    
    private function get_remote_version() {
        $response = wp_remote_get("https://api.github.com/repos/{$this->github_username}/{$this->github_repo}/releases/latest", array(
            'headers' => array(
                'User-Agent' => 'WordPress/' . get_bloginfo('version')
            )
        ));
        
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            return false;
        }
        
        $release_data = json_decode(wp_remote_retrieve_body($response));
        
        if (isset($release_data->tag_name)) {
            return ltrim($release_data->tag_name, 'v');
        }
        
        return false;
    }
    
    private function get_remote_info() {
        $response = wp_remote_get("https://raw.githubusercontent.com/{$this->github_username}/{$this->github_repo}/main/plugin-info.json");
        
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            return false;
        }
        
        $info_data = json_decode(wp_remote_retrieve_body($response));
        
        return $info_data;
    }
}
?>

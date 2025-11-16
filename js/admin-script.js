jQuery(document).ready(function($) {
    // Admin scriptleri buraya gelecek
    console.log('Auto Product Categories admin yüklendi');
    
    // Ayarları sıfırla butonu
    $('.apc-reset-settings').on('click', function(e) {
        e.preventDefault();
        
        if (confirm('Ayarları varsayılana sıfırlamak istediğinizden emin misiniz?')) {
            // Sıfırlama işlemi
            $('#apc_settings_category_count').val(8);
            $('#apc_settings_order_by').val('name');
            $('#apc_settings_order').val('ASC');
        }
    });
});

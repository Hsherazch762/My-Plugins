<?php
function wcvm_plugin_scripts() {
//         //Plugin Frontend JS
         wp_enqueue_script('wcvm-js', WCVM_PLUGIN_DIR. 'assets/js/main.js', 'jQuery', '1.0.0', true );

wp_enqueue_script('wcvm-ajax', WCVM_PLUGIN_DIR. 'assets/js/wcvm-ajax.js', 'jQuery', '1.0.0', true );

        wp_localize_script( 'wcvm-ajax', 'wcvm_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}
//add_action( 'wp_enqueue_scripts', 'wcvm_plugin_scripts' );
add_action( 'admin_enqueue_scripts', 'wcvm_plugin_scripts' );
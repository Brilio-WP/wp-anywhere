<?php
function admin_permissions() {
    global $pagenow;
    if ($pagenow == 'post.php' && $_GET["action"] == "elementor" ||  defined( 'DOING_AJAX' ) && DOING_AJAX) {
        return true;
    }
  /*  echo '<h2 style="padding-left: 200px">You are not allowed to access this page</h1>';
    exit;*/
    $redirect_url = "https://my.10web.io/websites?showUpgradePopup=1";
    wp_redirect($redirect_url, 301);
}
add_action('admin_init', 'admin_permissions');
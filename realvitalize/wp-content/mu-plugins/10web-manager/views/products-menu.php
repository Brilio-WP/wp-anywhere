<?php
$domain_id = get_site_option(TENWEB_PREFIX . '_domain_id');
/*if (empty($this->user_agreements)) {
    echo '<h2 style="text-align: center;padding-top: 10px;">You have not subscribe to <a target="_blank" href="' . TENWEB_DASHBOARD . '/workspace/account/subscription-plan">10web.</a></h2>';

    return;
}*/
/*$dashboard_url = TENWEB_DASHBOARD . '/websites/' . $domain_id . '/plugins/installed';
if (is_multisite()) {
    $dashboard_url = TENWEB_DASHBOARD . '/websites/' . $domain_id . '/my-sites';
}*/

$dashboard_url = TENWEB_DASHBOARD;
?>

<div id="tenweb_manager_products">
    <div class="tenweb-content-wrap">
        <div class="tenweb-left-section">
            <img src="<?php echo TENWEB_URL ?>/assets/images/10web-logo.svg" />
            <div class="tenweb-left-section-content">
                <div>
                    <img src="<?php echo TENWEB_URL ?>/assets/images/hand2x.png" /><h3>Hey <?php echo $user_name;?>!</h3>
                </div>
                <p>10Web Manager links your WordPress site<br/>to 10Web Dashboard.</p>
                <a href="<?php echo $dashboard_url ;?>" class="button logged-in">Go to 10Web Dashboard</a>
            </div>

        </div>
        <div class="tenweb-right-banner">

        </div>
    </div>
	<?php if (!\Tenweb_Manager\Helper::check_if_manager_mu() && !get_site_transient(TENWEB_PREFIX . "_disable_logout")): ?>
        <div class="tenweb-footer-wrap">
            <img src="<?php echo TENWEB_URL ?>/assets/images/check2x.png" />
            <b>Site is connected:</b>
            <span class="logged-in tm_products_logout">Disconnect from 10Web Manager</span>
        </div>
	<?php endif; ?>

<!--    <div class="tm_products_content">
        <div class="tm_products_header_container clear">
            <span class="tm_products_header_username">Hi, <?php /*echo $user_name; */?></span>

            <?php /*if (!\Tenweb_Manager\Helper::check_if_manager_mu() && !get_site_transient(TENWEB_PREFIX . "_disable_logout")): */?>
                <span class="tm_products_logout">LOG OUT</span>
            <?php /*endif; */?>

        </div>
        <div class="tm_products_logo_container">
            <div class="tm_products_logo"></div>
        </div>
        <div class="tm_products_text_container">
            <div>
                <span class="tm_products_title">Welcome to 10WEB Manager!</span>
            </div>
            <div class="tm_products_text_wrapper">
                <p>10Web Manager links your WordPress site to 10Web Dashboard.<br> Please navigate to 10Web Dashboard to
                    manage services, plugins and themes.</p>
            </div>
        </div>
        <div class="tm_products_button_container">
            <a href="<?php /*echo $dashboard_url; */?>" target="_blank"
               class="tm_products_button">GO TO 10WEB DASHBOARD</a>
        </div>
    </div>-->
</div>

<?php if (!\Tenweb_Manager\Helper::check_if_manager_mu()): ?>
    <form id="tenweb_manager_logout_form" method="post">
        <input type="hidden" name="action" value="logout">
        <?php wp_nonce_field(TENWEB_PREFIX . '_nonce', TENWEB_PREFIX . '_nonce'); ?>
    </form>

<?php endif; ?>

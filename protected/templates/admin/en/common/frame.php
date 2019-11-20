<!doctype html>
<html lang="en" dir="ltr">
<?php require_once TEMPLATE_DIR . 'common/head.php'; ?>

<body class="theme-black">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <img src="<?php echo SITE_DIR; ?>assets/theme/assets/images/logo-icon.svg" alt="Buzzer">
    </div>
</div>
<!-- Overlay For Sidebars -->

<div id="main_content">
    <?php require_once TEMPLATE_DIR . 'common/header.php'; ?>
    <?php require_once TEMPLATE_DIR . 'common/right_sidebar.php'; ?>
    <?php require_once TEMPLATE_DIR . 'common/left_sidebar.php'; ?>
    <div class="page">
        <div class="container-fluid" id="page_content">
            <?php require_once TEMPLATE_DIR . 'common/breadcrumbs.php'; ?>
            <?php echo $template_content; ?>
        </div>
        <?php require_once TEMPLATE_DIR . 'common/footer.php'; ?>
    </div>
</div>
<?php require_once TEMPLATE_DIR . 'common/foot.php'; ?>
</body>
</html>

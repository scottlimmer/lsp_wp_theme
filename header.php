<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width">
	<?php wp_head(); ?>
</head>
<body>
<div class="container-lg">
    <header>
        <div class="brand d-flex me-auto">

            <div class="logo">
                <a href="<?= site_url() ?>">
                    <img src="<?= get_template_directory_uri() . "/assets/images/lsp_logo.svg" ?>"
                         alt="Stylised image of Latham's Snipe">
                </a>
            </div>
            <h1 class="align-self-center">
                <a href="<?= site_url() ?>">
                    Latham's<br>Snipe<br>Project
                </a>
            </h1>
        </div>
        <?php
        wp_nav_menu(
	        array(
		        'menu_location' => 'top-menu',
		        'container'  => 'nav',
                'container_id' => 'top-menu',
		        'container_class' => 'primary align-self-center',
                'menu_class' => 'nav navbar-nav',
	        )
        );
        ?>
    </header>
    <main class="<?= get_active_template()." slug-".get_post_slug()?>">


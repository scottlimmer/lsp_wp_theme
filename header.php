<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" lang="en">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7HW0M2VP4Q"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-7HW0M2VP4Q');
    </script>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width">
    <link rel="icon" type="image/png" href="<?=get_template_directory_uri(). '/assets/images/favicon.png'?>" sizes="96x96" />
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


        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">

				<?php

				wp_nav_menu(
					array(
						'menu_location'   => 'top-menu',
						'container'       => 'nav',
						'container_id'    => 'main-menu',
						'container_class' => 'primary align-self-center',
						'menu_class'      => 'nav navbar-nav ',
					)
				);
				?>


                <button
                        class="btn btn-close-white btn-lg btn-mobile-menu"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#mobile-menu"
                        aria-controls="mobile-menu"
                        aria-label="Toggle navigation"
                >
                    <i class="fa-solid fa-bars"></i>
                </button>

            </div>
        </nav>


    </header>
    <div class="container-fluid">


        <div class="offcanvas offcanvas-end" tabindex="-1" id="mobile-menu"
             aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <div class="brand d-flex me-auto">

                    <div class="logo">
                        <a href="<?= site_url() ?>">
                            <img src="<?= get_template_directory_uri() . "/assets/images/lsp_logo.svg" ?>"
                                 alt="Stylised image of Latham's Snipe" style="max-width: 80px">
                        </a>
                    </div>
                    <h1 class="align-self-center">
                        <a href="<?= site_url() ?>">
                            Latham's<br>Snipe<br>Project
                        </a>
                    </h1>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">

				<?php


				wp_nav_menu(
					array(
						'menu_location'   => 'top-menu',
						'container'       => 'nav',
						'container_id'    => 'mobile-main-menu',
						'container_class' => 'primary align-self-center',
						'menu_class'      => 'nav navbar-nav ',
						'walker'          => new Mobile_Nav_Walker
					)
				);
				?>
            </div>
        </div>
    </div>
    <main class="<?= get_active_template() . " slug-" . get_post_slug() ?>">


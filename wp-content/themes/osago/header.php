<!DOCTYPE html>
<html <?php language_attributes() ?>>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php bloginfo('template_directory') ?>/css/fonts.css">
    <link rel="stylesheet" href="<?php bloginfo('template_directory') ?>/css/all.css">

    <link rel="stylesheet" href="<?php bloginfo('template_directory') ?>/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php bloginfo('template_directory') ?>/css/main2.css">
    
    <title><?php bloginfo('name') ?></title>
    <style>
        .bg-img {
            background: url("<?php bloginfo('template_directory') ?>/images/section1.png") no-repeat;
            background-size: 100%;
            height: 100vh;
            background-position: right bottom;
        }

        .img-thumbnail {
            padding: .25rem;
            background-color: unset;
            border: 6px solid #dee2e6;
            border-radius: .25rem;
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body class="d-flex flex-column h-100">
    <div class="header">
        <div class="bg-img">
            <div class="container-fluid h-100">
                <div class="container h-100">
                    <div class="row mt-5">
                        <div class="col col-lg-6">
                            <h1 class="title"><?php bloginfo('name') ?></h1>
                            <p class="lead my-3"><?php bloginfo('description') ?></p>
                            <a class="btn btn-outline-light" href="#about">Узнать подробнее</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
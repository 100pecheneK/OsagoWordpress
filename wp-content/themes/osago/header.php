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
        @media screen and (min-width: 425px) {
            .bg-img {
                background-color: #b7e76b;
                background: url("<?php bloginfo('template_directory') ?>/images/section1.png") no-repeat;
                background-position: right bottom;
            }

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
    <header class="container-fluid p-0">
        <div class="bg-img">
            <div class=container>
                <div class="row py-5">
                    <div class="col-12 col-md-8 align-items-md-center">
                        <h1 class="title"><?php bloginfo('name') ?></h1>
                        <p class="lead my-3"><?php bloginfo('description') ?></p>
                        <a class="btn btn-outline-dark" href="#request">Оставить заявку</a>
                    </div>
                </div>
            </div>
        </div>


    </header>
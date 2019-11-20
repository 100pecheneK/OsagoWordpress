<div class="container">
    <footer class="blog-header  py-3">
        <div class="row flex-nowrap justify-content-between align-items-center">
            <div class="col-4 pt-1">
                <h1 class="blog-header-logo text-dark"><?php bloginfo('name') ?></h1>
            </div>
            <!-- <div class="col d-flex justify-content-end align-items-center">
                <a href="#" class="social"><img src="<?php bloginfo('template_directory') ?>/images/vk.png" alt=""></a>
                <a href="#" class="social"><img src="<?php bloginfo('template_directory') ?>/images/instagram.png" alt=""></a>
                <a href="#" class="social"><img src="<?php bloginfo('template_directory') ?>/images/facebook.png" alt=""></a>
            </div> -->
            <?php
            $args = array(
                'post_type' => 'labels2',
                'post_per_page' => -1
            );
            $social = new WP_Query($args);
            ?>
            <ul class="navbar-nav flex-row ml-md-auto d-none d-md-flex">
                <?php
                while ($social->have_posts()) :
                    $social->the_post();
                    ?>
                    <?php if (get_field('vk')) : ?><li class="nav-item"><a class="nav-link p-2 fab fa-vk" href="<?php the_field('vk') ?>"></a></li><?php endif ?>
                    <?php if (get_field('instagram')) : ?><li class="nav-item"><a class="nav-link p-2 fab fa-instagram" href="<?php the_field('instagram') ?>"></a></li><?php endif ?>
                    <?php if (get_field('telegram')) : ?><li class="nav-item"><a class="nav-link p-2 fab fa-telegram" href="<?php the_field('telegram') ?>"></a></li><?php endif ?>
                    <?php if (get_field('facebook')) : ?><li class="nav-item"><a class="nav-link p-2 fab fa-facebook" href="<?php the_field('facebook') ?>"></a></li><?php endif ?>
                    <?php if (get_field('whatsapp')) : ?><li class="nav-item"><a class="nav-link p-2 fab fa-whatsapp" href="<?php the_field('whatsapp') ?>"></a></li><?php endif ?>
                    <?php if (get_field('twitter')) : ?><li class="nav-item"><a class="nav-link p-2 fab fa-twitter" href="<?php the_field('twitter') ?>"></a></li><?php endif ?>
                <?php endwhile ?>
            </ul>

        </div>
    </footer>
</div>

<script src="<?php bloginfo('template_directory') ?>/js/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="<?php bloginfo('template_directory') ?>/js/bootstrap.min.js"></script>

</body>

</html>
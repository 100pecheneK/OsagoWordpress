<div class="container">

    <footer class="blog-header  py-3">
        <div class="row justify-content-center justify-content-md-between align-items-center">
            <div class="col-12 col-md-4 justify-content-center justify-content-md-start pt-1 d-flex">
                <h1 class="blog-header-logo text-dark"><?php bloginfo('name') ?></h1>
            </div>
            <?php echo do_shortcode('[contact-form-7 id="55" title="Обратная связь."]') ?>
            <div class="col-12 col-md-5 justify-content-center justify-content-md-end d-flex ml-auto">
                <button type="button" class="btn btn-contact" id="show_hide_button">Обратная связь</button>
            </div>
            <?php
            $args = array(
                'post_type' => 'labels2',
                'post_per_page' => -1
            );
            $social = new WP_Query($args);
            ?>
            <div class="col-12 col-md-3">
                <ul class="navbar-nav flex-row justify-content-center justify-content-md-end ml-2 d-flex social">
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

        </div>
        <!-- <div class="row justify-content-center align-items-center">
            <div class="col-12">
                <a href="http://baranov.pro/" class="d-flex justify-content-center">
                    <img class="copy" src="<?php bloginfo('template_directory') ?>/images/copy.png" alt="Petr Baranov">
                </a>
            </div>
        </div> -->
    </footer>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="<?php bloginfo('template_directory') ?>/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $(function() {
            $('#tselect').on('change', function() {
                if (this.value == "Неограниченный список") {
                    $("#tfile").hide();
                }
                if (this.value == "Ограниченный список") {
                    $("#tfile").show();
                }
            })
        });



        $('#show_hide_button').click(function() {
            $('#wpcf7-f55-o2').toggle("slow")
        });


    });
</script>
</body>

</html>
<?php get_header() ?>
<main role="main" class="container-fluid">
    <div class="container">
        <div class="row mt-5 text-main">
            <div class="col-12 col-lg-7 about">
                <h2>Что такое электронный полис ОСАГО</h2>
                <p class="my-3 text-dark">При покупке у нас электронного полиса ОСАГО, 5% от его
                    стоимости мы направим на
                    благотворительность
                </p>
                <p class="my-3 text-dark">
                    Вы сами сможете выбрать благотворительную организацию из предложенного нами списка.</p>
            </div>
            <div class="col-12 col-lg-5">
                <img src="<?php bloginfo('template_directory') ?>/images/section2.png" class="img-fluid" alt="">
            </div>
        </div>
    </div>
    <div class="container question-answer">
        <div class="row">
            <div class="col">
                <h2 class="text-main">Часто задаваемые вопросы клиентов</h2>
                <?php
                $args = array(
                    'post_type' => 'labels',
                    'post_per_page' => -1
                );
                $q_and_a = new WP_Query($args);
                ?>
                <?php
                while ($q_and_a->have_posts()) :
                    $q_and_a->the_post();
                    ?>
                    <div class="row">
                        <div class="col">
                            <div class="row question justify-content-start mt-3">
                                <div class="col-8">
                                    <div class="bubble bubble-question">
                                        <span><?php the_field('question') ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row answer justify-content-end mt-3">
                                <div class="col-8 d-flex justify-content-end">
                                    <div class="bubble bubble-answer">
                                        <span><?php the_field('answer') ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                endwhile;
                ?>


            </div>
        </div>
    </div>

</main>
<div class="container-fluid header p-0 mt-3 partners">
    <div class="container">
        <div class="row p-3 justify-content-center">
            <?php
            $args = array(
                'post_type' => 'labels1',
                'post_per_page' => -1
            );
            $partner = new WP_Query($args);
            ?>
            <?php
            while ($partner->have_posts()) :
                $partner->the_post();
                ?>
                <?php
                    $image = get_field('partner_logo');
                    if (!empty($image)) : ?>


                    <div class="col-12 col-lg-4 py-3 py-lg-0">
                        <div class="row h-100">
                            <div class="col-12 d-flex justify-content-center align-items-center">
                                <img class="img-thumbnail" src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
                            </div>
                            <div class="col-12 mt-1">
                                <p class="d-flex m-auto btn btn-light donations">Собрано <?php the_field('donation') ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endwhile ?>
        </div>
    </div>
</div>
<div class="container">
    <div class="row mt-5 text-main">
        <div class="col-12 col-md-8 about">
            <h2>Кто может стать нашим клиентом?</h2>
            <p class="my-3 text-dark">1. Если Вы физическое лицо с легковым автомобилем (категория&nbsp;В),
                мы с радостью оформим Вам полис.
                <p class="my-3 text-dark">
                    2. Наш проект пока не имеет технической возможности помогать с& donatоформлением полиса по
                    другим видам транспортных средств.
                </p>
                <p class="my-3 text-dark">
                    3. Если Вы - юридическое лицо, желающее принять участие в проекте,
                    просим написать нам об этом через форму обратной связи. Мы обязательно
                    свяжемся с Вами, и постараемся предложить индивидуальный вариант
                    участия в нашем проекте.
                </p>
        </div>
        <div class="col-12 col-md-4 d-flex justify-content-center align-items-center">
            <img src="<?php bloginfo('template_directory') ?>/images/client.png" alt="">
        </div>
    </div>
</div>
<a name="contact"></a>

<div class="container-fluid contact-from header p-3">
    <div class="container">
        
        <?php echo do_shortcode('[contact-form-7 id="56" title="Заявка"]') ?>
        <?php echo do_shortcode('[contact-form-7 id="55" title="Обратная связь."]') ?>
    </div>
</div>

<?php get_footer() ?>
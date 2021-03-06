<?php
if(!defined('REPLICA')) {die('Sorry direct access to this file not allowed');}

Replica::inc_part('top','header',[
    'title' => $title,
    'meta_description'  => $meta_description,
    'meta_keywords'     => $meta_keywords
]);



?>

    <header>
        <ul>
            <?php if(@$display_nav): foreach(Replica::widget_load('menu','main') as $nav_label=>$nav_url):?>

            <li><a href="<?=$nav_url;?>"> <?=$nav_label;?></a></li>

            <?php endforeach; endif;?>

        </ul>

        <nav>
        <?php

        echo Replica::menu_generate(Replica::widget_load('menu','dropdown-menu'),['parent_class'=>'dropdown-menu','child_class'=>'submenu','grandchild_class'=>'subsubmenu']);
        ?>
        </nav>




        <div class="site-image">
            <img src="<?=@$site_img;?>" alt="That would be I.">
        </div>

        <h1 class="site-title"><?=@$site_title;?></h1>
        <p class="site-description"><?=@$site_description;?></p>
        <hr>
    </header>

    <div class="justify-text">

        <section>
            <?=@$section_who;?>
        </section>
        <section>
            <?=@$section_what;?>
        </section>

        <section>
            <?=@$section_projects;?>
        </section>
    </div>



<?php


Replica::inc_part('footer','footer'); ?>
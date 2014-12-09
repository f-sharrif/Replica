<?php
if(!defined('REPLICA')) {die('Sorry direct access to this file not allowed');}

Replica::include_get('header',[
    'title' => $title,
    'meta_description'  => $meta_description,
    'meta_keywords'     => $meta_keywords
]);
?>

<section>

    <?=Replica::dd(Replica::get('nav','main'));?>

    <?=@$content;?>
</section>



<?php Replica::include_get('footer'); ?>
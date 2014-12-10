<?php
if(!defined('REPLICA')) {die('Sorry direct access to this file not allowed');}

Replica::include_partial('top','header',
[
    'title'             => $title,
    'meta_description'  => $meta_description,
    'meta_keywords'     => $meta_keywords
]);
?>

<section>
    <?=@$content;?>
</section>



<?php Replica::include_partial('','footer'); ?>
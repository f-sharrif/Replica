<?php
if(!defined('REPLICA')) {die('Sorry direct access to this file not allowed');}
?>

<div class="events">

    <?php foreach(Replica::module_load('widget','events') as $event): ?>

        <div class="even_list">
           <h1><a href="<?=$event['url'];?>"> <?=$event['title'];?></a></h1>
            <?php if(isset($event['img'])):?>
                <img src="<?=$event['img'];?>" alt="<?=$event['title'];?>" title="<?=$event['title'];?>">
            <?php endif; ?>
            <p><?=$event['description'];?></p>
            <ul>
                <li>Date: <?=$event['date'];?><br></li>
                <li>Location: <?=$event['location'];?><br></li>
                <li>Contact: <?=$event['contact_name'];?><br></li>
                <li>Contact Info: <?=$event['contact_email'];?><br></li>
            </ul>
        </div>
        <hr>

     <?php endforeach;?>


</div>

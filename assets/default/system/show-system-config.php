<?php
if(!defined('REPLICA')) {die('Sorry direct access to this file not allowed');}

Replica::inc_part('top','header',[
    'title' => $title,

]);


?>

<style>
    table {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #efefef;
    }
    table tr{
        background-color: #efefef;
        padding: 10px;
    }
    table tr td
    {
        padding: 6px;
    }
    table tr td:first-child
    {
        width: 33%;
    }
    table tr td:last-child
    {
        width: 10%;
    }
    table tr td input
    {
        width: 100%;
        padding: 10px;
    }


    table tr:hover
    {
        opacity: .7;
        cursor: pointer;
    }
    table tr:hover table tr td button
    {
        opacity: 1;
    }

    table tr td button
    {
        padding: 6px;
        background-color: darkgreen;
        outline: none;
        border: 0;
        border-radius: 3px;
        color: #ddd;
    }
    table tr td button:hover
    {
        cursor: not-allowed;
        background-color: darkolivegreen;
    }


</style>



<div class="justify-text">
    <h2><?=$title;?></h2>
    <hr>
<table cellpadding="1" cellspacing="1">

            <?php foreach($config as$k=>$config):

    $k = ucwords(str_replace('_',' ',$k));

    ?>
    <tr><td><?=$k;?></td><td><input  type="text" value="<?php echo (is_array($config)) ? print_r($config) : $config; ?>"></td> <td><button> Update</button> </td></td></tr>
<?php endforeach; ?>


</table>

 </div>
<?php Replica::inc_part('footer','footer',['footer-widgets'=>false]); ?>
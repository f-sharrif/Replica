<?php
if(!defined('REPLICA')) {die('Sorry direct access to this file not allowed');}

//Make the name even shorter
use Replica as R;

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=@R::esc($title);?></title>
    <meta name="description" contents="<?=@Replica::escape($meta_description);?>">
    <meta name="keywords"    contents="<?=@Replica::escape($meta_keywords);?>">
    <meta name="viewport"    content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="http://sharif.co/favicon.ico">
    <?=R::al('css',['css/bootstrap.min.css','css/styles.css','css/color.css']); ?>
    <?=@$css;?>
    <?=@$style;?>

</head>
<body>
<div class="wrapper center-text">
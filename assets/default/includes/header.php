<?php
if(!defined('REPLICA')) {die('Sorry direct access to this file not allowed');}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=@Replica::escape($title);?></title>
    <meta name="description" contents="<?=@Replica::escape($meta_description);?>">
    <meta name="keywords"    contents="<?=@Replica::escape($meta_keywords);?>">
    <meta name="viewport"    content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="http://sharif.co/favicon.ico">
    <?=Replica::assets_load('css',['css/bootstrap.min.css','css/styles.css','css/color.css']); ?>
</head>
<body>
<div class="wrapper center-text">
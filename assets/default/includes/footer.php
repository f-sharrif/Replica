<?php
if(!defined('REPLICA')) {die('Sorry direct access to this file not allowed');}

if($footer_widgets)
{
    echo Replica::include_partial('widgets','events-widget');
}

?>


<div class="text-center">
<footer>
    <ul class="links inline">
        <li><i class="fa fa-skype"></i> <a href="callto:sp01010011">SP01010011</a></li>
        <li><i class="fa fa-twitter"></i> <a href="https://twitter.com/aqsharif" target="_blank">@aqsharif</a></li>
        <li><i class="fa fa-github"></i> <a href="http://github.com/sp01010011" target="_blank">SP01010011</a></li>
        <li><small> Powered by  <a href="<?=Replica::get_system('system_url')?>" target="_blank"><?=Replica::get_system('system_name');?> <?=Replica::get_system('system_version');?></a></small></li>

    </ul>

</footer>
</div>
</div>

<?=Replica::al('js',['js/jquery.min.js','js/script.js']);?>
<?=@$js;?>
<?=@$script;?>
</body>
</html>

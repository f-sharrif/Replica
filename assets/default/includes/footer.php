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
        <li><i class="fa fa-skype"></i> <a href="callto:<?=Replica::get('socialmedia/skype/handle', Replica::conf());?>"><?=Replica::get('socialmedia/skype/handle', Replica::conf());?></a></li>

        <li><i class="fa fa-twitter"></i> <a href="<?=Replica::get('socialmedia/twitter/url', Replica::conf());?>" target="_blank"><?=Replica::get('socialmedia/twitter/handle', Replica::conf());?></a></li>

        <li><i class="fa fa-github"></i> <a href="<?=Replica::get('socialmedia/github/url', Replica::conf());?>" target="_blank"><?=Replica::get('socialmedia/github/handle', Replica::conf());?></a></li>

        <li><small> Powered by  <a href="<?=Replica::get('system/url', Replica::conf());?>" target="_blank"><?=Replica::get('system/name',Replica::conf());?>  <?=Replica::get('system/version',Replica::conf());?></a></small></li>

    </ul>

</footer>
</div>
</div>

<?=Replica::al('js',['js/jquery.min.js','js/script.js']);?>
<?=@$js;?>
<?=@$script;?>
</body>
</html>

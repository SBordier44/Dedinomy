<?php
namespace Dedinomy;

if (!defined('SECURE_LINK')) die('Direct access is not authorized');
$rss = simplexml_load_file('http://forum.nubox.fr/forum/8-les-news-informations.xml');
$Database = Database::getInstance();
$unvalidates = $Database->query('SELECT * FROM dn_messages WHERE online = 0')->rowCount();
$validated = $Database->query('SELECT * FROM dn_messages WHERE online = 1')->rowCount();
?>
<div class="">
    <div class="clearfix"></div>
    <div class="row top_tiles">
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-moon-o"></i>
                </div>
                <div class="count"><?php echo $unvalidates; ?></div>
                <h3>Hors Ligne</h3>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-check-square-o"></i>
                </div>
                <div class="count"><?php echo $validated; ?></div>
                <h3>En Ligne</h3>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-bar-chart-o"></i>
                </div>
                <div class="count"><?php echo($unvalidates + $validated); ?></div>
                <h3>Total</h3>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-sm-4 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Dernières News NuBOX</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="dashboard-widget-content">
                        <ul class="list-unstyled timeline widget">
                            <?php $i = 0;
                            foreach ($rss->channel->item as $item) {
                                if ($i >= 10) break;
                                $i++; ?>
                                <li>
                                    <div class="block">
                                        <div class="block_content">
                                            <h2 class="title">
                                                <a href="<?php echo $item->link; ?>"
                                                   target="_blank"><?php echo $item->title; ?></a>
                                            </h2>
                                            <div class="byline">
                                                Le <?php echo date('d/m/Y à H:i', strtotime($item->pubDate)); ?>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php $Plugins->hook_box(); ?>
    </div>
</div>
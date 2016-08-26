<?php
namespace Dedinomy;

if(!defined('SECURE_LINK')) die('Direct access is not authorized');
$paginate = Paginator::getInstance();
$paginate->init('SELECT * FROM dn_messages WHERE online = "1" ORDER BY id DESC', null);
?>
            <div class="">
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Dédicaces :: En Ligne</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content table-responsive">
                                <?php if($paginate->getData()->total):?>
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Pseudo</th>
                                        <th>Message</th>
                                        <th>IP</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($paginate->getData()->data as $data):?>
                                    <tr>
                                        <th scope="row"><?php echo Utils::convertDate($data->created); ?></th>
                                        <td><?php echo ucfirst($data->nickname); ?></td>
                                        <td><?php echo ucfirst($data->message); ?></td>
                                        <td><?php echo ($data->ip)?$data->ip:'<small>Inconnu</small>'; ?></td>
                                        <td>
                                            <button type="button" onclick="location.href='?action=dedi_edit&id=<?php echo $data->id;?>'" class="btn btn-round btn-info btn-xs">Editer</button>
                                            <button type="button" class="btn btn-round btn-danger btn-xs dediDelete" id="<?php echo $data->id; ?>">Supprimer</button>
                                            <?php if($data->ip){?>
                                                <button type="button" class="btn btn-round btn-warning btn-xs dediBanIp" value="<?php echo $data->ip; ?>">Bannir IP</button>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php else: ?>
                                    <div class="alert alert-info">Vous n'avez actuellement aucune dédicace en ligne</div>
                                <?php endif; ?>
                                <?php echo $paginate->createLinks(10); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
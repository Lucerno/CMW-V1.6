<header class="heading-pagination">
	<div class="container-fluid">
		<h1 class="text-uppercase wow fadeInRight" style="color:white;">Support</h1>
	</div>
</header>
<section class="layout" id="page">
<div class="container">
	<div class="text-center">
		<h4 class="text-primary"><i class="fa fa-user-md"></i> Support communautaire</h4>
		<p>Postez des tickets, lisez ceux des autres, répondez à la communauté et discutez avec l'équipe du serveur !</p>
	</div>
	<div class="card">
	  <div class="card-block" style="padding:15px;">
				<table class="table table-bordered">
					<thead class="thead-inverse bg-primary">
						<tr>
							<?php if($_Joueur_['rang'] == 1 OR $_PGrades_['PermsDefault']['support']['displayTicket'] == true) { echo '<th style="text-align: center;">Visuel</th>'; } ?>
							<th style="text-align: center;">Pseudo</th>
							<th style="text-align: center;">Titre</th>
							<th style="text-align: center;">Date</th>
							<th style="text-align: center;">Action</th>
                            <th style="text-align: center;">Status </th>
							<?php if($_Joueur_['rang'] == 1 OR $_PGrades_['PermsDefault']['support']['closeTicket'] == true) { echo '<th style="text-align: center;">Modification</th>'; } ?>
						</tr>
					</thead>
					<tbody>
					<?php $j = 0;
					while($tickets = $ticketReq->fetch()) { ?>
						<tr>
						    <?php if($tickets['ticketDisplay'] == 0 OR $tickets['auteur'] == $_Joueur_['pseudo'] OR $_Joueur_['rang'] == 1 OR $_PGrades_['PermsDefault']['support']['displayTicket'] == true) {
						    if($_Joueur_['rang'] == 1 OR $_PGrades_['PermsDefault']['support']['displayTicket'] == true) { ?>
						    <td class="align-middle">
						        <?php if($tickets['ticketDisplay'] == "0") {
						                echo '<span><i class="glyphicon glyphicon-eye-open"></i> Public</span>';
						            } else {
								        echo '<span ><i class="glyphicon glyphicon-eye-close"></i> Privé</span>';
								} ?>
							</td>
							<?php } ?>

							<td class="text-center align-middle">
								<a href="index.php?&page=profil&profil=<?php echo $tickets['auteur'] ?>"><img class="icon-player-topbar" src="http://api.craftmywebsite.fr/skin/face.php?u=<?php echo $tickets['auteur']; ?>&s=32&v=front" /> <?php echo $tickets['auteur'] ?></a>
							</td>
						
							<td class="text-center align-middle">
								<?php echo $tickets['titre'] ?>​
							</td>
						
							<td class="text-center align-middle">
								<?php echo $tickets['jour']. '/' .$tickets['mois']. ' à ' .$tickets['heure']. ':' .$tickets['minute']; ?>
							</td>
						
							<td class="text-center align-middle">
								<a class="btn btn-primary btn-sm" data-toggle="modal" href="#" data-target="#<?php echo $tickets['id']; ?>Slide">
									Voir <i class="fa fa-eye"></i>
								</a>
							</td>
                            
                            <td class="text-center align-middle">
                                <?php
                                    $ticketstatus = $tickets['etat'];
                                    if($ticketstatus == "1"){
                                        echo '<button class="btn btn-success">Résolu <span class="glyphicon glyphicon-ok"></span></button>';
                                    } else {
                                        echo '<button class="btn btn-danger">Non Résolu <span class="glyphicon glyphicon-remove"></span></button>';
                                    }
                                ?>
                            </td>

							<?php if($_Joueur_['rang'] == 1 OR $_PGrades_['PermsDefault']['support']['closeTicket'] == true) { ?>
								<td style="text-align: center;">
									<form class="form-horizontal default-form" method="post" action="?&action=ticketEtat&id=<?php echo $tickets['id']; ?>">
										<?php if($tickets['etat'] == 0){ 
											echo '<button type="submit" name="etat" class="btn btn-warning" value="1" />Fermer le ticket</button>';
										}else{
											echo '<button type="submit" name="etat" class="btn btn-warning" value="0" />Ouvrir le ticket</button>';
										} ?>
									</form>
								</td>
							<?php }
							} ?>
						</tr>
						
					<?php if($tickets['ticketDisplay'] == "0" OR $tickets['auteur'] == $_Joueur_['pseudo'] OR $_Joueur_['rang'] == 1 OR $_PGrades_['PermsDefault']['support']['displayTicket'] == true) { ?>
					<!-- Modal -->
					<div class="modal fade" id="<?php echo $tickets['id']; ?>Slide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-support">
							<div class="modal-content">
							
								<div class="modal-header" style="background-color: #0c84e4;">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title" id="myModalLabel" style="color: white;" ><?php echo $tickets['titre']; ?></h4>
                                <?php
                                    $ticketstatus = $tickets['etat'];
                                    if($ticketstatus == "1"){
                                        echo '<div class="ribbon-wrapper-green"><div class="ribbon-green">Résolu !</div></div>';
                                    } else {
                                        echo '';
                                    }
                                ?>
								</div>
								
								<div class="modal-body">
									<p class="corp-ticket"><?php 
									unset($message);
									$message = espacement($tickets['message']);
									$message = BBCode($message);
									echo $message; ?></p>
									<span class="badge pull-right">Ticket de : <img src="http://api.craftmywebsite.fr/skin/face.php?u=<?php echo $tickets['auteur']; ?>&s=16&v=front" alt="none" /> <?php echo $tickets['auteur']; ?></span>
									</br>
									<hr>
									
									<?php
									$commentaires = 0;
									if(isset($ticketCommentaires[$tickets['id']]))
									{
										echo '<h3 class="ticket-commentaire-titre"><center>' .count($ticketCommentaires[$tickets['id']]). ' Commentaires</center></h3>';
										for($i = 0; $i < count($ticketCommentaires[$tickets['id']]); $i++)
										{
											$get_idComm = $bddConnection->prepare('SELECT id FROM cmw_support_commentaires WHERE auteur LIKE :auteur AND id_ticket LIKE :id_ticket');
											$get_idComm->bindParam(':auteur', $ticketCommentaires[$tickets['id']][$i]['auteur']);
											$get_idComm->bindParam(':id_ticket', $tickets['id']);
											$get_idComm->execute();
											$req_idComm = $get_idComm->fetch();
									?>
									<div class="panel panel-default">
										<div class="panel-body">
    										<div class="ticket-commentaire">
											<div class="left-ticket-commentaire">
												<span class="img-ticket-commentaire"><img src="http://api.craftmywebsite.fr/skin/face.php?u=<?php echo $ticketCommentaires[$tickets['id']][$i]['auteur']; ?>&s=32&v=front" alt="none" /></span>
												<span class="desc-ticket-commentaire">
													<span class="ticket-commentaire-auteur"><?php echo $ticketCommentaires[$tickets['id']][$i]['auteur']; ?></span>
													<span class="ticket-commentaire-date"><?php echo 'Le ' .$ticketCommentaires[$tickets['id']][$i]['jour']. '/' .$ticketCommentaires[$tickets['id']][$i]['mois']. ' à ' .$ticketCommentaires[$tickets['id']][$i]['heure']. ':' .$ticketCommentaires[$tickets['id']][$i]['minute']; ?></span>
													<?php if(isset($_Joueur_)) { ?>
													    <span class="ticket-commentaire-action pull-right">
							                                <div class="dropdown">
								                                <a type="button" class="btn btn-info collapsed" data-toggle="dropdown" style="font-size: 10px;">Action <b class="caret"></b></a>
								                                <ul class="dropdown-menu">
									                                <?php if($ticketCommentaires[$tickets['id']][$i]['auteur'] == $_Joueur_['pseudo'] OR $_Joueur_['rang'] == 1 OR $_PGrades_['PermsDefault']['support']['deleteMemberComm'] == true) {
										                                echo '<li><a href="?&action=delete_support_commentaire&id_comm='.$req_idComm['id'].'&id_ticket='.$tickets['id'].'&auteur='.$ticketCommentaires[$tickets['id']][$i]['auteur'].'">Supprimer</a></li>';
									                                } if($ticketCommentaires[$tickets['id']][$i]['auteur'] == $_Joueur_['pseudo'] OR $_Joueur_['rang'] == 1 OR $_PGrades_['PermsDefault']['support']['editMemberComm'] == true) {
									                                	echo '<li><a href="#editComm-'.$req_idComm['id'].'" data-toggle="modal" data-target="#editComm-'.$req_idComm['id'].'" >Editer</a></li>';
									                                }?>
								                                </ul>
							                                </div>
						                                </span>
						                            <?php } ?>
												</span>
												
											</div>
											<div class="right-ticket-commentaire">
												<?php unset($message);
												$message = espacement($ticketCommentaires[$tickets['id']][$i]['message']);
												$message = BBCode($message);
												echo $message;  ?>
											</div>
										</div>
										</div>
									</div>
									
									

									<?php
										}
									}		
									else
										echo '<h3 class="ticket-commentaire-titre">0 Commentaire</h3>';
									?>
									
									
									
								</div>
								<?php
								if($tickets['etat'] == "0"){
									echo '<div class="modal-footer">
											<form action="?&action=post_ticket_commentaire" method="post">
												<input type="hidden" name="id" value="'.$tickets['id'].'" />
												<textarea name="message" class="form-control" rows="3"></textarea>
												</br>
												<button type="submit" class="btn btn-primary">Commenter</button>
											</form>
										  </div>';
								} else {
									echo '<div class="modal-footer">
											<form action="" method="post">
												<textarea style="text-align: center;"name="message" class="form-control" rows="2" placeholder="Ticket résolu ! Merci de contacter un administrateur pour réouvrir votre ticket." disabled></textarea>
											</form>
										  </div>';
								}
								?>
							</div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->

					<?php if($ticketCommentaires[$tickets['id']][$i]['auteur'] == $_Joueur_['pseudo'] OR $_Joueur_['rang'] == 1 OR $_PGrades_['PermsDefault']['support']['editMemberComm'] == true) {
						for($i = 0; $i < count($ticketCommentaires[$tickets['id']]); $i++) {
							$get_idComm = $bddConnection->prepare('SELECT id FROM cmw_support_commentaires WHERE auteur LIKE :auteur AND id_ticket LIKE :id_ticket');
							$get_idComm->bindParam(':auteur', $ticketCommentaires[$tickets['id']][$i]['auteur']);
							$get_idComm->bindParam(':id_ticket', $tickets['id']);
							$get_idComm->execute();
							$req_idComm = $get_idComm->fetch(); ?>
					<div class="modal fade" id="editComm-<?php echo $req_idComm['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editComm">
					    <form method="POST" action="?&action=edit_support_commentaire&id_comm=<?php echo $req_idComm['id']; ?>&id_ticket=<?php echo $tickets['id']; ?>&auteur=<?php echo $ticketCommentaires[$tickets['id']][$i]['auteur']; ?>">
				        <div class="modal-dialog modal-lg" role="document">
					        <div class="modal-content">
						        <div class="modal-header">
							        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							        <h4 class="modal-title text-center" id="editComm">Edition du commentaire</h4>
						        </div>
						        <div class="modal-body">
						            <div class="col-lg-12 text-center">
						            	<div class="row">
						            		<textarea name="editMessage" class="form-control" rows="3" style="resize: none;"><?php echo $ticketCommentaires[$tickets['id']][$i]['message']; ?></textarea>
						            	</div>
						            </div>
						        </div>
						        <div class="modal-footer">
						        	<div class="col-lg-12 text-center">
						        		<div class="row">
						        			<button type="submit" class="btn btn-primary">Valider !</button>
						        		</div>
						        	</div>
						        </div>
						    </div>
						</div>
						</form>
				    </div>
				    <?php }
				       }
				    }
					$j++; } ?>
					</tbody>
			</table>
	</div>
				<div class="card-footer">
				<?php
					if(!isset($_Joueur_)) 
						echo '<a data-toggle="modal" data-target="#ConnectionSlide" class="btn btn-warning btn-block" ><span class="glyphicon glyphicon-user"></span> Se connecter pour ouvrir un ticket</a>'; 
					else 
					{
				?>
				<a data-toggle="collapse" data-parent="#ticketCree" href="#ticketCree" class="btn btn-primary"><i class="fa fa-pencil-square-o"></i> Poster un ticket !</a>
				</div>
		  </div>

				<div class="collapse" id="ticketCree">
					<div class="card">
						<form action="?&action=post_ticket" method="post">
							<div class="card-block">
								<div class="row">
									<div class="col-sm-8">
										<div class="form-group">
											<label class="control-label">Sujet</label>
											<div class="form-group">
												<div class="input-group">
													<div class="input-group-addon"><i class="fa fa-pencil"></i></div>
													<input type="text" class="form-control" name="titre" placeholder="Sujet">
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label for="exampleSelect1">Visibilité</label>
											<select class="form-control" name="ticketDisplay">
												<option value="0">Publique</option>
												<option value="1">Privée</option>
											</select>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="message">Description détaillée</label>
									<textarea class="form-control" id="message" name="message" placeholder="Description détaillée de votre problème" rows="3"></textarea>
								</div>
							</div>
							<div class="card-footer">
								<button type="submit" class="btn btn-success champ valider pull-right">Envoyer</button>
							</div>
						</form>
					</div>
				</div>
				<?php } ?>
	</div>
</section>
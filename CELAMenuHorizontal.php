<?php
/**
?>
<!-- start: Header Menú Horizontal-->
		<div role="navigation" class="navbar navbar-inverse navbar-fixed-top">
			<div class="container-fluid">
				<div class="navbar-header">
							else{
								if($registroMenu['MenuTipo']==1) {
				?>
								<li role="presentation" >
									<a role="menuitem" tabindex="-1" href="<?php print $registroMenu['ReferenciaMenu']; ?>" title="<?php print $registroMenu['MenuDescripcion']; ?>" data-placement="bottom">
									<i class="<?php print $registroMenu['MenuIcono']; ?>"></i>&nbsp;
									<?php print $registroMenu['MenuEtiqueta']; ?>
									</a>
								</li>				
				<?php
								}
							}
							$registroMenu=$resultadoMenu->fetch_assoc();
							$contadorCategoria++;
						}while($categoriaDeMenu==$registroMenu['MenuCategoria']);
				?>
					else{
						$registroMenu=$resultadoMenu->fetch_assoc();
						$contadorCategoria++;
					}
				?>
						</li>
		<?php
					else{
		<!-- Modal Acerca De -->
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											?> 
						?>
							<img class="img-circle" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=100"
							<br />
							<h4><span class="label label-primary"><?php print "Usuario: ".$_SESSION['CELA_Usuario'.$_SESSION['CELA_Aleatorio']]; ?></span></h4>
							<br />
							<input type="password" class="form-control" name="txtcontrasena" id="txtcontrasena" type="password" autocomplete="off" placeholder="Contrase&ntilde;a" /><br />
							<label class="label error" id="Message" style="font-size: 12pt;" ></label>
							<hr />
							<span class="clear-fix"></span>
							<button class="btn btn-lg btn-primary btn-block" id="UnLock" name="UnLock">Desbloquear</button>
					</div>
					<div class="modal-footer">
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
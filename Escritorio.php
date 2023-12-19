<?phpheader("Location: ClientsLeer.php");
require_once('lib/Conexion.php');require_once('lib/Funciones.php');require_once('lib/Seguridad.php');?><!DOCTYPE html><html lang="es">	<?php include 'CELAHead.php'; ?>	<body>		<?php include 'CELAMenuHorizontal.php'; ?>		<div class="container-fluid">			<div class="row">			<?php include 'CELAMenuVertical.php'; ?>				<div id="main-container" class="<?php print $registrosMenu>0?'col-sm-offset-3 col-sm-9 col-md-offset-2 col-md-10':'col-sm-12 col-md-12'; ?> main">					<!--div class="row">						<?php #include 'CELARuta.php'; ?>					</div -->					<div class="row">
						<div class="col-sm-12 col-md-12">						<div class="row panel panel-primary">							<div class="panel-heading">
							<div class="row">								<div class="col-md-12">									<div class="box-header col-xs-6 text-left">										<strong>											<span style="font-size: 18pt;">Escritorio</span>										</strong>									</div>									<div class="box-icon col-xs-6 text-right">										<a data-intro="Ayuda general" data-position="left" href="#" class="btn-help btn btn-default" title="Ayuda">
											<i class="fa fa-question"></i>
										</a>
									</div>
								</div>
							</div>
							</div>							<div class="box-content panel-body">							</div>							<div class="panel-footer text-right">								<a class="btn btn-danger" href="<?php print isset($_SERVER['HTTP_REFERER'])?substr($_SERVER['HTTP_REFERER'],strripos($_SERVER['HTTP_REFERER'],"/")+1,strlen($_SERVER['HTTP_REFERER'])):'Escritorio.php'; ?>" title="ir atr&aacute;s"  data-intro="Regresa al formulario anterior" data-position="left"><i class="fa fa-arrow-left"></i>&nbsp; ir atr&aacute;s</a>							</div>						</div>
						</div>					</div>					<hr>					<span class="clearfix"></span>					<?php include 'CELAPie.php'; ?>				</div>			</div>		</div>		<?php include 'CELAJavascript.php'; ?>	</body></html>
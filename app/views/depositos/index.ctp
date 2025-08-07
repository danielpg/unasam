<div class="depositos index">
	<h2><?php __('Depositos');?></h2>


<?php if(in_array(Credentials::get("__credentials.Login.role_id"),array(1,3))): ?>
<span class="lbutton"><?php echo $this->Html->link(__('Agregar Nuevo', true), array('action' => 'add')); ?></span>
<?php endif; ?>

<style>
.ptt{width:90px;text-align:right}
</style>

<?php
  echo $alaxosForm->create('Deposito', array('controller' => 'depositos', 'url' => $this->passedArgs));
?>

	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('entidad_id');?></th>
	
			<th><?php echo $this->Paginator->sort('monto');?></th>
		    <th><?php echo $this->Paginator->sort('cuenta');?></th>
		    <th><?php echo $this->Paginator->sort('codigo_voucher');?></th>
			<th><?php echo $this->Paginator->sort('Fecha Deposito','periodo');?></th>
			<th><?php echo $this->Paginator->sort('Creado','created');?></th>
<?php if(in_array(Credentials::get("__credentials.Login.role_id"),array(1,3))): ?>
			<th class="actions"><?php __('Acciones');?></th>

<?php endif; ?>
	</tr>
	<tr>
		<td>    <?php
    echo $this->AlaxosForm->filter_field('entidad_id'); //-> print a text field
    ?></td>
	<td>&nbsp;</td>
		<td>    <?php
    echo $this->AlaxosForm->filter_field('cuenta'); //-> print a text field
    ?></td>

		<td> <?php
    echo $this->AlaxosForm->filter_field('codigo_voucher'); //-> print a text field
    ?></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>
			 <span class="submit"><input type="submit" value="Filtrar" /></span>

			<div style="margin-top:8px"> <span class="lbutton"><a href="#" onclick="limpiar_filtro();">Limpiar</a></span></div>

		</td>

	</tr>
	




	<?php
	$i = 0;
	foreach ($depositos as $deposito):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $deposito['Entidad']['nombre'].' - '.$deposito['Entidad']['codigo']; ?>&nbsp;</td>

		<td><div class="ptt">S/.&nbsp;<?php echo mformat($deposito['Deposito']['monto']); ?>&nbsp;</div></td>
		<td>
			<?php echo $deposito['Deposito']['cuenta']; ?>
		</td>
		<td>
			<?php echo $deposito['Deposito']['codigo_voucher']; ?>
		</td>
		<td>
			<?php 
				list($y,$m,$d) = explode("-",$deposito['Deposito']['periodo']); 
				//echo $m."/".$y;
				echo $d."/".$m."/".$y;
			?>
		</td>
		<td>
			<?php echo $deposito['Deposito']['created']; ?>
		</td>
<?php if(in_array(Credentials::get("__credentials.Login.role_id"),array(1,3))): ?>
		<td class="actions">
			<?php if(!empty($deposito['Deposito']['archivo'])): ?>	
			<a target="_blank" href="<?php echo $this->Html->filepath($deposito['Deposito'],"uploads","archivo") ?>">Archivo</a>
			<?php endif; ?>
			<?php //echo $this->Html->link(__('View', true), array('action' => 'view', $deposito['deposito']['id'])); ?>
			<?php echo $this->Html->link(__('Editar', true), array('action' => 'edit', $deposito['Deposito']['id'])); ?>
			<?php echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $deposito['Deposito']['id']), null, sprintf(__('Esta seguro de querer eliminar # %s?', true), $deposito['Deposito']['codigo_voucher'])); ?>
		</td>
<?php endif; ?>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Pagina %page% de %pages%, mostrando %current% filas de un total de %count%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('Atras', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('Siguiente', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
</form>

<script type="text/javascript">
function limpiar_filtro(){
	$("#DepositoEntidadId").val("");	
	$("#DepositoCuenta").val("");	
	$("#DepositoCodigoVoucher").val("");	

	return false;
}
</script>



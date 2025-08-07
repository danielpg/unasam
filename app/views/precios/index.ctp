<div class="precios index">
	<h2><?php __('Precios');?></h2><span class="lbutton"><?php echo $this->Html->link(__('Agregar Nuevo', true), array('action' => 'add')); ?></span>

<style>
.ptt{width:90px;text-align:right}
</style>

<?php
echo $alaxosForm->create('Precio', array('controller' => 'registros', 'url' => $this->passedArgs));
?>

	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('codigo');?></th>
	
			<th><?php echo $this->Paginator->sort('nombre');?></th>
		    <th><?php echo $this->Paginator->sort('rubro_id');?></th>
			<th><?php echo $this->Paginator->sort('estado');?></th>
			<th><?php echo $this->Paginator->sort('monto');?></th>
			<th><?php echo $this->Paginator->sort('Actualizado','modified');?></th>
			<th class="actions"><?php __('Acciones');?></th>
	</tr>
	<tr>
		<td>    <?php
    echo $this->AlaxosForm->filter_field('codigo'); //-> print a text field
    ?></td>
		<td>    <?php
    echo $this->AlaxosForm->filter_field('nombre'); //-> print a text field
    ?></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td> <?php
    echo $this->AlaxosForm->filter_field('monto'); //-> print a text field
    ?></td>
		<td> &nbsp;  </td>
		<td>
			 <span class="submit"><input type="submit" value="Filtrar" /></span>


<div style="margin-top:8px"> <span class="lbutton"><a href="#" onclick="limpiar_filtro();">Limpiar</a></span></div>
		</td>

	</tr>
	




	<?php
	$i = 0;
	foreach ($precios as $precio):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $precio['Precio']['codigo']; ?>&nbsp;</td>

		<td><?php echo $precio['Precio']['nombre']; ?>&nbsp;</td>
		<td>
			<?php echo $precio['Rubro']['nombre']; ?>
		</td>
		<td><?php echo ($precio['Precio']['estado']==1 ? "Activo":"Inactivo" ); ?>&nbsp;</td>
		<td><div class="ptt">S/. <?php echo mformat($precio['Precio']['monto']); ?>&nbsp;</div></td>
		<td><?php echo $precio['Precio']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php //echo $this->Html->link(__('View', true), array('action' => 'view', $precio['Precio']['id'])); ?>
			<?php echo $this->Html->link(__('Editar', true), array('action' => 'edit', $precio['Precio']['id'])); ?>
			<?php echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $precio['Precio']['id']), null, sprintf(__('Esta seguro de querer eliminar # %s?', true), $precio['Precio']['codigo'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>


</form>

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

<script type="text/javascript">
function limpiar_filtro(){
	$("#PrecioCodigo").val("");	
	$("#PrecioNombre").val("");	
	$("#PrecioMonto").val("");	
	return false;
}
</script>


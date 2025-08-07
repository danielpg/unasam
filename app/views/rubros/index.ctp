<div class="rubros index">
	<h2><?php __('Clasificadores');?></h2><span class="lbutton"><?php echo $this->Html->link(__('Agregar Nuevo', true), array('action' => 'add')); ?></span>



<?php
echo $alaxosForm->create('Rubro', array('controller' => 'rubros', 'url' => $this->passedArgs));
?>

	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('codigo');?></th>

			<th><?php echo $this->Paginator->sort('nombre');?></th>
			<th><?php echo $this->Paginator->sort('categoria_id');?></th>
			<th><?php echo $this->Paginator->sort('estado');?></th>

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
		<td>&nbsp;</td>
		<td>
			 <span class="submit"><input type="submit" value="Filtrar" /></span>

			<div style="margin-top:8px"> <span class="lbutton"><a href="#" onclick="limpiar_filtro();">Limpiar</a></span></div>

		</td>

	</tr>


	<?php
	$i = 0;
	foreach ($rubros as $rubro):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $rubro['Rubro']['codigo']; ?>&nbsp;</td>
	
		<td><?php echo $rubro['Rubro']['nombre']; ?>&nbsp;</td>
	<td><?php echo $rubro['Categoria']['nombre']; ?>&nbsp;</td>	
		<td><?php echo ($rubro['Rubro']['estado']==1 ? "Activo":"Inactivo" ); ?>&nbsp;</td>

		<td><?php echo $rubro['Rubro']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php //echo $this->Html->link(__('View', true), array('action' => 'view', $rubro['Rubro']['id'])); ?>
			<?php echo $this->Html->link(__('Editar', true), array('action' => 'edit', $rubro['Rubro']['id'])); ?>
			<?php echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $rubro['Rubro']['id']), null, sprintf(__('Esta seguro de querer eliminar # %s?', true), $rubro['Rubro']['codigo'])); ?>
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
<div class="actions">
	<h3><?php __('Acciones'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Rubro', true), array('action' => 'add')); ?></li>
	</ul>
</div>


<script type="text/javascript">
function limpiar_filtro(){
	$("#RubroCodigo").val("");	
	$("#RubroNombre").val("");	

	return false;
}
</script>


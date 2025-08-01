<div class="personas index">
	<h2><?php __('Personas y Empresas');?></h2>

<span class="lbutton"><?php echo $this->Html->link(__('Agregar Persona', true), array('action' => 'add?tipo='.TPERSONA)); ?></span>
<span class="lbutton"><?php echo $this->Html->link(__('Agregar Empresa', true), array('action' => 'add?tipo='.TEMPRESA)); ?></span>
<span class="lbutton"><?php echo $this->Html->link(__('Agregar Estudiante', true), array('action' => 'add?tipo='.TESTUDIANTE)); ?></span>
<span class="lbutton"><?php echo $this->Html->link(__('Agregar Docente', true), array('action' => 'add?tipo='.TDOCENTE)); ?></span>
<span class="lbutton"><?php echo $this->Html->link(__('Agregar Empleado', true), array('action' => 'add?tipo='.TEMPLEADO)); ?></span>
<span class="lbutton"><?php echo $this->Html->link(__('Agregar Egresado', true), array('action' => 'add?tipo='.TEGRESADO)); ?></span>

	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort("Nombre o Razon Social",'nombre');?></th>
			<th><?php echo $this->Paginator->sort("DNI o RUC",'dni_ruc');?></th>
			<th><?php echo $this->Paginator->sort('Creado','created');?></th>
			<th class="actions"><?php __('Acciones');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($personas as $persona):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $persona['Persona']['nombre']; ?>&nbsp;</td>
		<td><?php echo $persona['Persona']['dni_ruc']; ?>&nbsp;</td>
		<td><?php echo $persona['Persona']['created']; ?>&nbsp;</td>
		<td class="actions">
			<?php //echo $this->Html->link(__('View', true), array('action' => 'view', $persona['Persona']['id'])); ?>
			<?php echo $this->Html->link(__('Editar', true), array('action' => 'edit', $persona['Persona']['id'])); ?>
			<?php echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $persona['Persona']['id']), null, sprintf(__('Esta seguro de querer eliminar # %s?', true), $persona['Persona']['id'])); ?>
		</td>
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
<div class="actions">
	<h3><?php __('Acciones'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Persona', true), array('action' => 'add')); ?></li>
	</ul>
</div>

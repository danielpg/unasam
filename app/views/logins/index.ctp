<div class="logins index">
	<h2><?php __('Usuarios');?></h2>

<span class="lbutton"><?php echo $this->Html->link(__('Agregar Nuevo', true), array('action' => 'add')); ?></span>

	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort("Rol",'role_id');?></th>
			<th><?php echo $this->Paginator->sort("Centro de Costo",'oficina_id');?></th>
			<th><?php echo $this->Paginator->sort("Nombre",'nombres');?></th>
			<th><?php echo $this->Paginator->sort("Usuario",'username');?></th>
			<th><?php echo $this->Paginator->sort("Estado",'estado');?></th>
			<th><?php echo $this->Paginator->sort("Modificado",'modified');?></th>
			<th class="actions"><?php __('Acciones');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($logins as $login):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $login['Role']['nombre']; ?>&nbsp;</td>
		<td>
		<?php if($login['Role']['id'] != 1): ?>
			<?php echo $login['Oficina']['nombre']; ?>
		<?php endif; ?>
			&nbsp;
		</td>
		<td><?php echo $login['Login']['nombres']; ?>&nbsp;</td>
		<td><?php echo $login['Login']['username']; ?>&nbsp;</td>
		<td><?php echo ($login['Login']['estado']==1 ? "Activo":"Inactivo" ); ?>&nbsp;</td>
		<td><?php echo $login['Login']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php //echo $this->Html->link(__('Ver', true), array('action' => 'view', $login['Login']['id'])); ?>
			<?php echo $this->Html->link(__('Editar', true), array('action' => 'edit', $login['Login']['id'])); ?>
			<?php echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $login['Login']['id']), null, sprintf(__('Esta seguro de querer eliminar # %s?', true), $login['Login']['id'])); ?>
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
		<?php echo $this->Paginator->prev('<< ' . __('atras', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('siguiente', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="actions">
	<h3><?php __('Acciones'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Nuevo Login', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('Listado Registros', true), array('controller' => 'registros', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Registro', true), array('controller' => 'registros', 'action' => 'add')); ?> </li>
	</ul>
</div>

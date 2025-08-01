<style>
table tr td .tar{text-align:right}

.actr{text-align:center}
</style>
<div class="recibos index">

<div>
	<div style="float:left;">
		<h2><?php __('Recibos');?></h2>


<?php if(in_array(Credentials::get("__credentials.Login.role_id"),array(1,3))): ?>
		<span class="lbutton"><?php echo $this->Html->link(__('Agregar Nuevo', true), array('action' => 'add')); ?></span>
<?php endif;?>


	</div>
	<div style="float:right;width:430px">


	</div>
	<div style="clear:both"></div>
</div>

	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('precio_id');?></th>
			<th><?php echo $this->Paginator->sort('nombres');?></th>
			<th style="text-align:right"><?php echo $this->Paginator->sort('monto');?></th>
			<th><?php echo $this->Paginator->sort('cantidad');?></th>
			<th><?php echo $this->Paginator->sort('Creado','created');?></th>

			<th class="actions"><?php __('Acciones');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($recibos as $recibo):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $recibo['Recibo']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $recibo['Precio']['nombre']." (S/.".mformat($recibo['Precio']['monto']).")"; ?>
		</td>
		<td><?php echo $recibo['Recibo']['nombres']; ?>&nbsp;</td>
		<td class="tar">S/.&nbsp;<?php echo mformat($recibo['Recibo']['monto']); ?>&nbsp;</td>
		<td align="center"><div class="actr">(<?php echo $recibo['Recibo']['cantidad']; ?>)</div></td>
		<td><?php echo $html->cdate($recibo['Recibo']['created']); ?>&nbsp;</td>

		<td class="actions">
			<?php echo $this->Html->link(__('Ver', true), array('action' => 'view', $recibo['Recibo']['id'])); ?>
			<?php //echo $this->Html->link(__('Imp', true), array('action' => 'imp', $recibo['Recibo']['id'])); ?>
			<a href="#" onclick="return printIframe(<?php echo $recibo['Recibo']['id'] ?>,'printframe')">Imp</a>
			<?php if(date("Y-m-d",strtotime($recibo['Recibo']['created'])) == date("Y-m-d")) echo $this->Html->link(__('Editar', true), array('action' => 'edit', $recibo['Recibo']['id'])); ?>
			<?php if(date("Y-m-d",strtotime($recibo['Recibo']['created'])) == date("Y-m-d")) echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $recibo['Recibo']['id']), null, sprintf(__('Esta seguro de querer eliminar # %s?', true), $recibo['Recibo']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Recibo', true), array('action' => 'add')); ?></li>
	</ul>
</div>

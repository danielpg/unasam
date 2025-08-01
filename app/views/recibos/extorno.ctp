<style>
table tr td .tar{text-align:right}
</style>
<div class="recibos index">
	<h2>Extornos</h2>

	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('precio_id');?></th>
			<th><?php echo $this->Paginator->sort('nombres');?></th>
			<th style="text-align:right"><?php echo $this->Paginator->sort('monto');?></th>
			<th><?php echo $this->Paginator->sort('cantidad');?></th>
			<th><?php echo $this->Paginator->sort('Creado','created');?></th>

<?php if(in_array(Credentials::get("__credentials.Login.role_id"),array(1,3))): ?>
			<th class="actions"><?php __('Acciones');?></th>
<?php endif; ?>
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
		<td class="tar">S/. <?php echo mformat($recibo['Recibo']['monto']); ?>&nbsp;</td>
		<td>(<?php echo $recibo['Recibo']['cantidad']; ?>)&nbsp;</td>
		<td><?php echo $html->cdate($recibo['Recibo']['created']); ?>&nbsp;</td>
<?php if(in_array(Credentials::get("__credentials.Login.role_id"),array(1,3))): ?>
		<td class="actions">
			<?php echo $this->Html->link(__('Ver', true), array('action' => 'view', $recibo['Recibo']['id'])); ?>
			<?php echo $this->Html->link(__('Imp', true), array('action' => 'imp', $recibo['Recibo']['id'])); ?>
			<?php //echo $this->Html->link(__('Editar', true), array('action' => 'edit', $recibo['Recibo']['id'])); ?>
			<?php if(date("Y-m-d",strtotime($recibo['Recibo']['created'])) == date("Y-m-d")) echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $recibo['Recibo']['id']), null, sprintf(__('Esta seguro de querer eliminar # %s?', true), $recibo['Recibo']['id'])); ?>
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

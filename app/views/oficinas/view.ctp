<div class="oficinas view">
<h2><?php  __('Oficina');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $oficina['Oficina']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Nombre'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $oficina['Oficina']['nombre']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Estado'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $oficina['Oficina']['estado']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Creado'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $oficina['Oficina']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Actualizado'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $oficina['Oficina']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Acciones'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Oficina', true), array('action' => 'edit', $oficina['Oficina']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Eliminar Oficina', true), array('action' => 'delete', $oficina['Oficina']['id']), null, sprintf(__('Esta seguro de querer eliminar # %s?', true), $oficina['Oficina']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Oficinas', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Oficina', true), array('action' => 'add')); ?> </li>
	</ul>
</div>

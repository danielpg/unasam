<div class="roles view">
<h2><?php  __('Role');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $role['Role']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Nombre'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $role['Role']['nombre']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Codigo'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $role['Role']['codigo']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Acciones'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Editarar Role', true), array('action' => 'edit', $role['Role']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Eliminar Role', true), array('action' => 'delete', $role['Role']['id']), null, sprintf(__('Esta seguro de querer eliminar # %s?', true), $role['Role']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Listado Roles', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Role', true), array('action' => 'add')); ?> </li>
	</ul>
</div>

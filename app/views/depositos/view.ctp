<div class="precios view">
<h2><?php  __('Precio');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $precio['Precio']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Rubro'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($precio['Rubro']['nombre'], array('controller' => 'rubros', 'action' => 'view', $precio['Rubro']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Nombre'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $precio['Precio']['nombre']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Codigo'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $precio['Precio']['codigo']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Estado'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $precio['Precio']['estado']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Creado'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $precio['Precio']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Actualizado'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $precio['Precio']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Acciones'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Precio', true), array('action' => 'edit', $precio['Precio']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Eliminar Precio', true), array('action' => 'delete', $precio['Precio']['id']), null, sprintf(__('Esta seguro de querer eliminar # %s?', true), $precio['Precio']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Precios', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Precio', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Rubros', true), array('controller' => 'rubros', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Rubro', true), array('controller' => 'rubros', 'action' => 'add')); ?> </li>
	</ul>
</div>

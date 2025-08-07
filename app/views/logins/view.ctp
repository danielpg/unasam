<span class="lbutton"><a href="#" onclick="javascript:history.back(1);return false;">Regresar</a></span>
<div class="logins view">
<h2><?php  __('Login');?></h2>



	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $login['Login']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Registro'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($login['Registro']['fullname'], array('controller' => 'registros', 'action' => 'view', $login['Registro']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Rol'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $login['Role']['nombre']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Usuario'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $login['Login']['username']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Estado'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo ($login['Login']['login_status']==1 ? "Activo":"Inactivo" ); ?>
			&nbsp;
		</dd>
		<!--dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Last Ip'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $login['Login']['last_ip']; ?>
			&nbsp;
		</dd-->
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Creado'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $login['Login']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modificado'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $login['Login']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Acciones'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Editarar Login', true), array('action' => 'edit', $login['Login']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Eliminar Login', true), array('action' => 'delete', $login['Login']['id']), null, sprintf(__('Esta seguro de querer eliminar # %s?', true), $login['Login']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Listado Logins', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Login', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Listado Registros', true), array('controller' => 'registros', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Registro', true), array('controller' => 'registros', 'action' => 'add')); ?> </li>
	</ul>
</div>

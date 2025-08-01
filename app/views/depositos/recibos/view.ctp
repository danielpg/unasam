<div class="recibos view">
<h2><?php  __('Recibo');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo recibo_id($recibo['Recibo']['id']); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Operador'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $recibo['Usuario']['nombres']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Oficina'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $recibo['Oficina']['nombre']; ?>
			&nbsp;
		</dd>

		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Concepto'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $recibo['Precio']['nombre']; ?>
			&nbsp;
		</dd>

		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Nombres'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $recibo['Recibo']['nombres']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Monto'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			S/. <?php echo mformat($recibo['Recibo']['monto']); ?>
			&nbsp;
		</dd>
<?php if(false): ?>
		<!--dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Extorno'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $recibo['Recibo']['extorno']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Codigo'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $recibo['Recibo']['codigo']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Fecha Externo'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $recibo['Recibo']['fecha_externo']; ?>
			&nbsp;
		</dd-->
<?php endif; ?>

		<dt<?php if ($i % 2 == 0) echo $class;?>>Efectivo</dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $recibo['Recibo']['efectivo']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>>Vuelto></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $recibo['Recibo']['vuelto']; ?>
			&nbsp;
		</dd>

		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Creado'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $recibo['Recibo']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Actualizado'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $recibo['Recibo']['modified']; ?>
			&nbsp;
		</dd>

	</dl>
</div>
<form>
<div class="submit">
	<span class="lbutton"><a href="javascript:history.back()">Regresar</a></span>
	<!--span class="lbutton"><?php echo $this->Html->link(__('Regresar', true), array('action' => 'index')); ?></span-->
</div>
</form>




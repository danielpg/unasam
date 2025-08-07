<?php
/**
 * Bake Template for Controller action generation.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.console.libs.template.objects
 * @since         CakePHP(tm) v 1.3
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
 * @author Nicolas Rod <nico@alaxos.com> (adaptation for Alaxos plugin)
 */
?>

	function <?php echo $admin ?>index()
	{
		$this-><?php echo $currentModelName ?>->recursive = 0;
		$this->set('<?php echo $pluralName ?>', $this->paginate($this-><?php echo $currentModelName ?>, $this->AlaxosFilter->get_filter()));
		
<?php
	foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc):
		foreach ($modelObj->{$assoc} as $associationName => $relation):
			if (!empty($associationName)):
				$otherModelName = $this->_modelName($associationName);
				$otherPluralName = $this->_pluralName($associationName);
				echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->find('list');\n";
				$compact[] = "'{$otherPluralName}'";
			endif;
		endforeach;
	endforeach;
	if (!empty($compact)):
		echo "\t\t\$this->set(compact(".join(', ', $compact)."));\n";
	endif;
?>
	}

	function <?php echo $admin ?>view($id = null)
	{
		$this->_set_<?php echo $singularName; ?>($id);
	}

<?php $compact = array(); ?>
	function <?php echo $admin ?>add()
	{
		if (!empty($this->data))
		{
			$this-><?php echo $currentModelName; ?>->create();
			if ($this-><?php echo $currentModelName; ?>->save($this->data))
			{
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(___('the <?php echo strtolower($singularHumanName); ?> has been saved', true), 'flash_message', array('plugin' => 'alaxos'));
				$this->redirect(array('action' => 'index'));
<?php else: ?>
				$this->flash(___('<?php echo ucfirst(strtolower($currentModelName)); ?> saved.', true), array('action' => 'index'), 1, 'flash_message'));
<?php endif; ?>
			}
			else
			{
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(___('the <?php echo strtolower($singularHumanName); ?> could not be saved. Please, try again.', true), 'flash_error', array('plugin' => 'alaxos'));
<?php endif; ?>
			}
		}
		
<?php
	foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc):
		foreach ($modelObj->{$assoc} as $associationName => $relation):
			if (!empty($associationName)):
				$otherModelName = $this->_modelName($associationName);
				$otherPluralName = $this->_pluralName($associationName);
				echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->find('list');\n";
				$compact[] = "'{$otherPluralName}'";
			endif;
		endforeach;
	endforeach;
	if (!empty($compact)):
		echo "\t\t\$this->set(compact(".join(', ', $compact)."));\n";
	endif;
?>
	}

<?php $compact = array(); ?>
	function <?php echo $admin; ?>edit($id = null)
	{
		if (!$id && empty($this->data))
		{
<?php if ($wannaUseSession): ?>
			$this->Session->setFlash(___('invalid id for <?php echo strtolower($singularHumanName); ?>', true), 'flash_error', array('plugin' => 'alaxos'));
			$this->redirect(array('action' => 'index'));
<?php else: ?>
			$this->flash(sprintf(___('invalid id for <?php echo strtolower($singularHumanName); ?>', true)), array('action' => 'index'), 1, 'flash_error');
<?php endif; ?>
		}
		
		if (!empty($this->data))
		{
			if ($this-><?php echo $currentModelName; ?>->save($this->data))
			{
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(___('the <?php echo strtolower($singularHumanName); ?> has been saved', true), 'flash_message', array('plugin' => 'alaxos'));
				$this->redirect(array('action' => 'index'));
<?php else: ?>
				$this->flash(___('the <?php echo strtolower($singularHumanName); ?> has been saved.', true), array('action' => 'index'), 1, 'flash_message');
<?php endif; ?>
			}
			else
			{
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(___('the <?php echo strtolower($singularHumanName); ?> could not be saved. Please, try again.', true), 'flash_error', array('plugin' => 'alaxos'));
<?php endif; ?>
			}
		}
		
		$this->_set_<?php echo $singularName; ?>($id);
		
<?php
		foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc):
			foreach ($modelObj->{$assoc} as $associationName => $relation):
				if (!empty($associationName)):
					$otherModelName = $this->_modelName($associationName);
					$otherPluralName = $this->_pluralName($associationName);
					echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->find('list');\n";
					$compact[] = "'{$otherPluralName}'";
				endif;
			endforeach;
		endforeach;
		if (!empty($compact)):
			echo "\t\t\$this->set(compact(".join(', ', $compact)."));\n";
		endif;
	?>
	}

<?php $compact = array(); ?>
	function <?php echo $admin; ?>copy($id = null)
	{
		if (!$id && empty($this->data))
		{
<?php if ($wannaUseSession): ?>
			$this->Session->setFlash(___('invalid id for <?php echo strtolower($singularHumanName); ?>', true), 'flash_error', array('plugin' => 'alaxos'));
			$this->redirect(array('action' => 'index'));
<?php else: ?>
			$this->flash(sprintf(___('invalid id for <?php echo strtolower($singularHumanName); ?>', true)), array('action' => 'index'), 1, 'flash_error');
<?php endif; ?>
		}
		
		if (!empty($this->data))
		{
			/*
		     * Delete automatically set id to ensure the save() won't make an update
		     */
			$this-><?php echo $currentModelName; ?>->id = false;
			
			if ($this-><?php echo $currentModelName; ?>->save($this->data))
			{
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(___('the <?php echo strtolower($singularHumanName); ?> has been saved', true), 'flash_message', array('plugin' => 'alaxos'));
				$this->redirect(array('action' => 'index'));
<?php else: ?>
				$this->flash(___('the <?php echo strtolower($singularHumanName); ?> has been saved.', true), array('action' => 'index'), 1, 'flash_message');
<?php endif; ?>
			}
			else
			{
				//reset id to copy
				$this->data['<?php echo $currentModelName; ?>'][<?php echo "\$this->{$currentModelName}->primaryKey"; ?>] = $id;
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(___('the <?php echo strtolower($singularHumanName); ?> could not be saved. Please, try again.', true), 'flash_error', array('plugin' => 'alaxos'));
<?php endif; ?>
			}
		}
		
		$this->_set_<?php echo $singularName; ?>($id);
		
<?php
		foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc):
			foreach ($modelObj->{$assoc} as $associationName => $relation):
				if (!empty($associationName)):
					$otherModelName = $this->_modelName($associationName);
					$otherPluralName = $this->_pluralName($associationName);
					echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->find('list');\n";
					$compact[] = "'{$otherPluralName}'";
				endif;
			endforeach;
		endforeach;
		if (!empty($compact)):
			echo "\t\t\$this->set(compact(".join(', ', $compact)."));\n";
		endif;
	?>
	}
	
	function <?php echo $admin; ?>delete($id = null)
	{
		if (!$id)
		{
<?php if ($wannaUseSession): ?>
			$this->Session->setFlash(___('invalid id for <?php echo strtolower($singularHumanName); ?>', true), 'flash_error', array('plugin' => 'alaxos'));
			$this->redirect(array('action'=>'index'));
<?php else: ?>
			$this->flash(sprintf(___('invalid id for <?php echo strtolower($singularHumanName); ?>', true)), array('action' => 'index'), 1, 'flash_error');
<?php endif; ?>
		}
		
		if ($this-><?php echo $currentModelName; ?>->delete($id))
		{
<?php if ($wannaUseSession): ?>
			$this->Session->setFlash(___('<?php echo strtolower($singularHumanName); ?> deleted', true), 'flash_message', array('plugin' => 'alaxos'));
			$this->redirect(array('action'=>'index'));
<?php else: ?>
			$this->flash(___('<?php echo ucfirst(strtolower($singularHumanName)); ?> deleted', true), array('action' => 'index'), 1, 'flash_message');
<?php endif; ?>
		}
			
<?php if ($wannaUseSession): ?>
		$this->Session->setFlash(___('<?php echo strtolower($singularHumanName); ?> was not deleted', true), 'flash_error', array('plugin' => 'alaxos'));
<?php else: ?>
		$this->flash(___('<?php echo ucfirst(strtolower($singularHumanName)); ?> was not deleted', true), array('action' => 'index'), 1, 'flash_error');
<?php endif; ?>
		$this->redirect(array('action' => 'index'));
	}
	
	function <?php echo $admin; ?>actionAll()
	{
	    if(!empty($this->data['_Tech']['action']))
	    {
<?php
        	if(!empty($admin))
        	{
        	?>
        	if(isset($this->Acl))
	        {
	            if($this->Acl->check($this->Auth->user(), '<?php echo ucfirst($pluralName) ?>/admin_' . $this->data['_Tech']['action']))
	            {
	                $this->setAction('admin_' . $this->data['_Tech']['action']);
	            }
	            else
	            {
<?php if ($wannaUseSession): ?>
	                $this->Session->setFlash(___d('alaxos', 'not authorized', true), 'flash_error', array('plugin' => 'alaxos'));
<?php else: ?>
                    $this->flash(___d('alaxos', 'not authorized', true), array('action' => 'index'), 1, 'flash_error');
<?php endif; ?>
	                $this->redirect($this->referer());
	            }
	        }
<?php
        	}
        	else
        	{
            ?>
            if(isset($this->Acl))
	        {
	            if($this->Acl->check($this->Auth->user(), '<?php echo ucfirst($pluralName) ?>/' . $this->data['_Tech']['action']))
	            {
	                $this->setAction($this->data['_Tech']['action']);
	            }
	            else
	            {
<?php if ($wannaUseSession): ?>
	                $this->Session->setFlash(___d('alaxos', 'not authorized', true), 'flash_error', array('plugin' => 'alaxos'));
<?php else: ?>
                    $this->flash(___d('alaxos', 'not authorized', true), array('action' => 'index'), 1, 'flash_error');
<?php endif; ?>
	                $this->redirect($this->referer());
	            }
	        }
<?php
        	}
            ?>
	        elseif(isset($this->Auth) && $this->Auth->user() == null)
	        {
	            /*
	             * Manually check permission, as the setAction() method does not check for permission rights
	             */
<?php
        	if(!empty($admin))
        	{
            	?>
                if(in_array(strtolower('admin_' . $this->data['_Tech']['action']), $this->Auth->allowedActions))
                {
                    $this->setAction('admin_' . $this->data['_Tech']['action']);
                }
<?php
        	}
        	else
        	{
                ?>
                if(in_array(strtolower($this->data['_Tech']['action']), $this->Auth->allowedActions))
                {
                    $this->setAction($this->data['_Tech']['action']);
                }
<?php
        	}
                ?>
                else
	            {
<?php if ($wannaUseSession): ?>
	                $this->Session->setFlash(___d('alaxos', 'not authorized', true), 'flash_error', array('plugin' => 'alaxos'));
<?php else: ?>
	                $this->flash(___d('alaxos', 'not authorized', true), array('action' => 'index'), 1, 'flash_error');
<?php endif; ?>
					$this->redirect($this->referer());
	            }
	        }
	        else
	        {
	        	/*
	             * neither Auth nor Acl, or Auth + logged user
	             * -> grant access
	             */
	        	$this->setAction($this->data['_Tech']['action']);
	        }
	    }
	    else
	    {
<?php if ($wannaUseSession): ?>
	        $this->Session->setFlash(___d('alaxos', 'the action to perform is not defined', true), 'flash_error', array('plugin' => 'alaxos'));
<?php else: ?>
			$this->flash(___d('alaxos', 'the action to perform is not defined', true), array('action' => 'index'), 1, 'flash_error');
<?php endif; ?>
	        $this->redirect($this->referer());
	    }
	}
	
	function <?php echo $admin; ?>deleteAll()
	{
	    $ids = Set :: extract('/<?php echo $currentModelName; ?>/id[id > 0]', $this->data);
	    if(count($ids) > 0)
	    {
    	    if($this-><?php echo $currentModelName; ?>->deleteAll(array('<?php echo $currentModelName; ?>.id' => $ids), false, true))
    	    {
<?php if ($wannaUseSession): ?>
    	        $this->Session->setFlash(___('<?php echo $pluralName ?> deleted', true), 'flash_message', array('plugin' => 'alaxos'));
<?php else: ?>
				$this->flash(___('<?php echo $pluralName ?> deleted', true), array('action' => 'index'), 1, 'flash_error');
<?php endif; ?>
    			$this->redirect(array('action'=>'index'));
    	    }
    	    else
    	    {
<?php if ($wannaUseSession): ?>
    	        $this->Session->setFlash(___('<?php echo $pluralName ?> were not deleted', true), 'flash_error', array('plugin' => 'alaxos'));
<?php else: ?>
				$this->flash(___('<?php echo $pluralName ?> were not deleted', true), array('action' => 'index'), 1, 'flash_error');
<?php endif; ?>
    	        $this->redirect(array('action' => 'index'));
    	    }
	    }
	    else
	    {
<?php if ($wannaUseSession): ?>
	        $this->Session->setFlash(___('no <?php echo $singularName; ?> to delete was found', true), 'flash_error', array('plugin' => 'alaxos'));
<?php else: ?>
			$this->flash(___('no <?php echo $singularName; ?> to delete was found', true), array('action' => 'index'), 1, 'flash_error');
<?php endif; ?>
    	    $this->redirect(array('action' => 'index'));
	    }
	}
	
	
	
<?php if (empty($admin)): ?>
	function _set_<?php echo $singularName; ?>($id)
	{
		if(empty($this->data))
	    {
    	    $this->data = $this-><?php echo $currentModelName; ?>->read(null, $id);
            if($this->data === false)
            {
<?php if ($wannaUseSession): ?>
                $this->Session->setFlash(___('invalid id for <?php echo strtolower($singularHumanName); ?>', true), 'flash_error', array('plugin' => 'alaxos'));
<?php else: ?>
                $this->flash(___('invalid id for <?php echo strtolower($singularHumanName); ?>', true), array('action' => 'index'), 1, 'flash_error');
<?php endif; ?>
                $this->redirect(array('action' => 'index'));
            }
	    }
	    
	    $this->set('<?php echo $singularName; ?>', $this->data);
	}
	
<?php endif; ?>
	
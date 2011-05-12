  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    
    $result = array('success' => true,'message'=> $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.');
   
    $<?php echo $this->getSingularName() ?> = $form->save();

    $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $<?php echo $this->getSingularName() ?>)));
   
    return json_encode($result);
    
  }

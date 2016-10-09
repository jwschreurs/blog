<?php
namespace Blog\Controller;

 use Doctrine\ORM\EntityManager;
 use Zend\Mvc\Controller\AbstractActionController;
 use Zend\View\Model\ViewModel;
 use Blog\Model\Blog,
     Blog\Form\BlogForm;
 use Zend\Validator\File\Size;

 class BlogController extends AbstractActionController
 {
 	protected $em;
	
 
    public function getEntityManager()
    {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->em;
    }
 	
	public function indexAction()
    {
        return new ViewModel(array(
            'blogs' => $this->getEntityManager()->getRepository('Blog\Entity\Blog')->findAll(),
        ));
    } 
	
	public function detailAction()
    {
    	$id = (int) $this->params()->fromRoute('id', 0);
		return new ViewModel(array(
            'blog' => $this->getEntityManager()->find('Blog\Entity\Blog', $id),
        ));
		
    } 
    
    public function addAction()
    {
        return $this->editAction();
    }
	
 
    public function editAction()
    {
      $form = new BlogForm();
	   if ($this->params('id') > 0) {
            $post = $this->getEntityManager()->getRepository('Blog\Entity\Post')->find($this->params('id'));
        }
        $request = $this->getRequest();  
        if ($request->isPost()) {
             
            $blog = new Blog();
            $form->setInputFilter($blog->getInputFilter());
			$File = $this->params()->fromFiles('Image');
             
            $nonFile = $request->getPost()->toArray();
			$files = $this->getRequest()->getFiles()->toArray();
			$data = array_merge_recursive(
			    $this->getRequest()->getPost()->toArray(),
			   $files
			);
            $form->setData($data);
            
			
            if ($form->isValid()) {
			     $size = new Size(array('min'=>2000000)); //minimum bytes filesize
                 
                $adapter = new \Zend\File\Transfer\Adapter\Http(); 
                $adapter->setValidators(array($size), $File['name']);
                if (!$adapter->isValid()){
                    $dataError = $adapter->getMessages();
                    $error = array();
                    foreach($dataError as $key=>$row)
                    {
                        $error[] = $row;
                    }
                    $form->setMessages(array('Image'=>$error ));
                } else {
                    $adapter->setDestination(dirname(__DIR__).'\Assets');
                    if ($adapter->receive($File['name'])) {
                        $profile->exchangeArray($form->getData());
                    }
					else if (!$adapter->receive($File['name'])){
						echo"<pre>";
						var_dump( $File['name']);
						echo"</pre>";
					    $messages = $adapter->getMessages();
					    echo implode("\n", $messages);
						die();
					}
			    }
			} 
			else {
			    foreach ($form->getMessages() as $messageId => $message) {
			       var_dump($messageId);	
			       var_dump($message);
			    } 
        	}
    	}
          
        return array('form' => $form);
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('blog');
        }
 
        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');
 
            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $blog = $this->getEntityManager()->find('Blog\Entity\Blog', $id);
                if ($blog) {
                    $this->getEntityManager()->remove($blog);
                    $this->getEntityManager()->flush();
                }
            }
 
            // Redirect to list of 'blogs
            return $this->redirect()->toRoute('blog');
        }
 
        return array(
            'id'    => $id,
            'blog' => $this->getEntityManager()->find('Blog\Entity\Blog', $id)
        );
    }
	
}
?>
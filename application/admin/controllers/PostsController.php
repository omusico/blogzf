<?php
class Admin_PostsController extends Blogzf_Controller_Action
{
    public function preDispatch()
    {
     //   $this->_helper->layout->disableLayout();
    }
    public function readAction ()
    {
        $this->view->moduleName = 'Post';
        $posts = new Post();
        $select = $posts->select();
        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect( $select ));
        $paginator->setCurrentPageNumber($this->_getParam('page'));
        
        $this->view->paginator = $paginator;
    }
    public function createAction ()
    {
        $form = new forms_Posts();
        if ($this->_request->isPost()) {
            $postData = $this->_request->getPost();
            if ($form->isValid($postData)) {
                try {
                    $posts = new Post();
                    $post = $posts->createRow();
                    $post->title = $postData['title'];
                    $post->content = $postData['content'];
                    $post->user_id = $this->_user->id;
                    $post->open_comment = $postData['comment'];
                    $post->created_on = new Zend_Db_Expr('now()');
                    $post->status = $postData['status'];
                    $post->save();
                    $this->_redirect('/admin/posts/read/');
                } catch (Zend_Exception $e) {
                    echo "Caught exception: " . get_class($e) . "\n";
                    echo "Message: " . $e->getMessage() . "\n";
                }
            } else {
                $form->populate( $postsData );
            }
        }
        $this->view->form = $form;
    }
    public function updateAction ()
    {
        $form = new forms_Posts();
        $posts = new Post();
        $postId = (int) $this->_request->getParam('postId', 0);
        $post = $posts->find($postId)->current();
        if ($this->_request->isPost()) {
            $postData = $this->_request->getPost();
            if ($form->isValid($postData)) {
                try {
                    $post->title = $postData['title'];
                    $post->content = $postData['content'];
                    $post->open_comment = $postData['comment'];
                    $post->update_on = new Zend_Db_Expr('now()');
                    $post->status = $postData['status'];
                    $post->save();
                    $this->_redirect('/admin/posts/read/');
                } catch (Zend_Exception $e) {
                    echo "Caught exception: " . get_class($e) . "\n";
                    echo "Message: " . $e->getMessage() . "\n";
                }
            } else {
                $form->populate($postsData);
            }
        } else {
            $form->populate($post->toArray());
        }
        $this->view->form = $form;
    }
    public function deleteAction ()
    {
        $id = (int) $this->_request->getParam('id', 0);
        $posts = new Post();
        $post = $posts->find($id)->current();
        if ($post !== null) {
            try {
                $post->delete();
            } catch (Exception $e) {
                echo "Caught exception: " . get_class($e) . "\n";
                echo "Message: " . $e->getMessage() . "\n";
            }
        }
        $this->_redirect('/admin/post/reader/');
    }
}
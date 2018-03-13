<?php

namespace CatchOfTheDay\DevExamBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use CatchOfTheDay\DevExamBundle\Model\TodoListItem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Tools\Pagination\Paginator;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     * @Template     
     * @return array
     */
    public function indexAction()
    {
        $manager = $this->get('catch_of_the_day_dev_exam.manager.todo_list');
        $items = $manager->read();		
        return [
            'items' => $items,
        ];
    }	

    /**
     * @Route("/add", name="add")
     * @Method("POST")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request)
    {
        // TODO - Read the new item's text from $request, add a new TodoListItem to the collection and save.
		$todoText = '';
		$todoArray = array();
		//check if request is POST
		if ($request->isMethod('POST')) {
			$manager = $this->get('catch_of_the_day_dev_exam.manager.todo_list');
			$items = $manager->read();			
			$data = $request->request->all();
			//check if data is set and not null
			if(isset($data['todo-text']) && $data['todo-text']!='')
				$todoText = $data['todo-text'];			
			//check for item name in POST
			if($todoText!=''){				
				$listObj 	= new TodoListItem();
				$listObj->setText($todoText);
				$items[]	=	$listObj;				
				$writeResponse 	= $manager->write($items);
				if($writeResponse){					
                    $responseArr = ["code"=>1,"message"=>"TODO item has been saved successfully."];
				} else {
					$responseArr = ["code"=>0,"message"=>"Oops! Something went wrong."];
				}
				return new JsonResponse($responseArr);				
			} else {
                //return message to user that error occurs
                return new JsonResponse(['code'=>0,"message"=>"Oops! Something went wrong."]);
            }			
		} else {
			//return message to user that error occurs
			return new JsonResponse(['code'=>0,"message"=>"Oops! Something went wrong."]);
		}        
    }

    /**
     * @Route("/items/{itemId}/mark-as-complete/{type}", name="mark_as_complete")
     *
     * @param Request $request
     * @param string $itemId
	 * @param string $type
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function markAsCompleteAction(Request $request, $itemId, $type)
    {
        $manager = $this->get('catch_of_the_day_dev_exam.manager.todo_list');
        $items = $manager->read();

        // TODO - Look in $items for the item that matches $itemId, update it and save the collection.
		//check if request is POST
		if ($request->isMethod('POST')) {
			if($type=="complete"){
				$flag		=	true;
				$message	=	"TODO item has been marked as completed";
			} else {
				$flag	=	false;
				$message	=	"TODO item has been marked as In-completed";
			}			
			$updateObj	=	'';			
			foreach($items as $item) {
				$getitemId	= $item->getId();
				if($getitemId == $itemId) {
					$updateObj[0]	=	$item;
				}				
			}
			if($updateObj) {
				$updateObj[0]->setComplete($flag);
				$writeResponse = $manager->write($items);
			}
			$responseArr = [
				"code"	=>	1,			
				"message"	=>	$message
			];
			return new JsonResponse($responseArr);
		}        
    }
	
	/**
     * @Route("/listitems", name="listitems")
     *
     * @param Request $request     
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function listItemsAction(Request $request, $itemId){		
		$manager = $this->get('catch_of_the_day_dev_exam.manager.todo_list');
		$newItems = $manager->read();
		#Generating view so that view can be updated on AJAX success in javaacript
		$shownewItems = $this->render('CatchOfTheDayDevExamBundle:Default:list.html.twig',array('items' => $newItems,'type'=>"complete"))->getContent();
        $responseArr = ["newItems"=>$shownewItems];
		return new JsonResponse($responseArr);
	}
	/**
     * @Route("/completedlist", name="completedlist")
     *
     * @param Request $request     
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function completedListAction(Request $request, $itemId){		
		$manager = $this->get('catch_of_the_day_dev_exam.manager.todo_list');
        $items = $manager->read();		
		#Generating view so that view can be updated on AJAX success in javaacript
		$shownewItems = $this->render('CatchOfTheDayDevExamBundle:Default:clist.html.twig',array('items' => $items,'type'=>"complete"))->getContent();
        $responseArr = ["newItems"=>$shownewItems];
		return new JsonResponse($responseArr);
	}
	/**
     * @Route("/edititem", name="edit_item")
     *
     * @param Request $request
     * @param string $itemId
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function editItemAction(Request $request) {
        if ($request->isMethod('POST')) {
			$data = $request->request->all();
			$itemText	=	$data['todo-text'];
			$itemId	=	$data['todo-id'];
			$manager = $this->get('catch_of_the_day_dev_exam.manager.todo_list');
			$items = $manager->read();
			$updateObj	=	'';			
			foreach($items as $item) {
				$getitemId	= $item->getId();
				if($getitemId == $itemId) {
					$updateObj[0]	=	$item;
				}				
			}
			if($updateObj) {
				$updateObj[0]->setText($itemText);
				$writeResponse = $manager->write($items);
			}
			$responseArr = ["code"=>1,"message"=>"TODO item has been updated successfully"];
			return new JsonResponse($responseArr);
		}
    }
	/**
     * @Route("/completed", name="completed")
     * @Template
     *
     * @return array
     */
    public function completedAction()
    {
        #Action for the list of items which are completed
        $manager = $this->get('catch_of_the_day_dev_exam.manager.todo_list');
        $items = $manager->read();
        
        return [
            'items' => $items,
        ];
    }
}

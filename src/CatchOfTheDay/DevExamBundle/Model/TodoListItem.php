<?php

namespace CatchOfTheDay\DevExamBundle\Model;

class TodoListItem
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var string
     */
    private $text;

    /**
     * @var bool
     */
    private $complete;

    public function __construct($id=null, $created=null, $complete=null)
    {
        // TODO - Generate an identifier for this TodoListItem
		if($id==""||$id==null)
			$this->id = uniqid('todo', true);
		if($created==""||$created==null)
			$this->created = new \DateTime();
		if($complete==""||$complete==null)
			$this->complete = false;
    }

    /**
     * @return mixed
     */
    public function getComplete()
    {
        return $this->complete;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $complete
     * @return TodoListItem
     */
    public function setComplete($complete)
    {
        $this->complete = $complete;

        return $this;
    }

    /**
     * @param \DateTime $created
     * @return TodoListItem
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @param string $id
     * @return TodoListItem
     */
    public function setId($id)
    {
        if( $id==""|| $id == null){            
			$id = uniqid('todo', true);
        }
		$this->id = $id;
        return $this;
    }

    /**
     * @param string $text
     * @return TodoListItem
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @param array $record
     * @return TodoListItem
     */
    public static function fromAssocArray(array $record)
    {
        $item = new TodoListItem();

        // TODO
		$item->id 		= 	$record['id'];
		$item->text		=	$record['text'];
		$item->created 	= 	new \DateTime($record['created']['date']);
		$item->complete = 	$record['complete'];
        return $item;
    }

    /**
     * @return array
     */
    public function toAssocArray()
    {
        $record = [];

        // TODO
		$record['id'] 		= 	$this->id;
        $record['created'] 	= 	$this->created;
        $record['text'] 		=	$this->text;
        $record['complete'] 	= 	$this->complete;
		
        return $record;
    }
}
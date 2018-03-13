<?php

namespace CatchOfTheDay\DevExamBundle\Manager;
use CatchOfTheDay\DevExamBundle\Model\TodoListItem;
use Symfony\Component\Filesystem\Filesystem;

class TodoListManager
{
    const DATA_FILE = '@CatchOfTheDayDevExamBundle/Resources/data/todo-list.json';

    /**
     * @var \Symfony\Component\Config\FileLocatorInterface
     */
    private $fileLocator;

    /**
     * @param \Symfony\Component\Config\FileLocatorInterface $fileLocator
     */
    public function __construct($fileLocator)
    {
        $this->fileLocator = $fileLocator;
    }

    /**
     * @return string
     */
    private function getDataFilePath()
    {
        return $this->fileLocator->locate(self::DATA_FILE);
    }

    /**
     * @return \CatchOfTheDay\DevExamBundle\Model\TodoListItem[]
     */
    public function read()
    {
        $jsonFile = $this->getDataFilePath();
        // TODO - Parse JSON and translate to array of TodoListItem. Hint: TodoListItem::fromAssocArray()
		$readArr = json_decode(file_get_contents($jsonFile), true); 
        $itemsList = [];
        if(!empty($readArr)){
            foreach($readArr as $key => $itemArr){
                $itemObj  = TodoListItem::fromAssocArray($itemArr);
                $itemsList[$key] = $itemObj;
            }
            return $itemsList;
        } else {
            return $itemsList;
        }
    }

    /**
     * @param \CatchOfTheDay\DevExamBundle\Model\TodoListItem[] $items
     */
    public function write(array $items)
    {
        $jsonFile = $this->getDataFilePath();
		// TODO - Serialise $items to JSON and write to $jsonFile. Hint: TodoListItem::toAssocArray()
		$todoArr = [];		
        if(count($items) > 0){
			foreach($items as $itemkey => $todoObj){
				$todoItem = $todoObj->toAssocArray();             
				$todoArr[$itemkey] = $todoItem;
			}
        }		
        if(count($todoArr) > 0){
            $newJson = json_encode($todoArr);
            //now insert to file 
            $file = new Filesystem();
            $file->dumpFile($jsonFile, $newJson);
            return true;
        }            
		return false;                
    }
}
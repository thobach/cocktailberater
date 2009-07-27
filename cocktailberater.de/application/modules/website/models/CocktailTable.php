<?php
class Website_Model_CocktailTable extends Website_Model_MysqlTable
{

    public function __construct()
    {
	    parent::__construct('cocktail');
    }

	
	public function fetchAllByTag ($_tag) {
    	$select = $this->getAdapter()->select();
    	$select->from(array('c'=>'cocktail'));
    	$select->join(array('cht'=>'cocktail_has_tag'),'cht.idcocktail=c.id');
    	$select->join(array('t'=>'tag'),'t.idtag=cht.idtag');
    	$select->where('tag=\''.$_tag.'\'');
    	$stmt = $select->query();
    	$ergArr = $stmt->fetchAll();
    	foreach ($ergArr as $key => $val){
    		if(is_array($val)) {
    			foreach ($val as $key2 => $val2) {
    				$valObj->$key2=$val2;
    			}
    		$ergArr2[$key]=$valObj;
    		}
    		else {
    			$ergArr2[$key]=$val;
    		}
    		
    	}
    	//print_r($stmt);
    	//print_r($ergObj);
    	return $ergArr2;
    }
}
?>
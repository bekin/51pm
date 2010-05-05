<?php
class ResultPage extends CPagination
{
	private $_results; 
	private $_sort;
	private $_ascdesc;
	
	public function getResults()
	{
		return $this->_results;
	}
	
	public function setResults($v)
	{
		$this->_results = $v;	
	}	
	
	public function getSort()
	{
		return $this->_sort;
	}
	
	public function setSort($v)
	{
		$this->_sort = $v;	
	}

	public function getAscdesc()
	{
		return $this->_ascdesc;
	}
	
	public function setAscdesc($v)
	{
		$this->_ascdesc = $v;	
	}
	
}
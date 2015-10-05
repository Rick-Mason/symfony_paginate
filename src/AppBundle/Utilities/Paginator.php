<?php

namespace AppBundle\Utilities;

class Paginator 
{
	private $totalPages;
	private $page;
	private $rpp;
	private $grace;
	private $offset;

	public function __construct($page, $totalcount, $rpp = 10, $grace = 5)
	{
		$this->rpp = $rpp;
		$this->page = $page;
		$this->grace = $grace;
		$this->totalPages = $this->setTotalPages($totalcount, $rpp);
		$this->offset = $this->setOffset($page, $rpp);
	}

	private function setTotalPages($totalcount, $rpp)
	{
		$this->totalPages = ceil($totalcount/$rpp);
		return $this->totalPages;
	}

	public function getTotalPages()
	{
		return $this->totalPages;
	}

	private function setOffset($page, $rpp)
	{
		return ($page - 1) * $rpp;
	}

	public function getOffset()
	{
		return $this->offset;
	}

	public function getPagesList()
	{
		$return = [];
		if($this->totalPages > 1) {	
			//not enough pages to bother breaking it up
			if ($this->totalPages < 7 + ($this->grace * 2)) {	
				for ($i = 1; $i <= $this->totalPages; $i++)
				{ $return[] = $i; }
				return $return;
			//we have enough pages to break it up!
			} elseif ($this->totalPages > 5 + ($this->grace * 2)) {
				
				//close to beginning; only hide later pages
				if($this->page < 1 + ($this->grace * 2))		
				{
					for ($i = 1; $i < 4 + ($this->grace * 2); $i++)
					 { $return[] = $i; }
					$return[] = $this->totalPages;
					return $return;

				//in the middle; hide start pages and end pages	
				} elseif($this->totalPages - ($this->grace * 2) 
							> $this->page && $this->page > ($this->grace * 2)) {
					for ($i = $this->page - $this->grace; $i <= $this->page + $this->grace; $i++)
						{ $return[] = $i;	}
					return $return;

				//close to end; hide start pages
				} else {
					for ($i = $this->totalPages - (2 + ($this->grace * 2)); $i <= $this->totalPages; $i++)
					{ $return[] = $i; }
					return $return;
				}
			}	
		} else {
			return array(1);
		}	
	}


}
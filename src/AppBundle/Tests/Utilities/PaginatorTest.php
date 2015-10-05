<?php 
# src/AppBundle/Tests/Utilities/PaginatorTest.php

namespace AppBundle\Tests\Utilities;

use AppBundle\Utilities\Paginator;

class PaginatorTest extends \PHPUnit_Framework_TestCase {

	public function testPaginator(){
		$paginator1 = new Paginator(2, 200, 10, 3);
		$pagelist = $paginator1->getPagesList();
		$result = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 20);
		$this->assertEquals($result, $pagelist);

		$paginator2 = new Paginator(15, 200, 10, 3);
		$pagelist = $paginator2->getPagesList();
		$result = array(12, 13, 14, 15, 16, 17, 18, 19, 20);
		$this->assertEquals($result, $pagelist);

		$paginator3 = new Paginator(11, 200, 10, 3);
		$pagelist = $paginator3->getPagesList();
		$result = array(8, 9, 10, 11, 12, 13, 14);
		$this->assertEquals($result, $pagelist);

		$paginator4 = new Paginator(3, 33, 10, 3);
		$pagelist = $paginator4->getPagesList();
		$result = array(1, 2, 3, 4);
		$this->assertEquals($result, $pagelist);

		$paginator5 = new Paginator(2, 200);
		$pagelist = $paginator5->getPagesList();
		$result = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 20);
		$this->assertEquals($result, $pagelist);

		$paginator6 = new Paginator(15, 200);
		$pagelist = $paginator6->getPagesList();
		$result = array(8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20);
		$this->assertEquals($result, $pagelist);

		$paginator7 = new Paginator(14, 400);
		$pagelist = $paginator7->getPagesList();
		$result = array(9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19);
		$this->assertEquals($result, $pagelist);
	}
}

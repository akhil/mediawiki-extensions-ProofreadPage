<?php

namespace ProofreadPage\Pagination;

use InvalidArgumentException;
use OutOfBoundsException;
use ProofreadPagePage;
use ProofreadPageTestCase;
use Title;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Pagination\PagePagination
 */
class PagePaginationTest extends ProofreadPageTestCase {

	public function testGetPageNumber() {
		$index = $this->newIndexPage();
		$page = new ProofreadPagePage( Title::newFromText( 'Page:Test 2.tiff' ), $index );
		$pagination = new PagePagination(
			$index,
			[
				new ProofreadPagePage( Title::newFromText( 'Page:Test 1.jpg' ), $index ),
				$page,
				new ProofreadPagePage( Title::newFromText( 'Page:Test:3.png' ), $index )
			],
			[
				new PageNumber( 'TOC' ),
				new PageNumber( '1' ),
				new PageNumber( '2' )
			]
		);
		$this->assertEquals( 2, $pagination->getPageNumber(
			new ProofreadPagePage( Title::newFromText( 'Page:Test 2.tiff' ), $index )
		) );
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testGetPageNumberWithFailure() {
		$index = $this->newIndexPage();
		$pagination = new PagePagination( $index, [], [] );
		$pagination->getPageNumber(
			new ProofreadPagePage( Title::newFromText( 'Page:Test 2.tiff' ), $index )
		);
	}

	public function testGetDisplayedPageNumber() {
		$index = $this->newIndexPage();
		$pageNumber = new PageNumber( 'TOC' );
		$pagination = new PagePagination(
			$index,
			[ new ProofreadPagePage( Title::newFromText( 'Page:Test 1.jpg' ), $index ) ],
			[ $pageNumber ]
		);
		$this->assertEquals( $pageNumber, $pagination->getDisplayedPageNumber( 1 ) );
	}

	/**
	 * @expectedException OutOfBoundsException
	 */
	public function testGetDisplayedPageNumberWithFailure() {
		$index = $this->newIndexPage();
		$pagination = new PagePagination( $index, [], [] );
		$pagination->getDisplayedPageNumber( 3 );
	}

	public function testGetNumberOfPages() {
		$index = $this->newIndexPage();
		$pagination = new PagePagination(
			$index,
			[
				new ProofreadPagePage( Title::newFromText( 'Page:Test 1.jpg' ), $index ),
				new ProofreadPagePage( Title::newFromText( 'Page:Test 2.jpg' ), $index ),
				new ProofreadPagePage( Title::newFromText( 'Page:Test:3.png' ), $index )
			],
			[
				new PageNumber( 'TOC' ),
				new PageNumber( '1' ),
				new PageNumber( '2' )
			]
		);
		$this->assertEquals( 3, $pagination->getNumberOfPages() );
	}

	public function testGetPage() {
		$index = $this->newIndexPage();
		$page = new ProofreadPagePage( Title::newFromText( 'Page:Test 1.jpg' ), $index );
		$pagination = new PagePagination(
			$index,
			[
				new ProofreadPagePage( Title::newFromText( 'Page:Test 1.jpg' ), $index ),
				new ProofreadPagePage( Title::newFromText( 'Page:Test 2.tiff' ), $index ),
				new ProofreadPagePage( Title::newFromText( 'Page:Test:3.png' ), $index )
			],
			[
				new PageNumber( 'TOC' ),
				new PageNumber( '1' ),
				new PageNumber( '2' )
			]
		);
		$this->assertEquals( $page, $pagination->getPage( 1 ) );
	}

	/**
	 * @expectedException OutOfBoundsException
	 */
	public function testGetPageWithFailure() {
		$index = $this->newIndexPage();
		$pagination = new PagePagination( $index, [], [] );
		$pagination->getPage( 3 );
	}

	public function testIterator() {
		$index = $this->newIndexPage();
		$page1 = new ProofreadPagePage( Title::newFromText( 'Page:Test 1.jpg' ), $index );
		$page2 = new ProofreadPagePage( Title::newFromText( 'Page:Test 2.jpg' ), $index );
		$pagination = new PagePagination(
			$index,
			[ $page1, $page2 ],
			[ new PageNumber( '1' ), new PageNumber( '2' ) ]
		);

		$this->assertEquals( 1, $pagination->key() );
		$pagination->next();
		$this->assertEquals( 2, $pagination->key() );
		$this->assertTrue( $pagination->valid() );
		$pagination->next();
		$this->assertFalse( $pagination->valid() );
		$pagination->rewind();
		$this->assertEquals( 1, $pagination->key() );
		$this->assertEquals( $page1, $pagination->current() );
	}
}

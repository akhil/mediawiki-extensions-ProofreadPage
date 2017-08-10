<?php

use ProofreadPage\Page\PageContent;

/**
 * @group ProofreadPage
 * @covers ProofreadPagePage
 */
class ProofreadPagePageTest extends ProofreadPageTestCase {

	/**
	 * Constructor of a new ProofreadPagePage
	 * @param Title|string $title
	 * @param PageContent|null $content
	 * @param ProofreadIndexPage|null $index
	 * @return ProofreadPagePage
	 */
	public static function newPagePage( $title = 'test.jpg', ProofreadIndexPage $index = null ) {
		if ( is_string( $title ) ) {
			$title = Title::makeTitle( 250, $title );
		}
		return new ProofreadPagePage( $title, $index );
	}

	public function testEquals() {
		$page = self::newPagePage( 'Test.djvu' );
		$page2 = self::newPagePage( 'Test.djvu' );
		$page3 = self::newPagePage( 'Test2.djvu' );
		$this->assertTrue( $page->equals( $page2 ) );
		$this->assertTrue( $page2->equals( $page ) );
		$this->assertFalse( $page->equals( $page3 ) );
		$this->assertFalse( $page3->equals( $page ) );
	}

	public function testGetTitle() {
		$title = Title::makeTitle( 250, 'Test.djvu' );
		$page = ProofreadPagePage::newFromTitle( $title );
		$this->assertEquals( $title, $page->getTitle() );
	}

	public function testGetPageNumber() {
		$this->assertEquals( 1, self::newPagePage( 'Test.djvu/1' )->getPageNumber() );

		$this->assertNull( self::newPagePage( 'Test.djvu' )->getPageNumber() );
	}

	public function testGetIndex() {
		$index = ProofreadIndexPageTest::newIndexPage();
		$this->assertEquals( $index, self::newPagePage( 'Test.jpg', $index )->getIndex() );
	}
}

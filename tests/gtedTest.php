<?php
class GtedTest 
extends PHPUnit_Framework_TestCase 
{
	public function test_noop() {
		//$manager = new WPSSManager();
	}
	/**
	 * add geotrack
	 */
	public function test_add_geotrack() {
		$site = array(
			'post_type' => 'geotrack',
			'post_title' => 'Hello by Adele from 25 (2015)',
			'post_author' => 1, // ??
			'post_status' => 'publish',
		);
		$id = wp_insert_post( $site );
		$this->assertNotEquals( $id, 0 );
		return $id;
	}
	/** 
 	 * delete test item
	 * @depends test_add_geotrack
	 */
	public function test_delete( $id ) {
		$res = wp_delete_post( $id, true );
		// String equality?!
		$this->assertTrue( $res !== false );
	}
}

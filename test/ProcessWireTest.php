<?php

use PHPUnit\Framework\TestCase;

class ProcessWireTest extends TestCase
{
	public function testWireFunction()
	{
		$pages = \ProcessWire\wire('pages');
		$this->assertEquals($pages->className(), 'Pages');
	}
	
	public function testDatabaseSetup()
	{
		$home = \ProcessWire\wire('pages')->get('/');
		$this->assertEquals('Skyscrapers', $home->title);
	}
}
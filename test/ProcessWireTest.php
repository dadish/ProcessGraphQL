<?php

use PHPUnit\Framework\TestCase;

class ProcessWireTest extends TestCase
{
	public function testWireFunction()
	{
		$pages = \ProcessWire\wire('pages');
		assertEquals($pages->className(), 'Pages');
	}
	
	public function testDatabaseSetup()
	{
		$home = \ProcessWire\wire('pages')->get('/');
		assertEquals('Skyscrapers', $home->title);
	}
}
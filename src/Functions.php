<?php namespace ProcessWire\GraphQL;

use ProcessWire\ProcessWire;

/**
 * Return a ProcessWire API variable, or NULL if it doesn't exist
 *
 * And the wire() function is the recommended way to access the API when included from other PHP scripts.
 * Like the fuel() function, except that ommitting $name returns the current ProcessWire instance rather than the fuel.
 * The distinction may not matter in most cases.
 *
 * @param string $name If omitted, returns a Fuel object with references to all the fuel.
 * @return null|ProcessWire|Wire|Session|Page|Pages|Modules|User|Users|Roles|Permissions|Templates|Fields|Fieldtypes|Sanitizer|Config|Notices|WireDatabasePDO|WireHooks|WireDateTime|WireFileTools|WireMailTools|WireInput|string|mixed
 *
 */
function wire($name = 'wire') {
	return ProcessWire::getCurrentInstance()->wire($name); 
}
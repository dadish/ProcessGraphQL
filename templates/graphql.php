<?php namespace ProcessWire;

header('Content-Type: application/json');

echo json_encode($modules->get('ProcessGraphQL')->executeGraphQL(), true);
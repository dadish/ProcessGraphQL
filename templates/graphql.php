<?php namespace ProcessWire;

echo json_encode($modules->get('ProcessGraphQL')->executeGraphQL(), true);
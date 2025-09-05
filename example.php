<?php

use BetterWorld\NarrativePhp\Storyline;

require_once __DIR__.'/vendor/autoload.php';

$storyline = new Storyline('http://narrative-cloud.test/api/storylines/01k37qbqmxfn253s65a4gyr75e', '1|JC4p4zJ7ibuttdAAO51RoBRGH9pGK399BcpSARFCeaf59791');

// $storyline->events()->create("Events Created", "From Saloon refactoring");

var_dump($storyline->scopes()->list());

<?php

use \antonshell\EgrulNalogParser\Parser;
use antonshell\EgrulNalogParser\PdfService;

$parser = new Parser();

// parse for Individual entrepreneur
$pathPe = __DIR__ . '/nalog_pe.pdf';
$results = $parser->parseNalogPe($pathPe);

// parse for organization
$pathOrg = __DIR__ . '/nalog_org.pdf';
$parser->parseNalogOrg($pathOrg);

# egrul-nalog-parser
Pdf parser for https://egrul.nalog.ru/

# Instalation

```
composer require "antonshell/egrul-nalog-parser:0.0.3"
```

# Examples

Parse PDF for Individual Entrepreneur

```php
<?php

$parser = new \antonshell\EgrulNalogParser\Parser();

// parse for Individual Entrepreneur
$pathPe = __DIR__ . '/nalog_pe.pdf';
$results = $parser->parseNalogPe($pathPe);
```

Parse PDF for Individual Entrepreneur

```php
<?php

$parser = new \antonshell\EgrulNalogParser\Parser();

// parse for Organization
$pathOrg = __DIR__ . '/nalog_org.pdf';
$parser->parseNalogOrg($pathOrg);
```
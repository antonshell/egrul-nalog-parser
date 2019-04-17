# egrul-nalog-parser
Pdf parser for 
https://egrul.nalog.ru/

<p align="center">
    <a href="http://demo.antonshell.me/egrul-nalog-parser/" target="_blank">
        <img src="http://demo.antonshell.me/images/egrul-nalog-parser.png" alt="egrul-nalog-parser" />
    </a>
</p>

# Instalation

```
composer require "antonshell/egrul-nalog-parser:dev-master"
```

# Demo

See [**demo**][1] for more details

<p align="center">
    <a href="http://demo.antonshell.me/egrul-nalog-parser/" target="_blank">
        <img src="http://demo.antonshell.me/images/egrul-nalog-parser-demo.jpg" alt="egrul-nalog-parser" />
    </a>
</p>

# Examples

Parse PDF, auto detect type

```php
<?php

$parser = new \antonshell\EgrulNalogParser\Parser();

// parse, auto detect type
$path = __DIR__ . '/nalog_pe.pdf';
$results = $parser->parseDocument($path);
```


Parse PDF for Individual Entrepreneur

```php
<?php

$parser = new \antonshell\EgrulNalogParser\Parser();

// parse for Individual Entrepreneur
$pathPe = __DIR__ . '/nalog_pe.pdf';
$results = $parser->parseNalogPe($pathPe);
```

Parse PDF for Organization

```php
<?php

$parser = new \antonshell\EgrulNalogParser\Parser();

// parse for Organization
$pathOrg = __DIR__ . '/nalog_org.pdf';
$parser->parseNalogOrg($pathOrg);
```

[1]: http://demo.antonshell.me/egrul-nalog-parser/
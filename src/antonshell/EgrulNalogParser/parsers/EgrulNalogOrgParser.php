<?php

namespace antonshell\EgrulNalogParser\parsers;

/**
 * Class EgrulNalogOrgParser
 * @package app\modules\admin\components\pdf\parsers
 */
class EgrulNalogOrgParser extends EgrulNalogBaseParser implements ParserInterface
{

    /**
     * EgrulNalogPeParser constructor.
     */
    public function __construct()
    {
        $this->breakWords = require(__DIR__ . '/../data/nalog_breakwords_org.php');
        $this->fields = require(__DIR__ . '/../data/nalog_fields_org.php');
        $this->namespaces = require(__DIR__ . '/../data/nalog_namespaces_org.php');

        $this->subSpace2keyWord = 'Наименование документа';
        $this->documentCheckerKeyWord = 'ведения о юридическом лице';
        $this->subSpace2groupName = 'documents';

        $this->type = 'ОРГ';
    }

    /**
     * @param $text
     * @return array
     */
    public function parseDocument($text)
    {
        return parent::parseDocument($text);
    }
}
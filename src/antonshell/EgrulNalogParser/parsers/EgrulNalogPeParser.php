<?php

namespace antonshell\EgrulNalogParser\parsers;

/**
 * Class EgrulNalogPeParser
 * @package app\modules\admin\components\pdf\parsers
 */
class EgrulNalogPeParser extends EgrulNalogBaseParser implements ParserInterface
{

    /**
     * EgrulNalogPeParser constructor.
     */
    public function __construct()
    {
        $this->breakWords = require(__DIR__ . '/../data/nalog_breakwords_pe.php');
        $this->fields = require(__DIR__ . '/../data/nalog_fields_pe.php');
        $this->namespaces = require(__DIR__ . '/../data/nalog_namespaces_pe.php');

        $this->subSpace2keyWord = 'Наименование документа';
        $this->documentCheckerKeyWord = 'ведения об индивидуальном предпринимателе';
        $this->subSpace2groupName = 'documents';

        $this->type = 'ИП';
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
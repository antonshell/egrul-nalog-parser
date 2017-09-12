<?php

namespace antonshell\EgrulNalogParser\parsers;

/**
 * Interface MapperToPdfInterface
 * @package app\modules\admin\components\pdf\mappers
 */
interface ParserInterface
{
    public function parseDocument($text);

    public function setOutputFormat($format);
}
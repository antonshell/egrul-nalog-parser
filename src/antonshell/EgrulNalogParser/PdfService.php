<?php

namespace antonshell\EgrulNalogParser;

use antonshell\EgrulNalogParser\parsers\EgrulNalogOrgParser;
use antonshell\EgrulNalogParser\parsers\EgrulNalogPeParser;
use antonshell\EgrulNalogParser\parsers\ParserInterface;

/**
 * Class PdfService
 * @package app\modules\admin\components\nalog
 */
class PdfService{

    /**
     * @param $path
     * @return array
     */
    public function parseNalogPe($path){
        $parser = new EgrulNalogPeParser();
        return $this->parseDocument($path, $parser);
    }

    /**
     * @param $path
     * @return array
     */
    public function parseNalogOrg($path){
        $parser = new EgrulNalogOrgParser();
        return $this->parseDocument($path, $parser);
    }

    /**
     * @param $path
     * @param ParserInterface $parser
     * @return mixed
     */
    public function parseDocument($path, ParserInterface $parser){
        $text = $this->getPlainText($path);
        $data = $parser->parseDocument($text);
        return $data;
    }

    /**
     * @param $path
     * @return string
     */
    public function getPlainText($path){
        $parser = new \Smalot\PdfParser\Parser();
        $pdf    = $parser->parseFile($path);
        return $pdf->getText();
    }

    /**
     * @return string
     */
    public function test(){
        return 'Parser:parseDocument';
    }
}
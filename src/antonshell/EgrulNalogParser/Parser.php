<?php

namespace antonshell\EgrulNalogParser;

use antonshell\EgrulNalogParser\parsers\EgrulNalogOrgParser;
use antonshell\EgrulNalogParser\parsers\EgrulNalogPeParser;
use antonshell\EgrulNalogParser\parsers\ParserInterface;

/**
 * Class PdfService
 * @package app\modules\admin\components\nalog
 */
class Parser{

    const DOC_TYPE_ORG = 1;
    const DOC_TYPE_PE = 2;

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
     * @param ParserInterface|null $parser
     * @return array
     */
    public function parseDocument($path, ParserInterface $parser = null){
        $text = $this->getPlainText($path);

        if(!$parser){
            $parser = $this->getDocumentParser($text);
        }

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
     * @param $text
     * @return EgrulNalogOrgParser|EgrulNalogPeParser|null
     * @throws \Exception
     */
    private function getDocumentParser($text){
        $parser = null;

        $orgParser = new EgrulNalogOrgParser();
        $peParser = new EgrulNalogPeParser();

        if(mb_strpos($text, $orgParser->getDocumentCheckerKeyWord()) !== false){
            $parser = $orgParser;
        }

        if(mb_strpos($text, $peParser->getDocumentCheckerKeyWord()) !== false){
            $parser = $peParser;
        }

        if(!$parser){
            throw new \Exception('Can\'t get document type');
        }

        return $parser;
    }
}
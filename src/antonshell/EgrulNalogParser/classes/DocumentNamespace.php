<?php

namespace antonshell\EgrulNalogParser\classes;

/**
 * Class DocumentNamespace
 * @package app\modules\admin\components\pdf\parsers
 */
class DocumentNamespace
{
    public $name;
    public $keyword;
    public $subspaces;
    public $subspaces_2;

    /**
     * DocumentNamespace constructor.
     * @param $name
     * @param $keyword
     * @param bool $subspaces
     * @param bool $subspaces_2
     */
    public function __construct($name, $keyword, $subspaces = false, $subspaces_2 = false)
    {
        $this->name = $name;
        $this->keyword = $keyword;
        $this->subspaces = $subspaces;
        $this->subspaces_2 = $subspaces_2;
    }
}
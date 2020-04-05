<?php

namespace antonshell\EgrulNalogParser\parsers;

/**
 * Class EgrulNalogBaseParser
 * @package app\modules\admin\components\pdf\parsers
 */
abstract class EgrulNalogBaseParser extends BaseParser
{
    // document namespaces constants
    const MAX_SUBSPACE = 100;

    const DEFAULT_SUBSPACE_1 = 1;

    const DEFAULT_SUBSPACE_2 = 0;


    const START_LINE_INDEX = 0;

    const START_POINT_INDEX = 1;

    // configuration data for specific document
    public $namespaces;

    public $breakWords;

    public $fields;

    protected $documentCheckerKeyWord;

    // document namespaces data
    protected $currentNamespace;

    protected $subSpace;

    protected $subSpace2;

    protected $subSpace2keyWord;

    protected $subSpace2groupName;

    // document structure
    protected $text = '';

    protected $lines = [];

    protected $results = [];

    // class configuration settings
    protected $outputFormat = 'tree'; // array | tree

    // debug
    protected $debugNamespacesItems = [];

    protected $debugResults = [];

    /**
     * @param $text
     * @return array
     */
    public function parseDocument($text)
    {
        $this->text = $text;
        $this->checkDocumentType($text);

        $this->results = [];

        $namespaces = $this->namespaces;
        $fields = $this->fields;

        // split text by this->lines
        $this->lines = $this->splitDocument($text);

        //current line
        $lineIndex = self::START_LINE_INDEX;

        // current point of document
        $pointIndex = self::START_POINT_INDEX;

        // document namespaces
        $this->currentNamespace = $namespaces[0];
        $this->subSpace = self::DEFAULT_SUBSPACE_1;

        while(isset($this->lines[$lineIndex])){
            // update namespace according to specific rule. see namespaces property
            $this->updateNamespace($lineIndex);

            if($this->checkLine($lineIndex,$pointIndex)){
                $item = $this->getItem($lineIndex,$pointIndex);

                // debug namespaces. associate point indexes with namespace items
                $this->debugNamespaceItems($pointIndex);

                $matchField = false;
                $fieldLabel = '';
                $key = '';

                foreach ($fields as $label => $field){
                    if(mb_strpos($item, $label) !== false){
                        $key .= $field;
                        $matchField = true;
                        $fieldLabel = $label;
                    }
                }

                $itemDebug = $this->clearItem($item, $fieldLabel, $pointIndex, true);
                $outputMethod = 'output' . ucfirst($this->outputFormat);
                $this->$outputMethod($key,$itemDebug,true);

                // output to results array
                $item = $this->clearItem($item, $fieldLabel, $pointIndex);
                $outputMethod = 'output' . ucfirst($this->outputFormat);
                $this->$outputMethod($key,$item);

                $pointIndex++;
            }

            $lineIndex++;
        }

        return $this->results;
    }

    /**
     * @param $format
     * @throws \Exception
     */
    public function setOutputFormat($format){
        if(!in_array($format,['array', 'tree'])){
            throw new \Exception('');
        }

        $this->outputFormat = $format;
    }

    /**
     * get debug info
     *
     * @return array
     */
    public function getDebugInfo(){
        return [
            'debugResults' => $this->debugResults,
            'debugNamespacesItems' => $this->debugNamespacesItems,
        ];
    }

    /**
     * @return mixed
     */
    public function getDocumentCheckerKeyWord(){
        return $this->documentCheckerKeyWord;
    }

    /**
     * check if input document has correct format( pe / org / other )
     * check by keyword
     *
     * @param $text
     * @throws \Exception
     */
    protected function checkDocumentType($text){
        $text = mb_strtolower($text);
        if(mb_strpos($text, $this->documentCheckerKeyWord) === false){
            throw new \Exception('Wrong document type');
        }
    }

    /**
     * remove garbige characters from line
     *
     * @param $line
     * @return mixed|string
     */
    protected function clearLine($line){
        $line = str_replace('\f','',$line);

        /*$pos = mb_strpos($line,'');
        if($pos !== false){
            $line = mb_substr($line,0,$pos);
        }*/

        return $line;
    }

    /**
     * clear item from metadata
     *
     * @param $item
     * @param $fieldLabel
     * @param $pointIndex
     * @param bool $removePointIndex
     * @return mixed|string
     */
    protected function clearItem($item, $fieldLabel, $pointIndex, $debug = false){
        // remove label
        $item = str_replace($fieldLabel,'',$item);

        // replace garbage strings
        $replaces = [
            '1	2	3	 Наименование	 1',
            '2	3	 , имя, отчество (при наличии 1',
            '(ИНН'
        ];

        foreach ($replaces as $replace){
            $item = str_replace($replace,'', $item);
        }

        // remove point index
        if(!$debug){
            $item = mb_substr($item,strlen($pointIndex), mb_strlen($item));
        }

        $item = trim($item);
        return $item;
    }

    /**
     * output items into results array
     * uses tree format
     * namespace -> subspace -> subspace2 -> field
     *
     * @param $results
     * @param $key
     * @param $item
     */
    protected function outputTree($key,$item, $debug = false){
        if($debug){
            $results = &$this->debugResults;
        }
        else{
            $results = &$this->results;
        }

        $tmpArr = &$results;
        //$tmpArr = &$this->results;
        $tmpArr = &$tmpArr[$this->currentNamespace->name];

        if($this->currentNamespace->subspaces){
            $tmpArr = &$tmpArr[$this->subSpace];
        }

        if($this->currentNamespace->subspaces_2 && ($this->subSpace2 !== self::DEFAULT_SUBSPACE_2)){
            $tmpArr = &$tmpArr[$this->subSpace2groupName][$this->subSpace2];
        }

        $key = $this->precessDuplicatedFields($tmpArr,$key);

        $tmpArr[$key] = $item;
    }

    /**
     * * output items into results array
     * uses array format
     * namespace_subspace_subspace2_field
     *
     * @param $results
     * @param $key
     * @param $item
     */
    protected function outputArray($key,$item, $debug = false){
        if($debug){
            $results = &$this->debugResults;
        }
        else{
            $results = &$this->results;
        }

        $key = $this->currentNamespace->name . '_' . $key;

        if($this->currentNamespace->subspaces){
            $key .= '_' . $this->subSpace;
        }

        if($this->currentNamespace->subspaces_2 && ($this->subSpace2 !== self::DEFAULT_SUBSPACE_2)){
            $key .= '_' . $this->subSpace2;
        }

        //$key = $this->precessDuplicatedFields($this->results,$key);
        $key = $this->precessDuplicatedFields($results,$key);

        //$this->results[$key] = $item;
        $results[$key] = $item;
    }

    /**
     * process duplicated fields in namespace
     *
     * @param $results
     * @param $key
     * @return string
     */
    protected function precessDuplicatedFields(&$results,&$key){
        if(isset($results[$key])){
            $index = 1;
            do{
                $index++;
                $tmpKey = $key . '_' . $index;
            }
            while(isset($results[$tmpKey]));

            $key = $tmpKey;
        }

        return $key;
    }

    /**
     * @param $text
     * @return array
     */
    protected function splitDocument($text){
        $lines = [];

        foreach(preg_split('~[\r\n]+~', $text) as $line){
            if(empty($line) or ctype_space($line)) continue; // skip only spaces
            // if(!strlen($line = trim($line))) continue; // or trim by force and skip empty
            // $line is trimmed and nice here so use it

            $line = $this->clearLine($line);

            $lines[] = $line;
        }

        return $lines;
    }

    /**
     * @param $lineIndex
     * @param $pointIndex
     * @return bool
     */
    protected function checkLine($lineIndex,$pointIndex){
        $line = $this->lines[$lineIndex];
        $numLength = strlen($pointIndex);
        $firstChar = mb_substr($line,0, $numLength);
        $secondChar = mb_substr($line,$numLength, 1);

        return ($firstChar == $pointIndex) && !is_numeric($secondChar);
    }

    /**
     * Update document namespace based on namespace keywords
     * Update subspace based on specific parameter (integer line, less then 1000)
     *
     * @param $lineIndex
     */
    protected function updateNamespace($lineIndex){
        // update namespace
        $line = $this->lines[$lineIndex];
        $namespaces = $this->namespaces;

        $maxSimilarity = 0;
        foreach ($namespaces as $namespace){
            $keyWord = $namespace->keyword;
            if(mb_strpos($line, $keyWord) !== false){

                $similarity = similar_text($line, $keyWord);
                if($similarity > $maxSimilarity){
                    $maxSimilarity = $similarity;

                    $this->currentNamespace = $namespace;
                    $this->subSpace = self::DEFAULT_SUBSPACE_1;
                    $this->subSpace2 = self::DEFAULT_SUBSPACE_2;
                }
            }
        }

        // update subspace
        if($this->currentNamespace->subspaces){
            if($this->checkSubSpace($lineIndex)){
                $this->subSpace = (int)$line;
                $this->subSpace2 = self::DEFAULT_SUBSPACE_2;
            }
        }

        // update subspace 2
        if($this->currentNamespace->subspaces_2){
            if($this->checkSubSpace2($lineIndex)){
                $this->subSpace2 += 1;
            }
        }
    }

    /**
     * debug namespaces. associate point indexes with namespace items
     *
     * @param $pointIndex
     */
    protected function debugNamespaceItems($pointIndex){
        $tmpArr = &$this->debugNamespacesItems;
        $tmpArr = &$tmpArr[$this->currentNamespace->name];

        if($this->currentNamespace->subspaces){
            $tmpArr = &$tmpArr[$this->subSpace];
        }

        if($this->currentNamespace->subspaces_2 && ($this->subSpace2 !== self::DEFAULT_SUBSPACE_2)){
            $tmpArr = &$tmpArr[$this->subSpace2groupName][$this->subSpace2];
        }

        $tmpArr[] = $pointIndex;
    }

    /**
     * @param $lineIndex
     * @return bool
     */
    protected function checkSubSpace($lineIndex){
        $line = $this->lines[$lineIndex];
        return (ctype_digit($line) && (int)$line < self::MAX_SUBSPACE);
    }

    /**
     * @param $lineIndex
     * @return bool
     */
    protected function checkSubSpace2($lineIndex){
        $line = $this->lines[$lineIndex];
        return (mb_strpos($line, $this->subSpace2keyWord) !== false);
    }

    /**
     * get item from text. In case if some value stored on multiple lined it will put it in item variable
     * break words used for remove unused data
     *
     * @param $lineIndex
     * @param $pointIndex
     * @return mixed|string
     */
    protected function getItem($lineIndex,$pointIndex){
        $breakWords = $this->breakWords;
        $line = $this->lines[$lineIndex];
        $item = $line;

        $tmpIndex = $lineIndex+1;

        $line = $this->lines[$tmpIndex];

        // check next lines before next item
        while (!$this->checkLine($tmpIndex,$pointIndex+1)){
            //$item .= ' ' . $line;
            //$tmpIndex++;

            if(!isset($this->lines[$tmpIndex])){
                break;
            }

            //$line = $this->lines[$tmpIndex];

            // checking break words
            foreach ($breakWords as $word){
                if(mb_strpos($line, $word) !== false){
                    break 2; // Exits the foreach and the while
                }
            }

            // checking subspace words
            if($this->checkSubSpace($tmpIndex)){
                break;
            }

            $item .= ' ' . $line;
            $tmpIndex++;
            $line = $this->lines[$tmpIndex];
        }

        return $item;
    }
}
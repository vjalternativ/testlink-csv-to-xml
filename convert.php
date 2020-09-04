<?php
require_once 'libs/lib_csv.php';
require_once 'libs/lib_xml.php';
require_once 'libs/lib_cli.php';
require_once 'libs/lib_logger.php';

class converter extends lib_cli {


    private $data = array();

    private $testCaseId  = 0;


    private $xml;



    function __construct() {
        parent::__construct();
        $this->cliRegistrar[1] = "processCSV";
        $this->xml = new lib_xml();

        $this->data['element'] = "testcases";
        $this->data['childs'] =array();
    }


    function processCsvLine($index,$row) {

        if($index==1) {
            return;
        }
        if(isset($row[0])) {
            if($row[0] && $row[0]!=$this->testCaseId) {
                $this->testCaseId++;
                $this->data['childs'][$this->testCaseId]['element'] = "testcase";
            }

        }

        if(isset($row[1]) && $row[1]) {
            $this->data['childs'][$this->testCaseId]['attributes']['name'] = $row[1];
        }

        if(isset($row[2]) && $row[2]) {
            $this->data['childs'][$this->testCaseId]['childs']['summary'] = $this->xml->getKeyValueNode("summary", $row[2]);
        }

        if(isset($row[3]) && $row[3]) {
            $this->data['childs'][$this->testCaseId]['childs']['preconditions'] = $this->xml->getKeyValueNode("preconditions", $row[3]);
        }

        if(isset($row[4]) && $row[4] && isset($row[5]) && $row[5]) {

            $step = array();
            $step['element'] = "step";



            $stepnumber = 1;

            if(isset($this->data['childs'][$this->testCaseId]['childs']['steps']['childs'])) {
                $stepnumber = count($this->data['childs'][$this->testCaseId]['childs']['steps']['childs']) + 1;
            }
            $step['childs'][] = $this->xml->getKeyValueNode("step_number", $stepnumber);
            $step['childs'][] = $this->xml->getKeyValueNode("actions", $row[4]);
            $step['childs'][] = $this->xml->getKeyValueNode("expectedresults", $row[5]);

            $this->data['childs'][$this->testCaseId]['childs']['steps']['element'] ="steps";
            $this->data['childs'][$this->testCaseId]['childs']['steps']['childs'][]=  $step;
        }



    }

    function processCSV($file) {
            $file =  $this->getFilePath($file);
            $csv = new lib_csv($file);
            $csv->read($this,"processCsvLine");
            $this->xml->processNode($this->data);
            $path = __DIR__.'/output.xml';
            $this->xml->save($path);
    }

    function execute() {
        $this->process();
    }

}


$ob = new converter();
$ob->execute();
?>
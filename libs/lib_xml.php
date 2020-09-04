<?php
class lib_xml {

    private $xmlDoc ;
    function __construct() {
        $this->xmlDoc = new DOMDocument("1.0","UTF-8");
        //make the output pretty
        $this->xmlDoc->formatOutput = true;
    }


    function getKeyValueNode($key,$val) {
        $node = array();
        $node['element'] = $key;

        $node['val'] = $val;

        return $node;
    }

    function getXmlData($element,$attributes,$childs) {
        $data = array();
        $data['element'] = "urlset";
        $data['attributes'] = $attributes;
        $data['childs'] = $childs;

        return $data;
    }


    function processNode($data) {
        $this->_processNode($this->xmlDoc, $this->xmlDoc, $data);
    }
    private function _processNode($xmlDoc,&$node,$data) {
        $cnode = false;
        if(isset($data['val'])) {
            $cnode = $node->appendChild($xmlDoc->createElement($data['element'],$data['val']));

        } else {

            if(isset($data['element'])) {
                $cnode = $node->appendChild($xmlDoc->createElement($data['element']));
            } else {
                $cnode = $node;
            }
        }
        if(isset($data['attributes'])) {
            foreach($data['attributes'] as $key=>$val){
                $cnode->setAttribute($key, $val);
            }
        }

        if(isset($data['childs'])) {
            foreach($data['childs'] as $cdata) {
                if(isset($cdata['element'])) {
                    $this->_processNode($xmlDoc,$cnode, $cdata);
                }
            }
        }


    }

    function save($path) {
            $this->xmlDoc->save($path);
    }

    function processNodes($data) {
        foreach($data as $nodedata) {
            $this->_processNode($this->xmlDoc, $this->xmlDoc, $nodedata);
        }
    }

}
?>
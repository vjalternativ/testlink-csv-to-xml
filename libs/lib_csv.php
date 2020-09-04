<?php
class lib_csv {

    var $isvalid = false;

    var $actualPath = false;

    var $counter  = 0;


    function setPath($path) {
        if (isset($_FILES[$path])) {

            if ($_FILES[$path]['error'] == '0') {
                $this->isvalid = true;
                $this->actualPath = $_FILES[$path]['tmp_name'];
            }
        } else {
            if(file_exists($path)) {
                $this->isvalid = true;
                $this->actualPath = $path;

            }
        }
    }

    function __construct($path)
    {
        $this->setPath($path);
    }

    function setCounter($counter) {
        $this->counter = $counter;
    }

    function read($callbackOb,$callbackMethod)
    {
        if ($this->isvalid) {
            $file = fopen($this->actualPath, "r");
            $counter = $this->counter;
            while (! feof($file)) {
                $data = fgetcsv($file);
                if($data) {


                    $counter ++;



                    $callbackOb->{$callbackMethod}($counter, $data);
                }
            }
            fclose($file);
        }

        return $this->isvalid;
    }




}
?>
<?php
class lib_cli {

    var $cliRegistrar = array();



    function __construct() {
        if (PHP_SAPI !== 'cli') {
            lib_logger::getInstace()->error("can only be execute from cli");
        }
    }


    function process() {
        $this->processArgs(1, $this->cliRegistrar);
    }

    private function processArgs($index,$registrar)
    {
        $args = $_SERVER['argv'];

        if (isset($args[$index])) {

            if (isset($registrar[$args[$index]])) {

                if (is_array($registrar[$args[$index]])) {
                    $newIndex = $index + 1;
                    $this->processArgs($newIndex, $args, $registrar[$args[$index]]);
                } else {
                    if ($registrar[$args[$index]]) {

                        $options = explode(",", $registrar[$args[$index]]);

                        $method = $options[0];
                        unset($options[0]);

                        $params = array();

                        $isValid = true;
                        $parg = "";
                        foreach ($options as $op) {
                            $index ++;
                            if (isset($args[$index])) {
                                $params[$op] = $args[$index];
                            } else {
                                $isValid = false;
                                $parg = $op;
                                break;
                            }
                        }
                        if ($isValid) {
                            if (method_exists($this, $method)) {
                                $this->{$method}($params);
                            } else {
                                echo $method . " function not defined in class." . PHP_EOL;
                            }
                        } else {
                            echo "specify " . $parg . " in arguments" . PHP_EOL;
                        }
                    } else {
                        echo "option not defined" . PHP_EOL;
                    }
                }
            } else {

                if(isset($registrar[$index])) {
                    $method = $registrar[$index];

                    if(isset($args[$index]))  {
                        $this->$method($args[$index]);
                    } else {
                        echo "Invalid input. Speicify ".$index." argument =>  "  . PHP_EOL;

                    }
                } else {

                    $keys = array_keys($registrar);
                    $string = implode("/", $keys);
                    echo "Invalid input. Options are =>  " . $string . PHP_EOL;

                }
            }
        } else {
            $keys = array_keys($registrar);
            $string = implode("/", $keys);
            echo "Invalid input. Options are =>  " . $string . PHP_EOL;
        }
    }

    function getFilePath($file) {

        if(substr($file,0,1)=="/") {
            return $file;
        } else {
            return $_SERVER['PWD'].'/'.$file;
        }

    }


}
?>
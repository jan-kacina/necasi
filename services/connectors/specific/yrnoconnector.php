<?php
    namespace services\connectors\specific;

    use services\connectors as Connectors;

    class YrNoConnector extends Connectors\BaseConnector {
        public function __construct() {
            parent::__construct();

            $this->usingLocation = array("lat", "lng");
            $this->urlExample = "https://api.met.no/weatherapi/locationforecast/1.9/?lat=:lat;lon=:lng";
            $this->reduceLocation();
        }

        public function getWeather() {
            if(!$this->isValid()) {
                return false;
            }

            $this->rawOutput = file_get_contents($this->url);

            //return object, this for test only
            $tempXml = $this->parser->parse($this->rawOutput);
            
            return $tempXml;
        }

        protected function isValid() {
            if(!isset($this->parser) || empty($this->parser->parserVersion)) {
                return false;
            }

            $this->url = $this->urlExample;

            foreach($this->usingLocation as $key => $val) {
                if(empty($this->location[$val])) {
                    $this->url = null;
                    return false;
                }
                else {
                    $this->url = str_replace(":".$val, $this->location[$val], $this->url);
                }
            }

            return true;
        }
    }
?>
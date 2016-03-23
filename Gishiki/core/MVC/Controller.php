<?php
/**************************************************************************
Copyright 2015 Benato Denis

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*****************************************************************************/    

namespace Gishiki\Core\MVC {
    
    /**
     * The Gishiki base controller. Every controller inherit from this class
     * 
     * @author Benato Denis <benato.denis96@gmail.com>
     */
    class Gishiki_Controller {
        //an array with request details
        protected $receivedDetails;

        //the HTTP Status Code to be sent to the client
        private $httpStatusCode;

        /**
         * Initialize the controller. Each controller MUST call this constructor
         */
        public function __construct() {
            //this is an OK response by default
            $this->httpStatusCode = "200";
        }

        /**
         * Set a new HTTP status code for the response
         * 
         * @param mixed $code the new HTTP status code, can be given as a number or as a string
         * @throws Exception the exception that prevent the new status to be used
         */
        protected function ChangeHTTPStatus($code) {
            //check the given input
            $codeType = gettype($code);
            if (($codeType != "string") && ($codeType != "integer")) {
                throw new \Exception("The http error code must be given a string or an integer value, ".$codeType." given");
            }

            //make the $code a string-type variable
            $code = (string)$code;

            //check if the given code is a valid one
            if (!array_key_exists("".$code, getHTTPResponseCodes())) {
                throw new \Exception("The given error code is not recognized as a valid one");
            }

            //change the status code
            $this->httpStatusCode = $code;
        }
        
        /**
         * Perform a call to the specified interface controller over the HTTP protocol.
         * The service host is the server that exposes the given interface controllers as an API.
         * 
         * @param string $service_name the name of the interface controller
         * @param string $service_action the name of the subroutine that the interface controller must execute
         * @param array  $service_details additionals details that are required and used by the given subroutine
         * 
         * @return array the result of the subroutin execution
         */
        protected function API_Call($service_name, $service_action, $service_details) /*: array */ {
            //check for data validity
            if (strlen($service_name) == 0) $service_name = "Default";
            if (strlen($service_action) == 0) $service_action = "Index";
            if (gettype($service_details) != "array") $service_details = array();
            
            //build the request URL
            $request_URL = \Gishiki\Core\Environment::GetCurrentEnvironment()->GetConfigurationProperty("INTERFACE_SERVICE_HOST")."/API/".$service_name."/".$service_action;

            //pass request details as a json
            $request_details = json_encode($service_details);
            
            // create a new cURL resource
            $api_call = curl_init();

            //build the cURL request to be performed
            curl_setopt_array($api_call, [ 
                CURLOPT_POST => true, 
                CURLOPT_HEADER => false, 
                CURLOPT_URL => $request_URL,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_POSTFIELDS => http_build_query(array("data" => $request_details)) 
            ]);
            
            // grab URL and pass it to the browser
            $result_details = curl_exec($api_call);
            if ($result_details === false) {
                throw new APICallException(curl_error($api_call), curl_errno($api_call));
            }
            
            // close cURL resource, and free up system resources
            curl_close($api_call);
            
            //return the API call result
            return json_decode($result_details);
        }
        
        /**
         * Return what the request detail at the given index
         * 
         * @param integer $argumentNumber the index number of the searched argument
         * @return mixed Data on the given index or NULL
         */
        protected function GetRequestDetail($argumentNumber) {
            if (isset($this->receivedDetails[$argumentNumber])) {
                return $this->receivedDetails[$argumentNumber];
            } else {
                return NULL;
            }
        }
        
        /**
         * Return the number of additional details that the client gave to the 
         * called controller
         * 
         * @return integer the number of received details
         */
        protected function RequestDetailsCount() {
            return count($this->receivedDetails);
        }
        
        /**
         * Change the HTTP status code
         */
        public function __destruct() {
            //get the supported php status code
            $httpStatusList = getHTTPResponseCodes();

            //build the http status code message
            $httpStatusMessage = $httpStatusList[$this->httpStatusCode];
            $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
            header($protocol.' '.$this->httpStatusCode.' '.$httpStatusMessage);

            //set the http status code
            $GLOBALS['http_response_code'] = $this->httpStatusCode;
        }
    }
}
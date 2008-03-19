<?php
/**
 * Pownce APi Class for POSTING data to pownce
 * Written by Kyle Browning[ocyrus]
 * Distrubuted under GNU
 */


class pownceAPI
{
  /**
   * Pownce Address
   * ie. http://api.pownce.com/2.0
   *
   * @var string
   */
  protected $pownce_address;
  
  /**
   * Pownce Username Pase
   * ie. username:pass
   * @var base64_encode(string)
   */
  protected $pownce_userpass;
  
  /**
   * Pownce Datasource
   * ie. /send/message.xml
   * @var String
   */
  protected $pownce_datasource;
  
  /**
   * Pownce Headers
   * Authentication Basic base64 ecodeded username:pass
   * @var array
   */
  protected $headers;
  
  /**
   * Pownce Post options 
   * ie. app_key=appkey&note_to=publix&note_body=Hi
   * @var string
   */
  protected $options;
  /**
   * Our Curl object
   *
   * @var object
   */
  protected $curl_handler;
  
  /**
   * Our Repsonse
   *
   * @var XML,JSON
   */
  protected $response;
  
  protected $type;
  
  /**
   * Our construct method, initializes our Pownce Object
   *
   */
  public function __construct()
  {
    $this->initNew();
  }
  
  /**
   * Sets all values to defaults on construct.
   *
   */
  private function initNew()
  {
    $this->pownce_address       = null;
    $this->pownce_userpass      = null;
    $this->pownce_datasource    = null;
    $this->headers              = array();
    $this->options         = null;
    $this->curl_handler         = curl_init();
    $this->type                 = null;
  }
  
  /**
   * Sets our curl object options
   *
   */
  public function initCurl()
  {
    curl_setopt($this->getPownceCurlHandler(), CURLOPT_RETURNTRANSFER, true);
    curl_setopt($this->getPownceCurlHandler(), CURLOPT_HTTPHEADER, $this->getPownceHeaders());
    if($this->type == 'GET')
    {
      curl_setopt($this->getPownceCurlHandler(), CURLOPT_URL, $this->getPownceAddress() . $this->getPownceDatasource() . '?' . $this->getPownceOptions());
    }
    else 
    {
      curl_setopt($this->getPownceCurlHandler(), CURLOPT_URL, $this->getPownceAddress() . $this->getPownceDatasource());
      curl_setopt($this->getPownceCurlHandler(), CURLOPT_POST, 1);
      curl_setopt($this->getPownceCurlHandler(), CURLOPT_POSTFIELDS, $this->getPownceOptions());
    }
  }
  
  /**
   * Executes our curl object
   *
   */
  public function execCurl()
  {
    $this->setPownceResponse(curl_exec($this->curl_handler));
    curl_close($this->getPownceCurlHandler());
  }
  /**
   * Get POwnce Address 
   *
   * @return stri
   ng
   */
  public function getPownceAddress()
  {
    return $this->pownce_address;
  }
  
  /**
   * Get POwnce UserPass 
   *
   * @return String
   */
  public function getPownceUserPass()
  {
    return $this->pownce_userpass;
  }
  
  /**
   * Get Pownce Datasource
   *
   * @return string
   */
  public function getPownceDatasource()
  {
    return $this->pownce_datasource;
  }
  
  /**
   * Get Pownce Headers
   *
   * @return array
   */
  public function getPownceHeaders()
  {
    return $this->headers;
  }
  
  /**
   * get Pownce PostOptions
   *
   * @return string
   */
  public function getPownceOptions()
  {
    return $this->options;
  }
  
  /**
   * Get Curl Handler Object
   *
   * @return object
   */
  public function getPownceCurlHandler()
  {
    return $this->curl_handler;
  }
  /**
   * Get Pownce Resource
   *
   * @return unknown
   */
  public function getPownceResponse()
  {
    return $this->response;
  }
  /**
   * Get POwnce Type
   *
   * @return string
   */
  public function getPownceType()
  {
    return $this->type;
  }
  
  /**
   * Set Pownce Address
   *
   * @param string $value
   */
  public function setPownceAddress($value)
  {
    $this->pownce_address = $value;
  }
  /**
   * Set Pownce UserPass
   *
   * @param string $value
   */
  public function setPownceUserPass($value)
  {
    $this->pownce_userpass = $value;
  }
  
  /**
   * Set Pownce Datasource
   *
   * @param string $value
   */
  public function setPownceDatasource($value)
  {
    $this->pownce_datasource = $value;
  }
  
  /**
   * Set Pownce headers
   *
   * @param string $value
   */
  public function setPownceHeaders($value)
  {
    array_push($this->headers,$value);
  }
  
  /**
   * Set Pownce Post Options
   *
   * @param unknown_type $value
   */
  public function SetPownceOptions($value)
  {
    $this->options = $value;
  }
  
  /**
   * Set Pownce Curl Handler
   *
   */
  public function setPownceCurlHandler()
  {
    $this->curl_handler = curl_init();
  }
  
  /**
   * Set Pownce Response
   *
   * @param String $value
   */
  public function setPownceResponse($value)
  {
    $this->response = $value;
  }
  /**
   * Set pownce Type
   *
   * @param string $value
   */
  public function setPownceType($value)
  {
    $this->type = $value;
  }
}



?>

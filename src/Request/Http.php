<?php
/**
 * Controller\Request.php
 * The request class is the base class for all types of requests.
 *
 * PHP Version 5.2.0
 * @category  Requests
 * @package   Controller
 * @author    James Phillips <james@dataocd.com>
 * @copyright 2011 DataOCD. All rights reserved.
 * @license   TBD
 * @version   SVN: $Id$
 * @link      http://www.dataocd.com/
 */

/**
 * @namespace
 */
namespace Listr\Request;

/**
 * @category  Requests
 * @package   Controller
 */
 class Http extends Request {
    private $validMethodTypes = array('DELETE','GET','HEAD','OPTIONS','POST','PUT');
    private $isHttps = false;
    private $requestURL;
    private $URLParts;
    private $type;
    private $basePath;
    private $baseURL;
    private $attributes;
    
    public function __construct($URL = NULL) {
        //Need to grab the url here
        //  need to load the put/post and querystrings into the attributes array
        //  need to set the type
        $parts = parse_url($url);
    }
    public function getRequestURL() {
        return $this->requestURL;
    }
    
    //Universal get function. This way, noone else outside of here ever,
    //  need mess with the ugly server globals stuff.
    public function get($key) {
        switch (true) {
            case isset($this->attributes[$key]):
                return $this->attributes[$key];
            case isset($_GET[$key]):
                return $_GET[$key];
            case isset($_POST[$key]):
                return $_POST[$key];
            case isset($_COOKIE[$key]):
                return $_COOKIE[$key];
            case ($key == 'REQUEST_URL'):
                return $this->getRequestUri();
            case isset($_SERVER[$key]):
                return $_SERVER[$key];
            case isset($_ENV[$key]):
                return $_ENV[$key];
            default:
                return null;
        }
    }
}
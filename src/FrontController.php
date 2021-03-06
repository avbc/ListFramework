<?php
/**
 * FrontController.php
 * The FrontController is the main controller that takes the initial requests.
 *
 * PHP Version 5.2.0
 * @category  Controller
 * @package   Controller
 * @author    James Phillips <james@dataocd.com>
 * @copyright 2011 DataOCD. All rights reserved.
 * @license   TBD
 * @version   SVN: $Id$
 * @link      http://www.dataocd.com/
 *
 * @todo This controller should implement the IController interface.
 *       However, I don't really want to do it yet as I would have to supply the full-path to the IController.
 *       Instead, we should have an autoloader that is loaded by default whenever we begin the initial load of 
 *       the list proceedures.  We can achieve this with the auto_prepend option in the php.ini file.  However,
 *       I'm not sold that we should do this.  Instead, we probably want to create the initial autloading from the
 *       file that is listed as our initial request file (/api/rest/request.php or whatever) and have it just create
 *       our initial autoload there via the Autoload class.
 *       -Jonathon Hibbard
 */

namespace Lists;
/**
 * @category  Core
 * @package   Loader
 */
class FrontController {
    /**
      * The package router used to find and load the packages. Parses the 
      * information contained in the Request to determine what package to use.
      */
    protected $router;
 
    /**
     * Holds the Reponse that will be built/returned back to the requester. 
     */
//    protected $response;
 
    /**
      * Pointer to the package dispatcher. The dispatcher is responsible for
      *  finding the proper package/controller and creating an instance. Then
      *  calling its execute() function.
      * @var \List\Controller\Dispatcher
      */
    protected $dispatcher = null;
    
    protected $base_uri = null;
    
    /**
      * We only want one of these guys.
      * @var \List\Controller\FrontController
      */
    protected static $instance = null;
/*
    protected $request = null;
*/
    private function __construct() {}
    
    //Just make sure we cant do this... singleton
    private function __clone() {}
   
    /**
     * @edit Jonathon Hibbard
     * Updated to be a static and changed check from null to !isset
     */ 
    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @author Jonathon Hibbard
     * Creatied a setter for defining the protected properties.
     * @todo determine if these vars really need to be protected or if instead they should be private/public...
     */
    public function __set($key, $value) {
      $this->$key = $value;
    }

    /**
     * @author Jonathon Hibbard
     * Created a getter for accessing the protected properties.
     * @todo @see self::__set
     */ 
    public function __get($key) {
      return $this->$key;
    }

    /**
     * @author James Phillips
     * Used to startup the server.
     *
     * @edit Jonathon Hibbard
     * changed all set methods to instead directly set the local object's variable to the value.
     * changed the default value of path to be null by default.
     */
    public static function run($base_uri = NULL) {
        /** 
         * @edit Jonathon Hibbard
         * I assume these vars are going to be used elsewhere? Maybe in an object that is creating an instance of the FrontendController?
         * If not, why are we setting this up.  I realize the purpose of the instances, but the purpose of storing them in this object is not clear.
         */

        $front = \Lists\FrontController::getInstance();
        $front->base_uri   = $base_uri;
        $front->router     = new Router\Rewrite();
        $front->dispatcher = new Dispatcher();
        $front->execute();
        //echo $front;
    }

    /**
     * @author James Phillips
     * Takes a request, if null will create the default request using the HTTP info
     * @return unknown $response // Returns the response.
     * 
     * @edit Jonathon Hibbard
     * Changed default value of request to be null.  Changed check to see if it is set or not instead of explicit null check.
     */ 
    public function execute($request = null) {

        if(!isset($request)) { 
            $request = new Request\Http();
        }

        try {
            $response = $this->router->route($request);       
        } catch (\Exception $e) {
            $response->addException($e);
        }

        return $response;
    }
}
?>
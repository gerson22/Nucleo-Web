<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 8/26/14
 * Time: 6:23 PM
 */

abstract class API
{
    /**
     * Property: method
     * The HTTP method this request was made in, either GET, POST, PUT or DELETE
     */
    protected $method = '';
    /**
     * Property: controlador
     * el controlador solicitado en la URI. eg: /usuario
     */
    protected $controlador = '';
    /**
     * Property: verbo
     * Descriptor adicional en la URI, es opcional,
     * un metodo a ejecutar. eg: /usuario/existe
     */
    protected $verbo = '';
    /**
     * Property: args
     * Any additional URI components after the endpoint and verb have been removed, in our
     * case, an integer ID for the resource. eg: /<endpoint>/<verb>/<arg0>/<arg1>
     * or /<endpoint>/<arg0>
     */
    protected $args = Array();
    /**
     * Property: file
     * Stores the input of the PUT request
     */
    protected $file = Null;

    /**
     * Constructor: __construct
     * Allow for CORS, assemble and pre-process the data
     */
    public function __construct($request)
    {
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");

        $this->args = explode('/', rtrim($request, '/'));
        $this->controlador = array_shift($this->args);
        if (array_key_exists(0, $this->args) && !is_numeric($this->args[0]))
        {
            $this->verbo = array_shift($this->args);
        }

        $this->method = $_SERVER['REQUEST_METHOD'];
        if ($this->method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER))
        {
            if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
                $this->method = 'DELETE';
            } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
                $this->method = 'PUT';
            } else {
                throw new Exception("Unexpected Header");
            }
        }

        switch($this->method) {
            case 'DELETE':
            case 'POST':
                $this->request = $this->_cleanInputs($_POST);
                break;
            case 'GET':
                $this->request = $this->_cleanInputs($_GET);
                break;
            case 'PUT':
                $this->request = $this->_cleanInputs($_GET);
                $this->file = file_get_contents("php://input");
                break;
            default:
                $this->_response('Invalid Method', 405);
                break;
        }
    }

    /** ------------------ Segunda parte ------------------ */

    public function processAPI()
    {
        // Obtenemos el controladors necesario ($this->controlador)
        $nombre_controlador = $this->controlador."Controlador";
        if(class_exists($nombre_controlador))
        {
            $controlador = new $nombre_controlador();
            /** @noinspection PhpUndefinedMethodInspection */
            $response =  $controlador->procesar($this->method, $this->verbo, $this->args);
            switch($response)
            {
                case 401:
                    header('WWW-Authenticate: Basic realm="PlataformaMEZE"');
                    return $this->_response([], 401);
                    break;
                case 404:
                    return $this->_response([], 404);
                    break;
                default:
                    return $this->_response($response);
                    break;
            }
        }
        return $this->_response([], 404);
    }

    private function _response($data, $status = 200)
    {
        header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
        return $data;
    }

    private function _cleanInputs($data)
    {
        $clean_input = Array();
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $clean_input[$k] = $this->_cleanInputs($v);
            }
        } else {
            $clean_input = trim(strip_tags($data));
        }
        return $clean_input;
    }

    private function _requestStatus($code)
    {
        $status = array(
            200 => 'OK',
            404 => 'Not Found',
            401 => 'Error de autorizacion',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        );
        return ($status[$code])?$status[$code]:$status[501];
    }
}
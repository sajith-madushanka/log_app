<?php
namespace Src\Controller;

use Src\TableGateways\LogsGateway;

class LogsController {

    private $db;
    private $requestMethod;
    private $userId;

    private $LogsGateway;

    public function __construct($db, $requestMethod)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;

        $this->LogsGateway = new LogsGateway($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                $response = $this->getAllLogs();
                break;
            case 'POST':
                $response = $this->createLogFromRequest();
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getAllLogs()
    {
        
        $result = $this->LogsGateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createLogFromRequest()
    {
        // return $_REQUEST['user_id'];
        //$input = (array) json_decode(file_get_contents('php://input'), TRUE);
        $this->LogsGateway->insert($_REQUEST['user_id'],$_REQUEST['status_code'],$_REQUEST['response'],$_REQUEST['payload'],$_REQUEST['route'],$_REQUEST['date_time'],$_REQUEST['stage']);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = 'Inserted the record';
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}
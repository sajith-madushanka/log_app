<?php
namespace Src\TableGateways;

class LogsGateway {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $statement = "
            SELECT 
                id, user_id, status_code,status
            FROM
                log_data;
        ";

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function insert($user_id,$status_code,$response,$payload,$route,$date_time,$server)
    {
        $statement = "
            INSERT INTO log_data 
                (user_id, status_code,response,payload,route,date_time,server)
            VALUES
                (:user_id, :status_code, :response, :payload, :route, :date_time :server);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'user_id' => $user_id,
                'status_code'  => $status_code,
                'response'  => $response,
                'payload' => $payload,
                'route' => $route,
                'date_time' => $date_time,
                'server' => $server
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
}
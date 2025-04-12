<?php

require_once __DIR__ . '/../Services/TransactionService.php';

class TransactionController {

    public function transfer($data,$user){

        if (!isset($data['from_account']) || !isset($data['to_account']) || !isset($data['amount']) || !isset($data['reference_number'])) {
            http_response_code(400);
            return json_encode(["status" => "error", "message" => "Missing required fields"]);
        }

        if (!is_numeric($data['amount'])) {
            http_response_code(400);
            return json_encode(["status" => "error", "message" => "Amount must be a valid number"]);
        }

        if($user->getBalance() < $data['amount']){
            http_response_code(400);
            return json_encode(["status" => "error", "message" => "Insufficient balance"]);
        }

        $service = new TransactionService();

        return $service->transfer($data['from_account'], $data['to_account'], $data['amount'], $data['reference_number']);
    }

    public function deposit($data,$user){

        if (!isset($user) || !isset($data['amount'])) {
            http_response_code(400);
            return json_encode(["status" => "error", "message" => "Missing required fields"]);
        }

        if (!is_numeric($data['amount'])) {
            http_response_code(400);
            return json_encode(["status" => "error", "message" => "Amount must be a valid number"]);
        }

        $service = new TransactionService();


        return $service->deposit($user, $data['amount']);
    }

    public function withdraw($data){
        if (!isset($data['account_number']) || !isset($data['amount']) || !isset($data['current_balance'])) {
            http_response_code(400);
            return json_encode(["status" => "error", "message" => "Missing required fields"]);
        }

        if (!is_numeric($data['amount']) || !is_numeric($data['current_balance'])) {
            http_response_code(400);
            return json_encode(["status" => "error", "message" => "Amount and Current Balance must be valid numbers"]);
        }

        $service = new TransactionService();

        return $service->withdraw($data['account_number'], $data['current_balance'], $data['amount']);
    }
}

?>

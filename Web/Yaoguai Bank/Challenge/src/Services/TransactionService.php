<?php
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/SubUser.php';
require_once __DIR__ . '/../Repositories/TransactionsRepository.php';
require_once __DIR__ . '/../Repositories/UsersRepository.php';

class TransactionService {
    
    public function __construct(){}

    public function transfer($fromAccount, $toAccount, $amount,$reference) {
        $fromAccount = intval($fromAccount);
        $toAccount = intval($toAccount);
        $amount = intval($amount);

        if ($amount <= 0) {
            http_response_code(400);
            echo json_encode(["Status" => "failed", "Msg" => "Amount must be greater than 0"]);
            return;
        }
        
    
        $internalApiUrl = "http://internal-services:5000/transfer";

        
    
        $url = "$internalApiUrl?reference_number=$reference&from_account=$fromAccount&to_account=$toAccount&amount=$amount";
    
        $ch = curl_init();
    
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
        ]);
    
        $response = curl_exec($ch);
    
        if (curl_errno($ch)) {
            http_response_code(500);
            echo json_encode(["Status" => "failed", "Msg" => "Internal API error: " . curl_error($ch)]);
            curl_close($ch);
            return;
        }
    
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    
        if ($statusCode !== 200) {
            http_response_code($statusCode);
            echo json_encode(["Status" => "failed", "Msg" => "Transfer failed with code $statusCode"]);
            return;
        }

        if($statusCode === 200){
            $this->checkPremium($fromAccount);
            $this->checkPremium($toAccount);
        }
    
        return $response;
    }

    private function checkPremium($AccountNumber){
        $res = UsersRepository::getUserDataByAccountnum($AccountNumber);
        if($res->num_rows>0){
        $res = $res->fetch_all()[0];
        $user = new User($res[0],
                $res[1],
                $res[2],
                $res[3],
                $res[5],
                $res[6]);

        if($user->getBalance() > 10000000){
            UsersRepository::upgradeToPremium($AccountNumber,1);
        }
        else{
            UsersRepository::upgradeToPremium($AccountNumber,0);
        }
        }
    }
    

    public function deposit($user, $amount){
        if($user){
            if($amount <= 0){
            http_response_code(400);
            echo json_encode(array("Status" => "failed", "Msg" => 'Amount Must Be Greater than 0'));
            return;
            }
            echo json_encode(["status" => "failed", "message" => "We Will Add this Feature Soon!!"]);
            return;
            // TO-DO: implement deposit feature into internal system
            /*
            $result = TransactionsRepository::addBalance($user->getAccountNumber(),$amount);
            if($result > 0){
                $result2 = TransactionsRepository::addTransactionHistory($user->getAccountNumber(),$amount);
                if($result2>0){
                    return json_encode(["status" => "success", "message" => "Amount Addedd", "accountNumber" => $user->getAccountNumber()]);
                }
                else{
                    return json_encode(["status" => "failed", "message" => "Error while adding transactions History"]);
                }
            }else{
                return json_encode(["status" => "failed", "message" => "Error while adding balance"]);
            }*/
        }
        else{
            echo json_encode(["status" => "failed", "message" => "Error while adding balance"]);
            return;
        }
    }

    public function getAccountNumbers($currentAccountNumber){
        $transactionResult = TransactionsRepository::getAccountsNumber();
        $transactions = [];
    
        if ($transactionResult->num_rows > 0) {
            while ($row = $transactionResult->fetch_assoc()) {
                #echo $row['AccountNumber']; 
                if ($row['AccountNumber'] != $currentAccountNumber) {
                    $transactions[] = $row;
                }
            }
        }
        return $transactions;
    }
    
    public function withdraw($accountNumber,$currentBalance, $amount){

        // TO-DO: implement withdraw feature into internal system
        if ($amount <= 0) {
            http_response_code(400);
            echo json_encode(array("Status" => "failed", "Msg" => 'Amount must be greater than 0'));
            return;
        }
        if($currentBalance < $amount){
            http_response_code(400);
            echo json_encode(array("Status" => "failed", "Msg" => 'Insufficient balance'));
            return;
        }

        $balanceUpdateRes = TransactionsRepository::subtractBalance($accountNumber,$amount);
        if($balanceUpdateRes<=0){
            http_response_code(500);
            echo json_encode(array("Status" => "failed", "Msg" => 'Error while updating balance'));
            return;
        }
        $historyres = TransactionsRepository::addTransactionHistory($accountNumber,-$amount);

        if ($historyres <= 0) {
            http_response_code(500);
            echo json_encode(array("Status" => "failed", "Msg" => 'Error while adding transaction history'));
            return;
        }

        return json_encode([
            "status" => "success", 
            "message" => "Withdrawal successful", 
            "accountNumber" => $accountNumber
        ]);
    }

}
?>

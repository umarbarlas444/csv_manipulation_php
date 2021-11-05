<?php
    $sourceFileName = "source.csv";
    $tempCSV = "temp.csv";
    try {
        $sFileHandler = fopen($sourceFileName, "r") or die("Unable to open File!");
        $tFileHandler = fopen($tempCSV, "w") or die("Unable to create helper file");
        $userIP = getUserIP();
        $isFound = false;
        $isUpdated = false;


        while(($row = fgetcsv($sFileHandler)) !== false && !$isFound){
            if($row[1]){
                if($userIP == $row[1]){
                    echo $row[0];
                    $isFound = true;
                }
            }else if(!$isUpdated){
                $row[1] = $userIP;
                echo $row[0];
                $isUpdated = true;
                //First empty row found
            }
            fputcsv($tFileHandler, $row);
        }

        fclose($sFileHandler);
        fclose($tFileHandler);
        unlink($sourceFileName);
        rename($tempCSV, $sourceFileName);
    } catch (\Throwable $th) {
        var_dump($th);
    }

    function getUserIP()
    {
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
                $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
                $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP)){
            $ip = $client;
        }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
            $ip = $forward;
        }else{
            $ip = $remote;
        }

        return $ip;
    }






<?php
$url = "https://ws.sandbox.pagseguro.uol.com.br/pre-approvals/request?email=email@email.com&token=ABDHJAISDIAJDIAJDIASJDIAJDIASJIA";
$xml = '<?xml version="1.0" encoding="ISO-8859-1" standalone="yes"?>
    <preApprovalRequest>
     <redirectURL>http://www.danielpinon.com.br/retorno.php</redirectURL>
     <reviewURL>http://www.site.com.br/revisao.php</reviewURL>
     <reference>REF1234</reference>
     <sender>
       <name>Nome do Cliente</name>
       <email>danspinon@danielpinon.com.br</email>
       <phone>
         <areaCode>82</areaCode>
         <number>99999999</number>
       </phone>
       <address>
       <street>Avenida Brigadeiro Faria Lima</street>
         <number>1384</number>
         <complement>1 Andar</complement>
         <district>Jardim Paulistano</district>
         <postalCode>01452002</postalCode>
         <city>São Paulo</city>
         <state>SP</state>
         <country>BRA</country>
       </address>
     </sender>
     <preApproval>
       <charge>auto</charge>
       <name>Seguro contra roubo do Notebook</name>
       <details>Todo dia 28 será cobrado o valor de R$100,00 referente ao seguro contra
                roubo de Notebook
        </details>
       <amountPerPayment>19.90</amountPerPayment>
       <period>Monthly</period>
       <finalDate>2019-01-21T00:00:000-03:00</finalDate>
       <maxTotalAmount>2000.00</maxTotalAmount>
       </preApproval>
     </preApprovalRequest>
';


$xml = str_replace("\n", '', $xml);
$xml = str_replace("\r",'',$xml);
$xml = str_replace("\t",'',$xml);


$curl = curl_init($url);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, Array('Content-Type: application/xml; charset=ISO-8859-1',"Accept:application/vnd.pagseguro.com.br.v3+xml;charset=ISO-8859-1"));
curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
$xml= curl_exec($curl);


if($xml == 'Unauthorized'){
   // header('Location: paginaDeErro.php');
    echo 'Unauthorized';
    exit;
}

curl_close($curl);

$xml_retorno= simplexml_load_string($xml);

if(count($xml_retorno -> error) > 0)
{
    print_r($xml_retorno -> error).'<br>';
    //header('Location: paginaDeErro.php');
    exit;
}

$xml    = json_encode($xml_retorno);
$array  = json_decode($xml,TRUE);
//print_r($array);

header('Location:  https://sandbox.pagseguro.uol.com.br/v2/pre-approvals/request.html?code='.$array['code']);

?>

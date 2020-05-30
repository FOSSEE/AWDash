<?php
namespace Src\Controller;
use Src\Controller\awfile;

class WebsiteController {

    private $requestMethod;
    private $websiteId;
    private $time;
    private $sno=1;
    private $sites;
    private $result = array();

    public function __construct($requestMethod, $time, $website)
    {
        $this->requestMethod = $requestMethod;
        $this->website = (int)$website;
        $this->time = $time;
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                $response = $this->getData($this -> time, $this -> website);
                break;
            case 'POST':
                $response = $this->methodNotAllowed();
                break;
            /*case 'PUT':
                $response = $this->updateUserFromRequest($this->userId);
                break;
            case 'DELETE':
                $response = $this->deleteUser($this->userId);
                break;
            default:
                $response = $this->notFoundResponse();
                break;*/
        }

        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
  }

    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }

    private function methodNotAllowed()
    {
        $response['status_code_header'] = 'HTTP/1.1 405 Method Not Allowed';
        $response['body'] = null;
        return $response;
    }

    private function getData($time, $website)
    {
        $sd = (int)substr($time, 0, 2);
        $ed = (int)substr($time, 8, 2);
        $sm = (int)substr($time, 2, 2);
        $em = (int)substr($time, 10, 2);
        $sy = (int)substr($time, 4, 4);
        $ey = (int)substr($time, 12, 4);

        $this -> sites = json_decode(file_get_contents('./sites.json'),true);

      if($sd!==null && $ed!==null && $sm!==null && $em!==null && $sy!==null && $ey!==null)
      {
       if($website !== 0)
       {
        if(gettype($website) == 'integer')
        {
         if($website > 0 && $website  <= sizeof($this->sites['sites']))
           $website = array($this->sites['sites'][$website - 1]);
         else
           return $this -> notFoundResponse();
         }
        else{
         return $this -> notFoundResponse();
       }
     }
      else{
     $website = $this -> sites['sites'];
     }

     foreach ($website as $web)
     {
     $url = 'https://'.$web['domain'].'/data/awstats/awstats.php?sd='.$sd.'&ed='.$ed.'&sm='.$sm.'&em='.$em.'&sy='.$sy.'&ey='.$ey.'&web='.$web['domain'];
     $contents = json_decode(file_get_contents($url));
     $contents -> sno = $this -> sno;
     $this -> sno += 1;
     $contents -> awstats = $web['awstats'];
     array_push($this -> result, $contents);
     }

     if (empty($this -> result)) {
            return $this->notFoundResponse();
        }

//    echo "Date: ".$sd.$sm.$sy.$ed.$em.$ey;
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode($this -> result);
    $response['dataType'] = 'json';
    return $response;
   }
  }

}

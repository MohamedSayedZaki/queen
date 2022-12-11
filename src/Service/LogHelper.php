<?php
namespace App\Service;

class LogHelper{

    public function __construct(LogActions $log){
        $this->log = $log;
    }

    public function getAction($path, $type = '', $page = 1)
    {
        $this->log->setPage($page);
        switch ($type) {
            case 'first':
                return $this->log->rewind($path);
                break;
            case 'previous':
                return $this->log->previous($path);
                break;
            case 'next':
                return $this->log->next($path);
                break;
            case 'end':
                return $this->log->end($path);
                break;                                            
            default:
                return $this->log->getLogs($path);
                break;
        }
    }        
}
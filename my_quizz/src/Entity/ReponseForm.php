<?php

namespace App\Entity;


class ReponseForm
{
    protected $response;

    public function getResponse(){
        return $this->response;
    }
    public function setResponse($response){
        $this->response = $response;
    }

}
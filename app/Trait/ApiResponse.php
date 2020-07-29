<?php
namespace App\Traits;


trait ApiResponse{

    public function successResponse($data,$code = 200,$msj = ''){

        //return response()->json(array("data" => $data, "code" => $code, "msj"=> $msj),$code);
        return response()->json(['data' => $data ]);

    }

    public function errorResponse($data,$code = 500,$msj = ''){

        return response()->json(array("data" => $data, "code" => $code, "msj"=> $msj),$code);

    }

}

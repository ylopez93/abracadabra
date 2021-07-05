<?php

namespace App\Http\Controllers\api;

use App\ApplicationJob;
use App\ApplicationJobImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreApplicationJobPost;
use App\Http\Requests\StoreApplicationJobPut
use App\Http\Controllers\api\ApiResponseController;

class ApplicationJobController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $applications = ApplicationJob::all();
        return $this->successResponse(['applications'=>$applications,'message'=>'Application Jobs retrieved successfully.']);
    }

    public function applicationJobsAproveMenssenger()
    {
        $applications = ApplicationJob::
        join('application_jobs_images', 'application_jobs.id', '=', 'application_jobs_images.application_jobs_id')
        ->select('application_jobs.name as nombre','application_jobs.surname','application_jobs.phone','application_jobs.email','application_jobs.ci',
        'application_jobs.address','application_jobs.vehicle_registration','application_jobs_images.name as image')
        ->orderBy('application_jobs.created_at', 'desc')
        ->where('application_jobs.state','aprobado')
        ->where([
            ['application_jobs.state', '=','aprobado'],
            ['application_jobs.employee_type','=','mensajero']])
        ->whereNull('application_jobs.deleted_at')
        ->whereNull('application_jobs_images.deleted_at')
        ->get();

        return $this->successResponse(['applications'=>$applications,'message'=>'Application Jobs retrieved successfully.']);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $v_application = new StoreApplicationJobPost();
        $validator = $request->validate($v_application->rules());
        if($validator){
           $application = new ApplicationJob();
           $application->name = $request['name'];
           $application->surname = $request['surname'];
           $application->ci = $request['ci'];
           $application->phone = $request['phone'];
           $application->email = $request['email'];
           $application->address = $request['address'];
           $application->vehicle_registration = $request['vehicle_registration'];
           $application->state = $request['state'];
           $application->employee_type = $request['employee_type'];
           $application->save();

           if ($request->hasFile('image_carnet')){

            $filename = time() .".". $request->image->extension();
            $request->image->move(public_path('images'),$filename);
            $applicationJobsImage = new ApplicationJobImage();
            $applicationJobsImage->name = $filename;
            $applicationJobsImage->application_jobs_id = $application->id;
            $applicationJobsImage->save();
           }

           if($request['employee_type'] =='mensajero'){
            if ($request->hasFile('image_chapa')){

            $filename = time() .".". $request->image->extension();
            $request->image->move(public_path('images'),$filename);
            $applicationJobsImage = new ApplicationJobImage();
            $applicationJobsImage->name = $filename;
            $applicationJobsImage->application_jobs_id = $application->id;
            $applicationJobsImage->save();
            }
           }

        return $this->successResponse(['application'=>$application, 'message'=>'Application Jobs  created successfully.']);

        }
        return response()->json([
            'message' => 'Error al validar'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ApplicationJob  $applicationJob
     * @return \Illuminate\Http\Response
     */
    public function show(ApplicationJob $applicationJob)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ApplicationJob  $applicationJob
     * @return \Illuminate\Http\Response
     */
    public function edit(ApplicationJob $applicationJob)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ApplicationJob  $applicationJob
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ApplicationJob $applicationJob)
    {
        $v_application = new StoreApplicationJobPut();
        $validator = $request->validate($v_application->rules());
        if($validator){
           $application = new ApplicationJob();
           $application->name = $request['name'];
           $application->surname = $request['surname'];
           $application->ci = $request['ci'];
           $application->phone = $request['phone'];
           $application->email = $request['email'];
           $application->address = $request['address'];
           $application->vehicle_registration = $request['vehicle_registration'];
           $application->state = $request['state'];
           $application->employee_type = $request['employee_type'];
           $application->save();

           if ($request->hasFile('image_carnet')){

            $filename = time() .".". $request->image->extension();
            $request->image->move(public_path('images'),$filename);
            $applicationJobsImage = new ApplicationJobImage();
            $applicationJobsImage->name = $filename;
            $applicationJobsImage->application_jobs_id = $application->id;
            $applicationJobsImage->save();
           }

           if($request['employee_type'] =='mensajero'){
            if ($request->hasFile('image_chapa')){

            $filename = time() .".". $request->image->extension();
            $request->image->move(public_path('images'),$filename);
            $applicationJobsImage = new ApplicationJobImage();
            $applicationJobsImage->name = $filename;
            $applicationJobsImage->application_jobs_id = $application->id;
            $applicationJobsImage->save();
            }
           }

        return $this->successResponse(['application'=>$application, 'message'=>'Application Jobs  created successfully.']);

        }
        return response()->json([
            'message' => 'Error al validar'
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ApplicationJob  $applicationJob
     * @return \Illuminate\Http\Response
     */

    public function destroyAplicationJob(Request $request)
    {
        $applicationJob = ApplicationJob::findOrFail($request['id']);
        $applicationJob->delete();
        return $this->successResponse(['message'=>'Aplication Job deleted successfully.']);
    }
}

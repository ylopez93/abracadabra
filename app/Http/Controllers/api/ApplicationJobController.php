<?php

namespace App\Http\Controllers\api;

use App\ApplicationJob;
use App\ApplicationJobImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreApplicationJobPost;
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
        return $this->successResponse([$applications,'Application Jobs retrieved successfully.']);
    }

    public function applicationJobsAprove(Request $request)
    {
        $applications = ApplicationJob::
        join('application_jobs_images', 'application_jobs.id', '=', 'application_jobs_images.application_jobs_id')
        ->select('application_jobs.name','application_jobs.surname','application_jobs.phone','application_jobs.email','application_jobs.ci',
        'application_jobs.address','application_jobs.vehicle_registration','application_jobs_images.name','application_jobs_images.type')
        ->orderBy('application_jobs.created_at', 'desc')
        ->where('application_jobs.state','aprobado')
        ->whereNull('application_jobs.deleted_at')
        ->whereNull('application_jobs_images.deleted_at')
        ->get();

        return $this->successResponse(['applications'=>$applications,'Application Jobs retrieved successfully.']);
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

           if($request['type'] == 'mensajero'){

            $filename = time() .".". $request->image->extension();
            $request->image->move(public_path('images'),$filename);
            $applicationJobsImage = new ApplicationJobImage();
            $applicationJobsImage->name = $filename;
            $applicationJobsImage->application_jobs_id = $application->id;
            $applicationJobsImage->type = $request['type'];
            $applicationJobsImage->save();

           }
           else{

            $filename = time() .".". $request->image->extension();
            $request->image->move(public_path('images'),$filename);
            $applicationJobsImage = new ApplicationJobImage();
            $applicationJobsImage->name = $filename;
            $applicationJobsImage->application_jobs_id = $application->id;
            $applicationJobsImage->type = $request['type'];
            $applicationJobsImage->save();
           }

        return $this->successResponse([$application, 'Application Jobs  created successfully.']);

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
        $v_product = new StoreApplicationJobPost();
        $validator = $request->validate($v_product->rules());
        if($validator){
           $applicationJob->name = $request['name'];
           $applicationJob->surname = $request['surname'];
           $applicationJob->ci = $request['ci'];
           $applicationJob->phone = $request['phone'];
           $applicationJob->email = $request['email'];
           $applicationJob->address = $request['address'];
           $applicationJob->vehicle_registration = $request['vehicle_registration'];
           $applicationJob->state = $request['state'];
           $applicationJob->employee_type = $request['employee_type'];
           $applicationJob->save();

           if ($request->hasFile('image')){

            $images = DB::select('select application_jobs_images.* from application_jobs_images where application_jobs_images.application_jobs_id. = ?', [$applicationJob->id]);

            foreach ($images as $image) {
                if($request['type'] == 'mensajero'){

                    $filename = time() .".". $request->image->extension();
                    $request->image->move(public_path('images'),$filename);
                    $image->name = $filename;
                    $image->application_jobs_id = $applicationJob->id;
                    $image->type = $request['type'];
                    $image->save();

                   }
                   else{

                    $filename = time() .".". $request->image->extension();
                    $request->image->move(public_path('images'),$filename);
                    $applicationJobsImage->name = $filename;
                    $applicationJobsImage->application_jobs_id = $applicationJob->id;
                    $applicationJobsImage->type = $request['type'];
                    $applicationJobsImage->save();
                   }
            }


           }

        return $this->successResponse([$applicationJob, 'Application Jobs  Update successfully.']);
        }
        return $this->successResponse(['message' => 'Error al validar']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ApplicationJob  $applicationJob
     * @return \Illuminate\Http\Response
     */
    public function destroy(ApplicationJob $applicationJob)
    {
        //
    }
}

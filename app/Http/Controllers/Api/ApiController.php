<?php 

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request as Request;
use Illuminate\Support\Facades\Request as FacadeRequest; 
use Illuminate\Support\Facades\Config as Config;
use Illuminate\Support\Facades\Input as Input;
use App\Http\Models\School;
use App\Http\Models\Route;
use App\Http\Models\TransportDetail;
use App\Http\Models\Attendance;
use App\Http\Models\StudentPoint;

use DB;
use Validator;
class ApiController extends Controller {  

    protected $api;

    public function __construct() {
        $this->api = FacadeRequest::segment(3);           
    }

    public function getpostdata($data) {

        $post = Input::json()->all();
        if (!isset($post) || empty($post))
            $post = Input::all();
        return $post;
    }


    public function getReponse($status, $code, $result) {

        $response['status']  = $status;
        $response['code']    = $code;
        $response['message'] = config('errorcode.code')[$code];
        $response['api']     = $this->api;
        $response['result']  = $result;
        return $response;
    }


    public function getSchoolDetails() 
    {
        try 
        {
            $result      = School::select('id as school_id','name')->get();   
            if(isset($result) && !empty($result))
            {                         
               $response = $this->getReponse(TRUE,1, $result);                  
            } 
            else 
            {
               $response = $this->getReponse(FALSE, 3, FALSE); 
            }
            return $response;
        } 
        catch (\Exception $e) 
        {
            $message = ['status'=>FALSE,'code'=>$e->getCode(),'message'=>$e->getMessage()];
            $response = $this->getReponse(FALSE, 6, $message);
            return $response;
        }
    }

    public function getRoutes(Request $request) 
    {
        try 
        {
            $school_id   = $request->get('school_id');           
            if(isset($school_id) && !empty($school_id))
            {                         
               $result      = Route::select('id as route_id','route_name')->where('school_id',$school_id)->get();   
               if($result)
                    $response = $this->getReponse(TRUE,1, $result);                  
                else
                    $response = $this->getReponse(FALSE,4, FALSE);                  
            } 
            else 
            {
               $response = $this->getReponse(FALSE, 3, FALSE); 
            }
            return $response;
        } 
        catch (\Exception $e) 
        {
            $message = ['status'=>FALSE,'code'=>$e->getCode(),'message'=>$e->getMessage()];
            $response = $this->getReponse(FALSE, 6, $message);
            return $response;
        }
    }

    public function getTripDetails(Request $request) 
    {
        try 
        {
            $school_id   = $request->get('school_id');               
            if(isset($school_id) && !empty($school_id))
            {                         
               $result      = TransportDetail::select('school_id','school_type','id')->where('school_id',$school_id)->get();   
               if($result)
                    $response = $this->getReponse(TRUE,1, $result);                  
                else
                    $response = $this->getReponse(FALSE,4, FALSE);               
            } 
            else 
            {
               $response = $this->getReponse(FALSE, 3, FALSE); 
            }
            return $response;
        } 
        catch (\Exception $e) 
        {
            $message = ['status'=>FALSE,'code'=>$e->getCode(),'message'=>$e->getMessage()];
            $response = $this->getReponse(FALSE, 6, $message);
            return $response;
        }
    }

    public function submitAttendance(Request $request) 
    {
        try 
        {
            $post = $this->getpostdata($_REQUEST); 
            if(isset($post['attendance']) && !empty($post['attendance'])) {
            
                $post['attendance'] = json_decode($post['attendance']);
                foreach ($post['attendance'] as $key => $value) {
                    $oAttendance = new Attendance();
                    $oAttendance->mode_of_travel = $value->mode_of_travel;
                    $oAttendance->route_id       = $value->route_id;
                    $oAttendance->status         = $value->status;
                    $oAttendance->student_id     = $value->student_id;
                    $oAttendance->student_name   = $value->student_name;
                    $oAttendance->trip           = $value->trip;
                    if(isset($value->school_id) && !empty($value->school_id))
                        $oAttendance->school_id      = $value->school_id;
                    $oAttendance->att_date       = date('Y-m-d H:i:s');   
                    $result = $oAttendance->save();                 
                } 
                if($result == TRUE) {
                    $response = $this->getReponse(TRUE, 1, TRUE); 
                } else {
                    $response = $this->getReponse(FALSE, 5, FALSE); 
                }                                     
            } else {
               $response = $this->getReponse(FALSE, 3, FALSE); 
            }
            return $response;
        } 
        catch (\Exception $e) 
        {
            $message = ['status'=>FALSE,'code'=>$e->getCode(),'message'=>$e->getMessage()];
            $response = $this->getReponse(FALSE, 6, $message);
            return $response;
        }
    }

    public function getStudentsList() 
    {
        try 
        {
            $post = $this->getpostdata($_REQUEST);              
            if(isset($post) && !empty($post))
            {                         
                $rules = [  'school_id' => 'required',
                            'route_id' => 'required',
                            'school_type_id'=>'required',
                            'mode_of_travel'=>'required',
                        ];

                $validator = Validator::make($post, $rules);
                if ($validator->fails()) {
                    $messages = $validator->messages();
                    $response = $this->getReponse(FALSE, 2, $messages);
                } else {  
                    $result = StudentPoint::getStudentsList($post['school_id'],$post['route_id'],$post['school_type_id'],$post['mode_of_travel']);
                    if($result)
                    {
                        $response = $this->getReponse(TRUE, 1, $result); 
                    } else {
                        $response = $this->getReponse(FALSE, 4, FALSE); 
                    }
                }
            } 
            else 
            {
               $response = $this->getReponse(FALSE, 3, FALSE); 
            }
            return $response;
        } 
        catch (\Exception $e) 
        {
            $message = ['status'=>FALSE,'code'=>$e->getCode(),'message'=>$e->getMessage()];
            $response = $this->getReponse(FALSE, 6, $message);
            return $response;
        }
    }


    public function checkEntry() 
    {
        try 
        {
            $post = $this->getpostdata($_REQUEST);              
            if(isset($post) && !empty($post))
            {                         
                $where = array('mode_of_travel'=>$post['mode_of_travel'],
                          'school_id'     =>$post['school_id'],
                          'route_id'      =>$post['route_id'],
                          'trip'          =>$post['trip'],
                          'att_date'      =>date('Y-m-d')); 
                $result = Attendance::where($where)->get();
                if(count($result) > 0)
                {
                    $response = $this->getReponse(TRUE, 1, TRUE); 
                } else {
                    $response = $this->getReponse(FALSE, 4, FALSE); 
                }
                
            } 
            else 
            {
               $response = $this->getReponse(FALSE, 3, FALSE); 
            }
            return $response;
        } 
        catch (\Exception $e) 
        {
            $message = ['status'=>FALSE,'code'=>$e->getCode(),'message'=>$e->getMessage()];
            $response = $this->getReponse(FALSE, 6, $message);
            return $response;
        }
    }
    

}
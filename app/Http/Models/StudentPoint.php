<?php 
namespace App\Http\Models;
  
use Illuminate\Database\Eloquent\Model;

use DB;
  
class StudentPoint extends Model
{
    
    protected $table = 'student_point';

    public $timestamps = false; 
    
    protected $fillable = ['school_id','student_id', 'school_type','pickup_route_id','pickup_point','drop_route_id','drop_point','flag'];

    public static function getStudentsList($school_id,$route_id,$school_type_id,$trip){

    	if($trip == 1)
    		$condition = " sp.drop_route_id = '".$route_id."'";
    	else
    		$condition = " sp.pickup_route_id = '".$route_id."'";
    	
    	$sql = "SELECT sp.*,sp.school_id,sp.student_id,sd.student_name,sd.class_id,sd.school_type,sd.dob,
                       sd.transport_required,sd.photo,sf.install1_status,sf.install2_status,cd.class,cd.section,
                       cd.id as class_id,
                       p.parent_name,p.email,p.mobile,p.address1,p.address2,p.address3,p.city,p.pincode
    			FROM student_point sp 
    			LEFT JOIN student_details sd ON sd.id=sp.student_id
    			LEFT JOIN student_fee sf ON sf.student_id = sp.student_id
    			LEFT JOIN class_details cd ON cd.id =sd.class_id
    			LEFT JOIN parent p ON p.student_id = sp.student_id AND p.school_id = sp.school_id
    			WHERE sp.school_id = '".$school_id ."'
    			AND sp.school_type = $school_type_id
    			AND $condition";
    	$data = DB::select($sql);
    	
    	return $data;		

    }
    
}
?>
<?php 
namespace App\Http\Models;
  
use Illuminate\Database\Eloquent\Model;

use DB;
  
class Attendance extends Model
{
    
    protected $table = 'attendance';

    public $timestamps = false; 
    
    protected $fillable = ['school_id','student_id', 'student_name','route_id','mode_of_travel','trip','att_date','status'];

    
}
?>
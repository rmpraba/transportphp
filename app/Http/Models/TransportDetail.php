<?php 
namespace App\Http\Models;
  
use Illuminate\Database\Eloquent\Model;

use DB;
  
class TransportDetail extends Model
{
    
    protected $table = 'transport_details';

    public $timestamps = false; 
    
    protected $fillable = ['school_type', 'school_id'];

    
}
?>
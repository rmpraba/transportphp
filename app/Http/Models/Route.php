<?php 
namespace App\Http\Models;
  
use Illuminate\Database\Eloquent\Model;

use DB;
  
class Route extends Model
{
    
    protected $table = 'route';

    public $timestamps = false; 
    
    protected $fillable = ['route_name', 'school_id'];

    
}
?>
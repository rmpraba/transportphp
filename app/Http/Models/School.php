<?php 
namespace App\Http\Models;
  
use Illuminate\Database\Eloquent\Model;

use DB;
  
class School extends Model
{
    
    protected $table = 'md_school';

    public $timestamps = false; 
    
    protected $fillable = ['id','name', 'address', 'logo'];

    
     
}
?>
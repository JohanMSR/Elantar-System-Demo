<?php

namespace App\Services\Projects;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProjectFactory extends Model 
{
    use HasFactory;

    protected static  $finalProjects = array(
        'teamprojects' => TeamProjects::class,
        'ownprojects' =>  OwnProjects::class,
        'teamonlyprojects' => TeamOnlyProjects::class,               
    );

    public static function create(String $type)
    {
        
        $className = self::$finalProjects[$type];//Str::studly($type); //. 'Projects';
        //dd($className);
        if (!class_exists($className)) {
            //throw new InvalidArgumentException('Both arguments must be numbers');
            return "";
        }
        return new $className();
    }
}

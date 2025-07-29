<?php

namespace App\Services\Projects;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

interface Projects 
{
    public function getAnalyst();
    public function getOrder(Request $request);
    public function getProjects(Request $request);    
    public function getConsulta(Request $request);
    public function getTotalize();
    public function getProjectsWithPagination(Request $request, int $perPage = 10);
}

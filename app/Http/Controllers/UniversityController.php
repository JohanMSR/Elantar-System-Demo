<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\C023tDocumento;
use App\Models\C022tVideo;
use App\Models\C025tBlog;
use App\Models\I032tVideoCategoria;

class UniversityController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('IsActive');
    }

    public function index(Request $request)
    {


        return view('dashboard.university.university');

    }

    public function documents(Request $request)
    {


        $documentos = C023tDocumento::orderByDesc('fe_registro')->get();
        return view('dashboard.university.documents')->with('documentos', $documentos);

    }

    public function videos(Request $request)
    {
        $rol = session('rol_userlogin_co');
        $video_destacado = [];
        $sql_destacado = "SELECT
  c022t_videos.*
FROM
  i032t_video_categoria
  INNER JOIN
  i016t_categoria
  ON 
    i032t_video_categoria.co_categoria = i016t_categoria.co_categoria
  INNER JOIN
  c022t_videos
  ON 
    i032t_video_categoria.co_video = c022t_videos.co_video
  INNER JOIN
  c031t_videos_roles
  ON 
    c022t_videos.co_video = c031t_videos_roles.co_video
WHERE
  (c031t_videos_roles.co_rol = $rol and sw_principal = 't' )
ORDER BY c022t_videos.fe_registro DESC
LIMIT 1  OFFSET 0";

        $video_destacado = DB::select($sql_destacado);
        $videos = [];
        $sql_videos = "SELECT
  c022t_videos.*, 
  i016t_categoria.tx_nombre AS \"Categoria\"
FROM
  i032t_video_categoria
  INNER JOIN
  i016t_categoria
  ON 
    i032t_video_categoria.co_categoria = i016t_categoria.co_categoria
  INNER JOIN
  c022t_videos
  ON 
    i032t_video_categoria.co_video = c022t_videos.co_video
  INNER JOIN
  c031t_videos_roles
  ON 
    c022t_videos.co_video = c031t_videos_roles.co_video
WHERE
  c031t_videos_roles.co_rol = $rol
ORDER BY
  i016t_categoria.tx_nombre ASC, 
  c022t_videos.in_orden ASC
";

    $videos = DB::select($sql_videos);

        return view('dashboard.university.videos')
            ->with('video_destacado', $video_destacado)
            ->with('videos', $videos)
            ->with('rol', $rol);
    }

    public function faq(Request $request)
    {

        return view('dashboard.university.faq');

    }

    public function blog(Request $request)
    {

        $noticia_destacada = C025tBlog::where('sw_principal', true)
            ->orderBy('fe_registro', 'desc')
            ->first();

        if (!empty($noticia_destacada)) {
            $lista_noticias = C025tBlog::where('co_blog', '!=', $noticia_destacada->co_blog)
                ->orderByDesc('fe_registro')->get();
        } else {
            $lista_noticias = C025tBlog::orderByDesc('fe_registro')->get();
        }

        return view('dashboard.university.blog')
            ->with('noticia_destacada', $noticia_destacada)
            ->with('lista_noticias', $lista_noticias);

    }

    public function contact(Request $request)
    {

        return view('dashboard.university.contact');

    }
}

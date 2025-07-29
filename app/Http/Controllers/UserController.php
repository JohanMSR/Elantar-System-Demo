<?php

namespace App\Http\Controllers;

use App\Models\C002tUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use App\Services\Documents\DocumentUserService;
use App\Http\Requests\UserCreateRequest;    
use App\Http\Requests\UserEditRequest;

class UserController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
         // Get sorting parameters from the request
        $sortField = $request->input('sort', 'Record_ID'); // Default sort by Record_ID
        $sortOrder = $request->input('order', 'asc'); // Default order is ascending
        $search = $request->input('search'); // Get unified search parameter

         // Validate sortField to prevent SQL injection
        $allowedSortFields = [
            'Record_ID',
            'First_Name',
            'Last_Name',
            'Mobile_Phone',
            'Email',
            'Rol_User',
            'Date_Created',
             'User_Status'
         ];
        if (!in_array($sortField, $allowedSortFields)) {
             $sortField = 'Record_ID';
         }
        // Validate the sort order to prevent SQL injection
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        $query = "
            SELECT
                u.co_usuario AS \"Record_ID\",
                u.tx_primer_nombre AS \"First_Name\",
                u.tx_primer_apellido AS \"Last_Name\",
                u.tx_primer_nombre || ' ' || u.tx_primer_apellido AS \"full_name\",
                u.tx_telefono AS \"Mobile_Phone\",
                u.tx_email AS \"Email\",
                u.co_ryve_usuario AS \"Ryve_User_ID\",
                r.tx_nombre AS \"Rol_User\",
                u.fe_fecha_creacion AS \"Date_Created\",
                s.tx_estatus AS \"User_Status\",
                u.tx_direccion1 AS \"Address_1\",
                u.tx_direccion2 AS \"Address_2\",
                u.tx_zip AS \"Address_ZIP\",
                u.co_sponsor AS \"Sponsor_ID\",
                s_user.tx_primer_nombre || ' ' || s_user.tx_primer_apellido AS \"Sponsor_FullName\",
                u.co_usuario_reclutador AS \"Recruiter_ID\",
                r_user.tx_primer_nombre || ' ' || r_user.tx_primer_apellido AS \"Recruiter_FullName\",
                u.co_office_manager AS \"Office_Manager_ID\",
                om_user.tx_primer_nombre || ' ' || om_user.tx_primer_apellido AS \"Office_Manager_FullName\"
            FROM c002t_usuarios u
            JOIN i007t_roles r ON u.co_rol = r.co_rol
            JOIN i014t_estatus_usuarios s ON u.co_estatus_usuario = s.co_estatus_usuario
            LEFT JOIN c002t_usuarios s_user ON u.co_sponsor = s_user.co_usuario
            LEFT JOIN c002t_usuarios r_user ON u.co_usuario_reclutador = r_user.co_usuario
            LEFT JOIN c002t_usuarios om_user ON u.co_office_manager = om_user.co_usuario
            WHERE u.co_usuario NOT IN (1, 2001, 9999)";

         
        if ($search) {
            $searchWords = explode(' ', $search);
            $searchCondition = implode(' OR ', array_map(function($word) {
                return "(
                    unaccent(LOWER(u.tx_primer_nombre)) LIKE unaccent(LOWER('%$word%')) OR
                    unaccent(LOWER(u.tx_primer_apellido)) LIKE unaccent(LOWER('%$word%')) OR
                    unaccent(LOWER(u.tx_email)) LIKE unaccent(LOWER('%$word%')) OR
                    unaccent(LOWER(u.tx_direccion1)) LIKE unaccent(LOWER('%$word%')) OR
                    unaccent(LOWER(u.tx_direccion2)) LIKE unaccent(LOWER('%$word%')) OR
                    CAST(u.co_usuario AS TEXT) LIKE '%$word%'
                )";
            }, $searchWords));

            $query .= "AND ($searchCondition)";
        }
        $query .= " ORDER BY u.co_usuario DESC";        
        $users = DB::select($query);

        // Check if no results were found after applying search filter
        if (empty($users) && $search) {
            Session::flash('error', 'No se encontraron resultados para los criterios de búsqueda especificados.');
            return redirect()->route('users.index');
        }

        $users = collect($users);

        $perPage = 10;
        $currentPage = $request->get('page', 1);
        $total = $users->count();

        $paginatedData = new LengthAwarePaginator(
           $users->forPage($currentPage, $perPage),
           $total,
           $perPage,
           $currentPage,
           ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('users.index', [
             'paginatedData' => $paginatedData,
             'sortField' => $sortField,
            'sortOrder' => $sortOrder,
         ]);
    }

    public function create()
    {
        $languages = DB::table('i012t_idiomas')
            ->orderBy('tx_idioma')
            ->get();
        
        // Obtener datos adicionales necesarios para el formulario
        $userTypes = DB::table('c013t_tipo_usuarios')
            ->orderBy('tx_tipo_usuario')
            ->get();

        $userStatuses = DB::table('i014t_estatus_usuarios')
            ->orderBy('tx_estatus')
            ->get();

        $roles = DB::table('i007t_roles')
            ->orderBy('tx_nombre')
            ->get();

        $activeUsers = DB::table('c002t_usuarios')
            ->select('co_usuario', 'tx_primer_nombre', 'tx_primer_apellido')
            ->orderBy('tx_primer_nombre')
            ->orderBy('tx_primer_apellido')
            ->get();

        $officeManagers = DB::table('c002t_usuarios')
            ->select('co_usuario', 'tx_primer_nombre', 'tx_primer_apellido')
            ->orderBy('tx_primer_nombre')
            ->orderBy('tx_primer_apellido')
            ->get();

        $offices = DB::table('i008t_oficinas')
            ->select('co_oficina', 'tx_nombre', 'co_zip')
            ->orderBy(DB::raw('LOWER(tx_nombre)'))
            ->get();

        return view('users.create', compact(
            'languages',
            'userTypes',
            'userStatuses', 
            'roles',
            'activeUsers',
            'officeManagers',
            'offices'
        ));
    }

    public function store(UserCreateRequest $request)
    {
        try {
            //$validated = $request->validated();
            
            DB::beginTransaction();

            // Check email again just before insert (double check)
            $emailExists = DB::table('c002t_usuarios')
                ->where('tx_email', $request->tx_email)
                ->exists();

            if ($emailExists) {
                DB::rollBack();
                return back()->withInput()->with('error', 'El correo electrónico ya está registrado en el sistema.');
            }
            

            // Get the office name
            $officeName = DB::table('i008t_oficinas')
                ->where('co_oficina', $request->Office_City_ID)
                ->value('tx_nombre');            
            
            $co_rol = $request->co_rol ?? 0;
            if($request->has('co_tipo_usuario') && $request->co_tipo_usuario == 2){
                $co_rol = 20;                
            }

            // Create new user
            $userId = DB::table('c002t_usuarios')->insertGetId(
                [
                    'tx_primer_nombre' => $request->tx_primer_nombre,
                    'tx_segundo_nombre' => $request->tx_segundo_nombre,
                    'tx_primer_apellido' => $request->tx_primer_apellido,
                    'tx_segundo_apellido' => $request->tx_segundo_apellido,
                    'tx_telefono' => $request->tx_telefono,
                    'tx_email' => $request->tx_email,
                    'tx_password' => Hash::make($request->tx_password),
                    'tx_direccion1' => $request->tx_direccion1,
                    'tx_direccion2' => $request->tx_direccion2,
                    'co_usuario_padre' => $request->co_usuario_padre,
                    'co_usuario_reclutador' => $request->co_usuario_reclutador,
                    'co_estatus_usuario' => $request->co_estatus_usuario,
                    'fe_fecha_creacion' => Carbon::now()->toDateString(),
                    'co_tipo_usuario' => $request->co_tipo_usuario,
                    'tx_zip' => $request->tx_zip,
                    'co_office_manager' => $request->co_office_manager,
                    'co_sponsor' => $request->co_sponsor,
                    'Office_City' => $officeName,
                    'Office_City_ID' => $request->Office_City_ID,
                    'co_rol' => $co_rol,
                    'fe_registro' => Carbon::now(),
                    'fe_nac' => Carbon::createFromFormat('m/d/Y', $request->fe_nac),
                    'co_idioma' => $request->co_idioma,
                ],
                'co_usuario',
            );

            // Update the user with co_ryve_usuario, co_quick_base_id
            //and Document
            $fieldNames= [
                'tx_url_paquete'=> '',
                'tx_url_appempl'=> '',
                'tx_url_drive'=> '',
                'tx_url_formaw9'=> ''
            ];
            $documentService = new DocumentUserService();
            //Upload document
            $documentCreateUser = $this->uploadDocument($request, $documentService,$fieldNames,$userId);
            
            DB::table('c002t_usuarios')
                ->where('co_usuario', $userId)
                ->update([
                    'co_ryve_usuario' => $userId,
                    'co_quick_base_id' => $userId,
                    'tx_url_paquete' => $documentCreateUser['tx_url_paquete'],
                    'tx_url_appempl' => $documentCreateUser['tx_url_appempl'],
                    'tx_url_drive' => $documentCreateUser['tx_url_drive'],
                    'tx_url_formaw9' => $documentCreateUser['tx_url_formaw9'],
                ]);

            if($request->hasFile('tx_url_photo')){                
                $documentService = new DocumentUserService();
                $path_perfil = $this->uploadImagePerfil($request, $documentService,'');  
                if($path_perfil){
                    DB::table('users')
                    ->where('id', $userId)
                    ->update([
                        'image_path' => $path_perfil
                    ]);    
                }                
            }    
            
            DB::commit();

            return redirect()
                ->route('users.index')
                ->with('success', sprintf('El usuario %s %s ha sido creado exitosamente', $request->tx_primer_nombre, $request->tx_primer_apellido));
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', implode("\n", $errors));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating user: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Error al crear el usuario: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $user = DB::table('c002t_usuarios')
        ->join('users', 'c002t_usuarios.co_usuario', '=', 'users.id')
        ->select('c002t_usuarios.*', 'users.image_path')
        ->where('c002t_usuarios.co_usuario', '=', $id)
        ->first();
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'Usuario no encontrado');
        }

        // Get offices ordered by city name
        $offices = DB::table('i008t_oficinas')
            ->select('co_oficina', 'tx_nombre', 'co_zip')
            ->orderBy(DB::raw('LOWER(tx_nombre)')) // Case-insensitive ordering
            ->get();

        // Get user types
        $userTypes = DB::table('c013t_tipo_usuarios')
            ->orderBy('tx_tipo_usuario')
            ->get();

        // Get user statuses
        $userStatuses = DB::table('i014t_estatus_usuarios')
            ->orderBy('tx_estatus')
            ->get();

        // Get roles
        $roles = DB::table('i007t_roles')
            ->orderBy('tx_nombre')
            ->get();

        // Get potential parent users (for parent and recruiter selection)
        $potentialParents = DB::table('c002t_usuarios')
            ->select('co_usuario', 'tx_primer_nombre', 'tx_primer_apellido')
            ->orderBy('tx_primer_nombre')
            ->orderBy('tx_primer_apellido')
            ->get();

        // Get office managers
        $officeManagers = DB::table('c002t_usuarios')
            ->select('co_usuario', 'tx_primer_nombre', 'tx_primer_apellido')
            ->orderBy('tx_primer_nombre')
            ->orderBy('tx_primer_apellido')
            ->get();

        $languages = DB::table('i012t_idiomas')
        ->orderBy('tx_idioma','asc')
        ->get();   

        return view('users.edit', compact(
            'user', 
            'offices', 
            'userTypes', 
            'userStatuses', 
            'roles', 
            'potentialParents',
            'officeManagers',
            'languages'
        ));
    }

    public function show(Request $request)    
    {

        $validated = $request->validate(
            [
                'co_usuario' => 'required|numeric',                
            ],
            [
                'co_usuario.required' => 'El codigo de usuario es obligatorio.',
                'co_usuario.numeric' => 'El codigo del usuario debe ser un numero entero.',
                
            ],
        );
        
        $co_usuario = $request->input('co_usuario'); 
        
        $user = $this->getData($co_usuario);        
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'Usuario no encontrado');
        }

        $logs = $this->getLogs($co_usuario);
        return view('users.detail', compact(
            'user',            
            'logs'
        ));
    }

    public function update(UserEditRequest $request, $id)
    {
        try {
            $validated = $request->validated();
            // Validate the request
            /*
            $validated = $request->validate([
                'tx_url_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:3072',
                'tx_primer_nombre' => 'required|string|max:255',
                'tx_primer_apellido' => 'required|string|max:255',
                'tx_email' => 'required|email|max:255',
                'tx_telefono' => 'required|string|max:20',
                'tx_direccion1' => 'nullable|string|max:255',
                'tx_direccion2' => 'nullable|string|max:255',
                'tx_zip' => 'nullable|string',
                'co_rol' => 'required|exists:i007t_roles,co_rol',
                'co_tipo_usuario' => 'required|exists:c013t_tipo_usuarios,co_tipo_usuario',
                'co_estatus_usuario' => 'required|exists:i014t_estatus_usuarios,co_estatus_usuario',
                'Office_City_ID' => 'required|exists:i008t_oficinas,co_oficina',
                'co_office_manager' => 'required|exists:c002t_usuarios,co_usuario',
                'co_usuario_padre' => 'required|exists:c002t_usuarios,co_usuario',
                'co_usuario_reclutador' => 'required|exists:c002t_usuarios,co_usuario',
                'co_sponsor' => 'required|exists:c002t_usuarios,co_usuario',                
                'tx_password' => 'nullable|string',                
                'tx_url_paquete' => 'nullable|file|mimes:pdf|max:3072', 
                'tx_url_appempl' => 'nullable|file|mimes:pdf|max:3072', 
                'tx_url_drive' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,pdf|max:3072', 
                'tx_url_formaw9' => 'nullable|file|mimes:pdf|max:3072', 
                'co_idioma' => 'required|exists:i012t_idiomas,co_idioma',
                'fe_nac' => 'required|date'
            ], [
                'required' => 'El campo :attribute es obligatorio.',
                'email' => 'El campo :attribute debe ser una dirección de correo válida.',
                'max' => 'El campo :attribute no debe ser mayor a :max caracteres.',
                'exists' => 'El :attribute seleccionado no es válido.',
                'co_usuario_padre.required' => 'El usuario padre es obligatorio.',
                'co_usuario_reclutador.required' => 'El reclutador es obligatorio.',
                'co_sponsor.required' => 'El patrocinador es obligatorio.',
                'co_office_manager.required' => 'El gerente de oficina es obligatorio.',
                'Office_City_ID.required' => 'La ciudad de oficina es obligatoria.',    
                'tx_url_photo.image' => 'La foto de perfil debe ser tipo imagen.',
                'tx_url_photo.mimes' => 'La foto de perfil debe ser de tipo: jpeg, png, jpg, gif.',
                'tx_url_photo.max' => 'La foto de perfil no debe pesar más de 3MB.',
                'tx_url_paquete.file' => 'El paquete debe ser un archivo PDF.',
                'tx_url_paquete.mimes' => 'El paquete debe ser un archivo PDF.',
                'tx_url_paquete.max' => 'El paquete no debe pesar más de 3MB.',
                'tx_url_appempl.file' => 'El archivo de empleado debe ser un archivo PDF.',
                'tx_url_appempl.mimes' => 'El archivo de empleado debe ser un archivo PDF.',
                'tx_url_appempl.max' => 'El archivo de empleado no debe pesar más de 3MB.',
                'tx_url_drive.file' => 'La Licencia de conducir debe ser un archivo valido.',
                'tx_url_drive.mimes' => 'La Licencia de conducir debe ser un archivo jpeg, png, jpg, gif o PDF.',
                'tx_url_drive.max' => 'La Licencia de conducir no debe pesar más de 3MB.',
                'tx_url_formaw9.file' => 'El Formulario W-9 debe ser un archivo PDF.',
                'tx_url_formaw9.mimes' => 'El Formulario W-9 debe ser un archivo PDF.',
                'tx_url_formaw9.max' => 'El Formulario W-9 no debe pesar más de 3MB.',  
                'co_idioma.required' => 'El Idioma es requerido.',  
                'co_idioma.exists' => 'El Idioma seleccionado no es válido.',  
                'fe_nac.required' => 'La Fecha de Nacimiento es requerida.',  
                'fe_nac.date' => 'La Fecha de Nacimiento debe ser una fecha válida.',
            ]);
            

            $validated = $request->validated();
            */

            DB::beginTransaction();

            // Get the office name if Office_City_ID is provided
            if (isset($validated['Office_City_ID'])) {
                $officeName = DB::table('i008t_oficinas')
                    ->where('co_oficina', $validated['Office_City_ID'])
                    ->value('tx_nombre');
                
                $validated['Office_City'] = $officeName;
            }

            // Verificar si la columna co_usuario_log existe en la tabla
            try {
                $hasColumn = Schema::hasColumn('c002t_usuarios', 'co_usuario_log');                
                
                // Solo añadir co_usuario_log si la columna existe y hay un usuario autenticado
                if ($hasColumn && auth()->check()) {
                    $validated['co_usuario_log'] = auth()->id();                    
                }
            } catch (\Exception $e) {
                // Si hay error al verificar la columna, continuamos sin bloquear
                Log::warning('Error checking co_usuario_log column: ' . $e->getMessage());
            }

            // Handle password update
            if ($request->has('tx_password') && !empty($request->tx_password)) {                
                
                // Get current password
                $currentPassword = DB::table('c002t_usuarios')
                    ->where('co_usuario', $id)
                    ->value('tx_password');

                // Check if new password is different from current
                if (Hash::check($request->tx_password, $currentPassword)) {
                    throw new \Exception('La nueva contraseña no puede ser igual a la contraseña actual.');
                }

                $validated['tx_password'] = Hash::make($request->tx_password);
                $successMessage = 'Usuario y contraseña actualizados exitosamente';
            } else {
                unset($validated['tx_password']);
                $validated = array_diff_key($validated, ['tx_password'=>null]);     
                $successMessage = 'Usuario actualizado exitosamente';
            }           
            
            $fieldDeleted = [
                'tx_url_paquete '=> null,
                'tx_url_appempl' => null,
                'tx_url_drive' => null,
                'tx_url_formaw9' => null,
                'tx_url_paquete_old' => null,
                'tx_url_appempl_old' => null,
                'tx_url_drive_old' => null,
                'tx_url_formaw9_old' => null,
                'tx_url_photo' => null,
                'tx_url_photo_old' => null
            ];
            
            $validated = array_diff_key($validated, $fieldDeleted); // Elimina el elemento 'b'
            
            $fieldNames= [
                'tx_url_paquete'=> $request->input('tx_url_paquete_old') ?? '',
                'tx_url_appempl'=> $request->input('tx_url_appempl_old') ?? '',
                'tx_url_drive'=> $request->input('tx_url_drive_old') ?? '',
                'tx_url_formaw9'=> $request->input('tx_url_formaw9_old') ?? ''
            ];
            $temp = [];
            foreach($fieldNames as $field => $value){
                if($request->hasFile($field) && $request->file($field)->isValid()){
                    $temp[$field] = $value; 
                }
            }
            $fieldNames = $temp;
            $service = new DocumentUserService();
            $fieldNames = $this->uploadDocument($request,$service,$fieldNames,$id);          
            foreach($fieldNames as $field => $value){
                $validated[$field] = $value; 
            }
            
            $validated['fe_nac'] = Carbon::createFromFormat('m/d/Y', $validated['fe_nac']);
            // Update the user
            $updateResult = DB::table('c002t_usuarios')
                ->where('co_usuario', $id)
                ->update($validated);
            //Actualizamos la foto de perfil
            if($request->hasFile('tx_url_photo')){                
                //$documentService = new DocumentUserService();
                $photo_old = $request->input('tx_url_photo_old') ?? '';
                $path_perfil = $this->uploadImagePerfil($request, $service,$photo_old);  
                if($path_perfil){
                    DB::table('users')
                    ->where('id', $id)
                    ->update([
                        'image_path' => $path_perfil
                    ]);    
                }                
            }    

            // Manejar la actualización del rol de forma robusta
            try {
                // Verificar si el rol existe para este usuario
                $roleExists = DB::table('c014t_usuarios_roles')
                    ->where('co_usuario', $id)
                    ->exists();
                
                
                if ($roleExists) {
                    // Actualizar el rol existente
                    $updateRol = DB::table('c014t_usuarios_roles')
                        ->where('co_usuario', $id)
                        ->update(['co_rol' => $validated['co_rol']]);               
                } else {
                    // Crear un nuevo registro de rol
                    $updateRol = DB::table('c014t_usuarios_roles')->insert([
                        'co_usuario' => $id,
                        'co_rol' => $validated['co_rol']
                    ]);                    
                }
            } catch (\Exception $e) {
                // Si hay error al actualizar el rol, lo registramos pero continuamos
                Log::warning('Error updating role: ' . $e->getMessage());
                $updateRol = false;
            }
            

            DB::commit();

            Log::info('Transaction committed successfully for user ID: ' . $id);

            
            
            // Definir mensaje de éxito
            if (!isset($successMessage)) {
                $successMessage = 'El usuario ha sido actualizado correctamente';
            }
            
            return redirect()
                ->route('users.index')
                ->with('success', $successMessage);

        } catch (ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error for user ID ' . $id . ': ' . $e->getMessage());
            return back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating user ID ' . $id . ': ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar el usuario: ' . $e->getMessage());
        }
    }

    private function getData($co_usuario){
        $sql ="SELECT
            u.co_usuario as co_usuario,
            u.tx_primer_nombre,
            u.tx_primer_apellido,
            u.tx_primer_nombre || ' ' || u.tx_primer_apellido as full_name,
            u.tx_telefono,
            u.tx_email,
            u.co_ryve_usuario as ryve_user_id,
            r.tx_nombre as rol_user,
            u.fe_fecha_creacion as date_created,
            s.tx_estatus as user_status,
            u.tx_direccion1,
            u.tx_direccion2,
            u.tx_zip,
            u.\"Office_City\" as tx_oficina,
            u.\"Office_City_ID\" as codigo_oficina,
            u.co_idioma,
            i.tx_idioma as tx_idioma,
            u.fe_nac,
            u.tx_url_drive,
            u.tx_url_paquete,
            u.tx_url_appempl,
            u.tx_url_formaw9,
            t.tx_tipo_usuario as tipo_usuario,
            p.tx_primer_nombre ||' '|| p.tx_primer_apellido as father_name,
            u.co_sponsor as sponsor_id,
            s_user.tx_primer_nombre || ' ' || s_user.tx_primer_apellido as sponsor_name,
            u.co_usuario_reclutador as recruiter_id,
            r_user.tx_primer_nombre || ' ' || r_user.tx_primer_apellido as recruiter_name,
            u.co_office_manager as office_manager_id,
            om_user.tx_primer_nombre || ' ' || om_user.tx_primer_apellido as office_manager_name,
            usr.image_path as image_path
            
            FROM c002t_usuarios u
            JOIN i007t_roles r ON u.co_rol = r.co_rol
            JOIN i014t_estatus_usuarios s ON u.co_estatus_usuario = s.co_estatus_usuario
            JOIN c013t_tipo_usuarios t ON u.co_tipo_usuario = t.co_tipo_usuario
            JOIN users usr ON u.co_usuario = usr.id
            LEFT JOIN c002t_usuarios s_user ON u.co_sponsor = s_user.co_usuario
            LEFT JOIN c002t_usuarios r_user ON u.co_usuario_reclutador = r_user.co_usuario
            LEFT JOIN c002t_usuarios om_user ON u.co_office_manager = om_user.co_usuario
            LEFT JOIN  c002t_usuarios as p ON u.co_usuario_padre = p.co_usuario
            LEFT JOIN i012t_idiomas i ON u.co_idioma = i.co_idioma
            WHERE u.co_usuario = ?";

        $user = DB::select($sql, [$co_usuario]);
        
        if(count($user) > 0){
            return $user[0];
        }
        
        return null;
        
    } 

    private function getLogs($co_usuario){
        $sql = "SELECT log1.tx_accion, 
            COALESCE(usr2.tx_primer_nombre || ' ' || usr2.tx_primer_apellido, 'sin usuario definido') as usuario,
            log1.fe_registro 
            FROM log_sistema as log1 
            LEFT JOIN c002t_usuarios as usr2 ON (usr2.co_usuario = log1.co_usuario)
            WHERE log1.codigo = ? AND log1.tipo_log = ? 
            ORDER BY log1.fe_registro DESC";
        $logs = DB::select($sql, [$co_usuario, 2]);
        
        return $logs;
    }

    private function uploadImagePerfil(Request $request, DocumentUserService $document, $photo_Old = ''){
        try{
            $fieldName = 'tx_url_photo';            
            $ruta = $document->uploadImagePerfil($request, $fieldName, $photo_Old);
            return $ruta;
        }catch(\Exception $e){
            Log::INFO('Imagen de perfil de usuario no creada');
            return null;
        }        
    }
    private function uploadDocument(Request $request, DocumentUserService $document, $fieldNames, $user){        
        try{
            foreach ($fieldNames as $field => $value) {
                $ruta = $document->uploadDocument($request,$field, $value,$user);
                $fieldNames[$field] = $ruta;
            }    
            return $fieldNames;
            
            
        }catch(\Exception $e){
            echo $e->getMessage();
        }
        
    }
    public function documentTest(Request $request, DocumentUserService $document){
        try{
            $imagen = $request->file('tx_url_photo1');
            $ruta = $document->uploadImagePerfil($request,'tx_url_photo1', '2077', '');
            //tx_url_document1
            $rutaDocumento = $document->uploadDocument($request,'tx_url_document1', '2077', '');
            echo "ruta imagen". $ruta;
            echo "\nruta documento". $rutaDocumento;
        }catch(\Exception $e){
            echo $e->getMessage();
        }
        
    }
}
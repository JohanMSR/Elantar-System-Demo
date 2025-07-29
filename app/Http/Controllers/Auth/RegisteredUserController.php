<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\AutoEncoder;
use Intervention\Image\Encoders\WebpEncoder;


class RegisteredUserController extends Controller
{

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'name' => ['required', 'bail', 'string', 'max:255'],
            'email' => ['required', 'bail', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'surname' => ['required', 'bail', 'string', 'max:255']
        ];

        $path = null;
        $pathImg = null;
        $bandFile = 0;
        if ($request->hasFile('image_path')) { //si la img viene se agrega su validacion
            $rules['image_path'] = [
                'required',
                File::image()->min('1kb')->max('1024kb'),
                File::image()->types(['image/jpeg', 'image/png', 'image/webp', 'image/bmp', 'image/gif'])

            ];
            $bandFile = 1;
        }

        Validator::validate($request->all(), $rules);
        
        if ($bandFile == 1) {

            $directory = "img_profile";
            $pathRelativo = $request->file('image_path')->store('public/'.$directory);
            $pathImg = explode('/', $pathRelativo );
            $pathImg = $pathImg[1].'/'.$pathImg[2];
            $path = Storage::path($pathRelativo);
            
            // comprimimos la imagen
            $image = ImageManager::imagick()->read($path);
            $image->scale(width: 200);
            $image->encodeByMediaType('image/jpg', progressive: true, quality: 70);
            $respSave = $image->save($path);
            //

            if (!$respSave) {
                Session::flash('error', 'No se pudo almacenar la imagen');
                return redirect('/register/acces');
            }
        }

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'image_path' => $pathImg
        ]);

        event(new Registered($user));


        //Auth::login($user);

        //return redirect(RouteServiceProvider::HOME);
        Session::flash('success_register', 'Usuario registrado');
        return redirect('/register/acces');
    }

    public function edit(){
        return view('auth.edit');
    }
}

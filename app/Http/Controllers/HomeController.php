<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehiculos;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Crypt;
use DataTables;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        session(['type' => Auth::user()->type]);
        return view('home');
    }


    public function datatablesinicial(Request $request)
    {
        if ($request->ajax()) {
            
            $data = User::select(array('id','name','email'));
            return Datatables::of($data)

                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                            $btn = '<button onclick="editarus(\''.Crypt::encrypt($row->id).'\')" class="editus btn btn-primary" style="width:100% !important;padding-top:2px;padding-bottom:2px;margin-bottom:1px;">Editar</button><br><button onclick="deleteus(\''.Crypt::encrypt($row->id).'\')" class="deleteus btn btn-danger"  style="width:100% !important;padding-top:2px;padding-bottom:2px;margin-bottom:1px;">Eliminar</button>';
                            return $btn;
                    })
                    ->addColumn('action1', function($row){
                            $btn = '<button onclick="history(\''.Crypt::encrypt($row->id).'\')" class="history btn btn-success"  style="width:100% !important;padding-top:2px;padding-bottom:2px;margin-bottom:1px;">Historial</button><br><button onclick="addveh(\''.Crypt::encrypt($row->id).'\')" class="addveh btn btn-dark"  style="width:100% !important;padding-top:2px;padding-bottom:2px;margin-bottom:1px;">Agregar Vehículo</button>';
                            return $btn;
                    })
                    ->rawColumns(['action', 'action1'])
                    ->make(true);

        }
    }

    public function regus(Request $request)
    {
        $messages = [
            'name.required' => 'El nombre de usuario es obligatorio',
            'name.string' =>'El nombre de usuario debe ser una cadena de texto.',
            'name.min' => 'El mínimo requerido es de 2 caracteres.',
            'name.max' => 'El máximo requerido es hasta 50 caracteres.',
            'email.required' => 'El email es obligatorio',
            'email.string' => 'El email debe ser una cadena de texto',
            'email.email' => 'El email no tiene el formato correcto',
            'email.max' => 'El máximo requerido es hasta 255 caracteres.',
            'email.unique' => 'El email ingresado ya se encuentra registrado.',
            'password.required' => 'El password es obligatorio.',
            'password.string' => 'El password debe ser una cadena de texto',
            'password.min' => 'El password debe contener al menos 5 caracteres',
            'password.confirmed' => 'El password debe repetirse correctamente',
        ];
        $validator = $request->validate([
            'name' => ['required', 'string', 'max:50', 'min:2'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:5', 'confirmed'],
        ], $messages);
        $user = User::create(['name' => $request->name, 'email' => $request->email, 'password' => Hash::make('password'), 'type' => 'user']);
        return redirect()->back()->with(['message' => trans('messages.lang9').'  Usuario: '.$request->email.' . Contraseña : password']);
               
    }

    public function editus(Request $request)
    {
        $id = Crypt::decrypt($request->id);
        $data = User::select(array('id','name','email'))->where(["id" => $id])->count();
        switch (true) {
            case ($data == 0):
                return json_encode(array("status" => 0, "id" => "", "nombre" => "", "email" => ""));
            break;
            case ($data == 1):
                $data = User::select(array('id','name','email'))->where(["id" => $id])->first();
                return json_encode(array("status" => "ok", "id" => Crypt::encrypt($id), "nombre" => $data->name, "email" => $data->email));
            break;
            default:
            return json_encode(array("status" => 0, "id" => "", "nombre" => "", "email" => ""));
            break;
        }
    }
    public function editusid(Request $request)
    {
        $id = Crypt::decrypt($request->id);
        $messages = [
            'name.required' => 'El nombre de usuario es obligatorio',
            'name.string' =>'El nombre de usuario debe ser una cadena de texto.',
            'name.min' => 'El mínimo requerido es de 2 caracteres.',
            'name.max' => 'El máximo requerido es hasta 50 caracteres.',
            'email.required' => 'El email es obligatorio',
            'email.string' => 'El email debe ser una cadena de texto',
            'email.email' => 'El email no tiene el formato correcto',
            'email.max' => 'El máximo requerido es hasta 255 caracteres.',
            'email.unique' => 'El email ingresado ya se encuentra registrado.',
        ];
        $validator = $request->validate([
            'name' => ['required', 'string', 'max:50', 'min:2'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($id)],
        ], $messages);
        $user = User::where(['id' => $id])->update(['name' => $request->name, 'email' => $request->email]);
        return redirect()->back()->with(['message' => trans('messages.lang12').'  Nombre: '.$request->name.' . Email : '.$request->email]);
        dd($id);
    }
}

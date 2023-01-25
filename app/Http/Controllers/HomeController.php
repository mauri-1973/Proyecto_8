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
use PDF;
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
        
        return view('home');
    }


    public function datatablesinicial(Request $request)
    {
        if ($request->ajax()) {
            
            $data = User::select(array('id','name','email'));
            return Datatables::of($data)

                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                            if(Auth::user()->id != $row->id)
                            {
                                $btn = '<button onclick="editarus(\''.Crypt::encrypt($row->id).'\')" class="editus btn btn-primary" style="width:100% !important;padding-top:2px;padding-bottom:2px;margin-bottom:1px;">Editar</button><br><button onclick="deleteus(\''.Crypt::encrypt($row->id).'\')" class="deleteus btn btn-danger"  style="width:100% !important;padding-top:2px;padding-bottom:2px;margin-bottom:1px;">Eliminar</button>';
                                return $btn;
                            }
                            else
                            {
                                $btn = '<button onclick="editarus(\''.Crypt::encrypt($row->id).'\')" class="editus btn btn-primary" style="width:100% !important;padding-top:2px;padding-bottom:2px;margin-bottom:1px;">Editar</button>';
                                return $btn;
                            }
                    })
                    ->addColumn('action1', function($row){
                            $data = Vehiculos::select('*')->where(["users_id_veh" => $row->id])->count();
                            switch (true) {
                                case ($data > 0):
                                    $btn = '<button onclick="history(\''.Crypt::encrypt($row->id).'\')" class="history btn btn-success"  style="width:100% !important;padding-top:2px;padding-bottom:2px;margin-bottom:1px;">Historial</button><br><button onclick="addveh(\''.Crypt::encrypt($row->id).'\')" class="addveh btn btn-dark"  style="width:100% !important;padding-top:2px;padding-bottom:2px;margin-bottom:1px;">Agregar Vehículo</button>';
                            return $btn;
                                break;
                                default:
                                    $btn = '<button onclick="addveh(\''.Crypt::encrypt($row->id).'\')" class="addveh btn btn-dark"  style="width:100% !important;padding-top:2px;padding-bottom:2px;margin-bottom:1px;">Agregar Vehículo</button>';
                                return $btn;
                                break;
                            }
                            
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
    }
    public function eliusid(Request $request)
    {
        $id = Crypt::decrypt($request->id);
        $user = User::where(['id' => $id])->get();
        $user->restore();
        dd("ok");
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
        $user = User::where(['id' => $id])->delete();
        return redirect()->back()->with(['message' => trans('messages.lang12').'  Nombre: '.$request->name.' . Email : '.$request->email]);
    }
    public function addveh(Request $request)
    {
        $id = Crypt::decrypt($request->id);
        $messages = [
            'marca.required' => 'El nombre del modelo es obligatorio',
            'marca.min' => 'El mínimo requerido es de 2 caracteres.',
            'modelo.max' => 'El máximo requerido es hasta 50 caracteres.',
            'modelo.required' => 'El nombre del modelo es obligatorio',
            'modelo.min' => 'El mínimo requerido es de 2 caracteres.',
            'modelo.max' => 'El máximo requerido es hasta 50 caracteres.',
            'patente.required' => 'La patente es obligatoria',
            'patente.min' => 'El mínimo requerido es de 6 caracteres.',
            'patente.max' => 'El máximo requerido es hasta 6 caracteres.',
            'patente.unique' => 'Esta patente ya fue ingresada.',
            'annio.required' => 'El año es abligatorio.',
            'annio.numeric' => 'El año debe ser un valor numérico.',
            'annio.max' => 'El máximo valor para el año es 2023',
            'annio.min' => 'El mínimo valor para el año es 1950',
            'precio.required' => 'El precio es abligatorio.',
            'precio.numeric' => 'El precio debe ser un valor numérico.',
            'precio.max' => 'El máximo valor para el precio es 10',
            'precio.min' => 'El mínimo valor para el precio es 999999999999',
        ];
        $validator = $request->validate([
            'marca' => ['required', 'string', 'max:50', 'min:2'],
            'modelo' => ['required', 'string', 'max:50', 'min:2'],
            'patente' => ['required', 'string', 'max:6', 'min:6', 'unique:vehiculos'],
            'annio' => ['required', 'numeric', 'max:2023', 'min:1950'],
            'precio' => ['required', 'numeric', 'max:999999999999', 'min:10'],

        ], $messages);
        $user = Vehiculos::create(['marca' => $request->marca, 'modelo' => $request->modelo, 'patente' => $request->patente, 'annio' => $request->annio, 'precio' => $request->precio, 'users_id_veh' => $id]);
        return redirect()->back()->with(['message' => trans('messages.lang12').'  Nombre: '.$request->name.' . Email : '.$request->email]);
    }
    public function histusveh(Request $request)
    {
        if ($request->ajax()) {
            
            $id = Crypt::decrypt($request->id);
            
            $data = Vehiculos::select(array('id','marca','modelo', 'patente', 'annio'))->where(["users_id_veh" => $id]);
            return Datatables::of($data)

                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '<button onclick="editarusveh(\''.Crypt::encrypt($row->id).'\')" class="editus btn btn-primary" style="width:100% !important;padding-top:2px;padding-bottom:2px;margin-bottom:1px;">Editar</button><br><button onclick="deleteus(\''.Crypt::encrypt($row->id).'\')" class="deleteus btn btn-danger"  style="width:100% !important;padding-top:2px;padding-bottom:2px;margin-bottom:1px;">Eliminar</button>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);

        }
    }
    public function pdf(Request $request)
    {
        $id = Crypt::decrypt($request->id);
        $user = User::select('*')->where(["id" => $id])->first();
        $veh = Vehiculos::select('*')->where(["users_id_veh" => $id])->get();
        $pdfContent = PDF::loadView('pdf.historico',["veh" => $veh, "user" => $user]);
        $pdfContent->setPaper('a4', 'portrait');
        $pdfContent->download('archivo-pdf.pdf');
        return json_encode(["base64" => 'data:application/pdf;base64,'.base64_encode($pdfContent->stream())]);
    }
}

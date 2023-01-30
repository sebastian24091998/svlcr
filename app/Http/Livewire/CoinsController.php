<?php

namespace App\Http\Livewire;

use Livewire\Component;
//importar modelo
use App\Models\Denomination;
//para subir imagenes
use Livewire\WithFileUploads;
//para el paginado de los componentes
use Livewire\WithPagination;

use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Unique;

class CoinsController extends Component
{

    use WithFileUploads;
    use WithPagination;

    //cargar los componentes
    public $type, $value, $search, $image, $selected_id, $pageTitle, $componentName; 
    private $pagination = 2;

    //asignando valor
    //metodo mount para iniciar propiedades
    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Denominaciones';
        $this->type ='Elegir';
    }
    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }


    public function render()
    {   
        //obtener todos los registros
        if(strlen($this->search) > 0)
            $data = Denomination::where('type', 'like', '%' . $this->search . '%')->paginate($this->pagination);
        else
            $data = Denomination::orderBy('id','desc')->paginate($this->pagination);
            
        //vista que usaremos
        return view('livewire.denominations.component', ['data' => $data])
        //plantillas que suaremos
        ->extends('layouts.theme.app')
        //el contenido
        ->section('content');


    }

    //metodo del boton editar 
    public function Edit(Denomination $coins)
    {
        //busqueda y mejor integrada "Buena practica"

        $this->type = $coins->type;
        $this->value = $coins->value;
        $this->selected_id = $coins->id;
        $this->image = null;
        //evento
        $this->emit('modal-show','show modal!');
    }
    //store agregar categoria nueva
    public function Store()
    {
        //validar los datos
        $rules = [
            'type' => 'required|not_in:Elegir',
            'value'=> 'required|unique:denominations'
        ];
        //reglas de validacion
        $messages = [
            'type.required' => 'El tipo es requerido',
            'type.not_in' => 'Elege un valor distinto a Elegir',
            'value.required' => 'El valor es requerido',
            'value.unique' => 'Ya existe el valor'  
        ];
        //ejecutar las validaciones
        $this-> validate($rules, $messages);

        //insertar en la categoria nueva
        $denomination = Denomination::create([
            'type' => $this-> type,
            'value' => $this-> value
        ]);

        //validar las imagenes
        
        if($this->image)
        {
            //funcion de php para diferenciar 
            $customFileName = uniqid() . '_.' . $this->image->extension();
            //almacenar la img
            $this->image->storeAs('public/denominations', $customFileName);
            //subir el archivo a la bd
            $denomination->image = $customFileName;
            $denomination->save();
        }
        $this->resetUI();
        $this->emit('item-added','Denominacion Registrada');

    }
    //metodo actualizar datos
    public function Update()
    {
        $rules = [
            //validar datos
            'type' => 'required|not_in:Elegir',
            'value' => "required|unique:denominations,value,{$this->selected_id}"
            
        ];
        //mensajes personalizados
        $messages =[
            'type.required' => 'El tipo es requerido',
            'type.not_in' => 'Elege un tipo valido',
            'value.required' => 'El valor es requerido',
            'value.unique' => 'El valor ya existe'
        ];

        //ejecutar la validacion
        $this->validate($rules, $messages);
        //actualizar la caegoria
        $denomination = Denomination::find($this->selected_id);
        $denomination->update([
            'type' => $this->type,
            'value' => $this->value
        ]);

        //validacion de imagen
        if($this->image)
        {
            //nombre de imagen unico
            $customFileName = uniqid() . '_.' . $this->image->extension();
            //guardar imagen
            $this->image->storeAs('public/denominations', $customFileName);
            //eliminar la imagen anterior
            //recuperamos la imagen anterior
            $imageName = $denomination->image;
            //asignar la nueva img
            $denomination->image = $customFileName;
            //guarda de firma temporal
            $denomination->save();
            //verificacion de imagen
            if($imageName != null)
            {
                //eliminar la ancituga imagen
                if(file_exists('storage/denominations' . $imageName))
                {
                    unlink('storage/denominations' . $imageName);
                }
            }

            
        }
        
        $this->resetUI();
        $this->emit('item-updated', 'Denominacion Actualizado');
    }
    
    //metodo resetui para el cerra del edit y agregar
    public function resetUI()
    {
        $this->type ='';
        $this->value='';
        $this->image = null;
        $this->search ='';
        $this->selected_id=0;
        $this->emit('item-close', 'Categria cerrar');

    }
    //escuchar evento de eliminar para js
    protected $listeners = [
        'deleteRow' => 'Destroy'
    ];
    //metodo eliminar categoria con funcion nueva
    public function Destroy(Denomination $denomination)
    {
        //buscar la categoria a eliminar de forma antigua
        //$category = Category::find($id);
        //dd($category);
        $imageName = $denomination->image; //img temporal
        $denomination->delete();
        //identiicar si se tiene una img
        if($imageName != null)
        {
            //elimina la img
            unlink('storage/denominations/' . $imageName);
        }
        $this->resetUI();
        $this->emit('item-delete', 'Denominacion Eliminada');

    }   
}

<?php

namespace App\Http\Livewire;

use Livewire\Component;
//importar modelo
use App\Models\Category;
//para subir imagenes
use Livewire\WithFileUploads;
//para el paginado de los componentes
use Livewire\WithPagination;

use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Unique;

class CategoriesController extends Component
{

    use WithFileUploads;
    use WithPagination;

    //cargar los componentes
    public $name, $search, $image, $selected_id, $pageTitle, $componentName; 
    private $pagination = 3;

    //asignando valor
    //metodo mount para iniciar propiedades
    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Categories';
    }
    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }


    public function render()
    {   
        //obtener todos los registros
        if(strlen($this->search) > 0)
            $data = Category::where('name', 'like', '%' . $this->search . '%')->paginate($this->pagination);
        else
            $data = Category::orderBy('id','desc')->paginate($this->pagination);
            
        //vista que usaremos
        return view('livewire.category.categories', ['categories' => $data])
        //plantillas que suaremos
        ->extends('layouts.theme.app')
        //el contenido
        ->section('content');


    }

    //metodo del boton editar 
    public function Edit($id)
    {
        //busqueda y mejor integrada "Buena practica"
        $record = Category::find($id, ['id','name','image']);
        $this->name = $record->name;
        $this->selected_id = $record->id;
        $this->image = null;
        //evento
        $this->emit('show-modal','show modal!');
    }
    //store agregar categoria nueva
    public function Store()
    {
        //validar los datos
        $rules = [
            'name' => 'required|unique:categories|min:3'
        ];
        //reglas de validacion
        $messages = [
            'name.required' => 'nombre de la categoria es requerido',
            'name.unique' => 'Ya existe el nombre de la categoria',
            'name.min' => 'El nombre de la categoria debe tener al menos 3 caracteres' 
        ];
        //ejecutar las validaciones
        $this-> validate($rules, $messages);

        //insertar en la categoria nueva
        $category = Category::create([
            'name' => $this-> name
        ]);

        //validar las imagenes
        
        if($this->image)
        {
            //funcion de php para diferenciar 
            $customFileName = uniqid() . '_.' . $this->image->extension();
            //almacenar la img
            $this->image->storeAs('public/categories', $customFileName);
            //subir el archivo a la bd
            $category->image = $customFileName;
            $category->save();
        }
        $this->resetUI();
        $this->emit('category-added','Categoria Registrada');

    }
    //metodo actualizar datos
    public function Update()
    {
        $rules = [
            //validar datos
            'name' => "required|min:3|unique:categories,name,{$this->selected_id}"

        ];
        //mensajes personalizados
        $messages =[
            'name.required' => 'Nombre de categoria requerido',
            'name.min' => 'El nombre de la categoria debe tener al menos 3 caracteres',
            'name.unique' => 'El nombre de la categoria ya existe'
        ];

        //ejecutar la validacion
        $this->validate($rules, $messages);
        //actualizar la caegoria
        $category = Category::find($this->selected_id);
        $category->update([
            'name' => $this->name
        ]);

        //validacion de imagen
        if($this->image)
        {
            //nombre de imagen unico
            $customFileName = uniqid() . '_.' . $this->image->extension();
            //guardar imagen
            $this->image->storeAs('public/categories', $customFileName);
            //eliminar la imagen anterior
            //recuperamos la imagen anterior
            $imageName = $category->image;
            //asignar la nueva img
            $category->image = $customFileName;
            //guarda de firma temporal
            $category->save();
            if($imageName != null)
            {
                //eliminar la antigua imagen
                if(file_exists('storage/categories' . $imageName))
                {
                    unlink('storage/categories' . $imageName);
                }
            }

            
        }
        
        $this->resetUI();
        $this->emit('category-updated', 'Categoria Actualizada');
    }
    
    //metodo resetui para el cerra del edit y agregar
    public function resetUI()
    {
        $this->name ='';
        $this->image = null;
        $this->search ='';
        $this->selected_id=0;
        $this->emit('category-close', 'Categria cerrar');

    }
    //escuchar evento de eliminar para js
    protected $listeners = [
        'deleteRow' => 'Destroy'
    ];
    //metodo eliminar categoria con funcion nueva
    public function Destroy(Category $category)
    {
        //buscar la categoria a eliminar de forma antigua
        //$category = Category::find($id);
        //dd($category);
        $imageName = $category->image; //img temporal
        $category->delete();
        //identiicar si se tiene una img
        if($imageName != null)
        {
            //elimina la img
            unlink('storage/categories/' . $imageName);
        }
        $this->resetUI();
        $this->emit('category-delete', 'Categoria Eliminada');

    }   
}

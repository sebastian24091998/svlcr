<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ProductsController extends Component
{   
    //paginacion
    use WithPagination;
    // subir imagenes o archivos
    use WithFileUploads;
    public $name, $barcode, $cost, $price, $stock, $alerts, $categoryid, $search, $image, $selected_id, $pageTitle, $componentName;
    private $pagination = 2;

    //funcion de la paginacion
    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }
    //metodo para iniciar propiedades o componentes
    public function mount()
    {
        $this->pageTitle ='Listado';
        $this->componentName = 'Productos';
        $this->categoryid = 'Elegir';
    }
    public function render()
    {
        //buscador
        if(strlen($this->search)>0)
            $products= Product::join('categories as c','c.id','products.category_id')
                            ->select('products.*','c.name as category')
                            //busquedas
                            ->where('products.name', 'like', '%'. $this->search . '%')
                            ->orWhere('products.barcode', 'like', '%' . $this->search . '%')
                            ->orWhere('c.name', 'like', '%' . $this->search . '%')
                            ->orderBy('products.name', 'asc')
                            ->paginate($this->pagination);
        else
            $products= Product::join('categories as c','c.id','products.category_id')
            ->select('products.*','c.name as category')
            ->orderBy('products.name', 'asc')
            ->paginate($this->pagination);
        //retornar la vista y los datos
        return view('livewire.products.products', [
            'data' => $products,
            'categories' => Category::orderBy('name', 'asc')->get()
        ])
        //extender desde la plantilla
        ->extends('layouts.theme.app')
        //para la informacion del componente
        ->section('content');
    }

    //Medota agregar
    public function Store()
    {
        //validaciones
        $rules =[
            'name' => 'required|unique:products|min:3',
            'cost' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'alerts' => 'required',
            'categoryid' => 'required|not_in:Elegir',
           
        ];

        $messages=[
            'name.required' => 'Nombre del producto requerido',
            'name.unique' => 'Ya existe el nombre del producto',
            'name.min' => 'El nombre del producto debe tener al menos 3 caracteres',
            'cost.required' => 'El costo es requerido',
            'price.required' => 'El precio es requerido',
            'stock.required' => 'El stock es requerido',
            'alerts.required' => 'Ingresa el valor minimo en existencias',
            'categoryid.not_in' => 'Elige un nombre de categoria diferente de Elegir',
          
        ];

        //validar
        $this->validate($rules, $messages);
        
        //para guardar 
        
        $product = Product::create([
            'name' => $this->name,
            'cost' => $this->cost,
            'price' => $this->price,
            'barcode' => $this->barcode,
            'stock' => $this->stock,
            'alerts' => $this->alerts,
            'category_id' => $this->categoryid
        ]);

        
        //para la imagen personalizar
        if($this->image)
        {
            $customFileName = uniqid() . '_.' . $this->image->extension();
            $this->image->storeAs('public/products', $customFileName);
            $product->image = $customFileName;
            
            
            $product->save();
        }
        
        $this->resetUI();
        $this->emit('product-added', 'Producto Registrado');
    }
    //metodo de editar para mostrar el form con datos
    public function Edit(Product $product ){
        $this->selected_id = $product->id;
        $this->name = $product->name;
        $this->barcode = $product->barcode;
        $this->cost = $product->cost;
        $this->price = $product->price;
        $this->stock = $product->stock;
        $this->alerts = $product->alerts;
        $this->categoryid = $product->category_id;
        $this->image = null;

        $this->emit('modal-show', 'abrir modal');

    }

    //actualizar datos
    public function Update()
    {
        //validaciones
        $rules =[
            'name' => "required|min:3|unique:products,name,($this->selected_id)",
            'cost' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'alerts' => 'required',
            'categoryid' => 'required|not_in:Elegir',
           
        ];

        $messages=[
            'name.required' => 'Nombre del producto requerido',
            'name.unique' => 'Ya existe el nombre del producto',
            'name.min' => 'El nombre del producto debe tener al menos 3 caracteres',
            'cost.required' => 'El costo es requerido',
            'price.required' => 'El precio es requerido',
            'stock.required' => 'El stock es requerido',
            'alerts.required' => 'Ingresa el valor minimo en existencias',
            'categoryid.not_in' => 'Elige un nombre de categoria diferente de Elegir',
          
        ];

        //validar
        $this->validate($rules, $messages);
        
        //buscar el producto
        $product = Product::find($this->selected_id);
        
        //para guardar 
        $product -> update([
            'name' => $this->name,
            'cost' => $this->cost,
            'price' => $this->price,
            'barcode' => $this->barcode,
            'stock' => $this->stock,
            'alerts' => $this->alerts,
            'category_id' => $this->categoryid
        ]);

        
        //para la imagen personalizar
        if($this->image)
        {
            $customFileName = uniqid() . '_.' . $this->image->extension();
            $this->image->storeAs('public/products', $customFileName);
            //guardar la imagen anterior
            $imageTemp = $product->image; //imagen temporal
            $product->image = $customFileName;
            $product->save();

            if($imageTemp !=null){
                if(file_exists('storage/products/' . $imageTemp)){
                    //si existe eliminamos la img
                    unlink('storage/products/' . $imageTemp );
                }
            }
        }
        
        $this->resetUI();
        $this->emit('product-updated', 'Producto Actualizado');
    }

    
    //resetui
    public function resetUI()
    {
        $this->name ='';
        $this->barcode ='';
        $this->cost ='';
        $this->price ='';
        $this->stock ='';
        $this->alerts ='';
        $this->search ='';
        $this->categoryid ='Elegir';
        $this->image =null;
        $this->selected_id =0;
        $this->emit('product-close', 'Producto cerrar');
    }
    //evento para eliminar el producto

    protected $listeners =['deleteRow' => 'Destroy'];

    public function Destroy(Product $product)
    {
        
        //buscar la categoria a eliminar de forma antigua
        //$category = Category::find($id);
        $imageName = $product->image; //img temporal
        $product->delete();
        //identiicar si se tiene una img
        if($imageName != null)
        {
            //verificar si existe la img
            if(file_exists('storage/products/' . $imageName))
            {
                //elimina la img de los archivos
                unlink('storage/products/' . $imageName);
            }
            
            
        }
        $this->resetUI();
        $this->emit('products-deleted', 'Producto Eliminada');

    }
}

<?php

namespace App\Http\Livewire;
use App\Models\Denomination;
use App\Models\Tienda;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetails;
//
use App\Models\Category;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Livewire\Component;
use DB;

class TiendasController extends Component
{
    public $total, $itemsQuantity,  $efectivo, $change, $search, $qrtotal, $qritem, $titulo, $dinero;
    private $pagination = 2;

     //inicar las propiedades
     public function mount()
     {
        $this->titulo="Generar Codigo QR";
         //$this->efectivo = 0;
         $this->change = 0;
         //$this->total = "1000";
         //$this->itemsQuantity = $this->efectivo;
         
     }
     public function paginationView()
     {
         return 'vendor.livewire.bootstrap';
     }

    public function render()
    {
        //borrar de arriba
        
        //hasta aqui
        return view('livewire.tiendas.component',[
            //eliminar
            
            //hasta aqui
            'denominations' => Denomination::orderBy('value','desc')->get(),
            'cart' => Cart::getContent()->sortBy('name')
            ])  
        ->extends('layouts.theme.app')
        ->section('content');
    }

    protected $listeners =[
        'scan-code' => 'ScanCode',
        'removeItem' => 'removeItem',
        'clearCart' => 'clearCart',
        'saveSale' => 'saveSale',
        'datitos' => 'datitos',
        'algo' => 'algo'
    ];
    public function datitos($t, $iq)
    {
        //dd($this->total);
        /*$category = Tienda::create([
            'precio' => $this->total
        ]);*/
        $this->total=$t;
        //dd($t);
    }

     // metodo para agregar el efectivo tecleado
     public function Acash($value)
    {
        //almacer cada unos de los btn que tenemos en la vista y el btn exacto
        $this->efectivo += ($value == 0) ? $this->total : $value;
        //para el cambio 
        $this->change = ($this->efectivo - $this->total);
        //
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
}

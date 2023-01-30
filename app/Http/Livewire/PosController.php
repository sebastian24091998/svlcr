<?php

namespace App\Http\Livewire;

use App\Models\Denomination;
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
use Illuminate\Support\Facades\Auth;

class PosController extends Component
{
    public $total, $itemsQuantity,  $efectivo, $change, $search, $qrtotal, $qritem;
    private $pagination = 2;
    
    //inicar las propiedades
    public function mount()
    {
        $this->efectivo = 0;
        $this->change = 0;
        
        /*$this->total = 1000;
        $this->itemsQuantity = Cart::getTotalQuantity();*/
    }
    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {   //esto tenemos que quitar
        
        //borrar de arriba
        $this->total = Cart::getTotal();
        $this->itemsQuantity=Cart::getTotalQuantity();
        $this->qrtotal=Cart::getTotal();
        $this->qritem=Cart::getTotalQuantity();
        //hasta aqui
        return view('livewire.pos.component',[
            //eliminar
            
            //hasta aqui
            'denominations' => Denomination::orderBy('value','desc')->get(),
            'cart' => Cart::getContent()->sortBy('name')
            ])  
        ->extends('layouts.theme.app')
        ->section('content');
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

    //capturar los eventos 
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
        //dd($t,$iq);
        $this->qrtotal=Cart::getTotal();
        $this->qritem=Cart::getTotalQuantity();

    }
    public function algo()
    {
        
        $this->qrtotal=Cart::getTotal();
        
        $this->qritem=Cart::getTotalQuantity();
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
    
    //metodo recibir el codigo de barras
    public function ScanCode($barcode, $cant = 1){
        //obtener el producto en base al codigo de barras
        $product = Product::where('barcode', $barcode)->first();
        //verificar si el producto exite
        if($product == null || empty($product))
        {
            dd($product);
            $this->emit('scan-notfound','El producto no esta registrado');
        } else{
            //si existe el producto
            if($this->InCart($product->id))
            {
                $this->increaseQty($product->id);
                return;
            }
            if($product->stock <1)
            {
                $this->emit('No-stock', 'stock insuficiente :C');
                return;
            }
            //metodo del cart descargado
            
            Cart::add($product->id, $product->name, $product->price, $cant, $product->image);
            $this->total = Cart::getTotal();
            $this->itemsQuantity=Cart::getTotalQuantity();

            $this->emit('scan-ok','Producto agregado');
        }
    }

    //metodo en carro
    public function InCart($productId)
    {
        $exist = Cart::get($productId);
        //validar si existe algun item
        if($exist)
        {
            return true;
        }
        else{
            return false;
        }
    }

    public function increaseQty($productId, $cant = 1)
    {
        $title='';
        $product = Product::find($productId);
        $exist = Cart::get($productId);
        // validar si existe
        if($exist)
            $title = 'Cantidad Actualizada';
        else
            $title = 'Cantidad Insuficiente';
        
        if($exist){
            //validar si las existencias de los productos son menores a + de la cantidad 
            if($product->stock < ($cant + $exist->quantity))
            {
                $this->emit('no-stock', 'Stock insuficiente :C');
                return;
            }
        }

        //actualizar el carrito con funcion del cart descargado
        Cart::add($product->id, $product->name, $product->price, $cant, $product->image);
        //actualizar el total
        $this ->total = Cart::getTotal();
        //actualizar items quanyity
        $this->itemsQuantity = Cart::getTotalQuantity();
        //emitir el evento
        $this ->emit('scan-ok', $title);
    }   

    //metodo update quantity donde remplazara toda la info del carrito y la vuelve a poner
    public function updateQty($productId, $cant = 1)
    {
        $title='';
        $product = Product::find($productId);
        //valdiar si existe el producto en el carrito
        $exist = Cart::get($productId);
        if($exist)
        $title = 'Cantidad Actualizada';
        else
        $title = 'Cantidad Actualizada';

        //
        if($exist)
        {
            //si en su columna stock es menor a la cantidad
            if($product->stock < $cant)
            {
                $this->emit('no-stock', 'Stock insuficiente :C');
                return;
            }
        }

        //eliminar el carrito
        $this-> removeItem($productId);
        if($cant >0)
        {
            Cart::add($product->id, $product->name, $product->price, $cant, $product->image);
            //actualizar el total
            $this ->total = Cart::getTotal();
            //actualizar items quanyity
            $this->itemsQuantity = Cart::getTotalQuantity();
            //emitir el evento
            $this ->emit('scan-ok', $title);
        }
    }


    //metodo eliminar un producto de ventas con ayudade las funciones cart
    public function removeItem($productId)
    {
        
        //eliminar
        Cart::remove($productId);

        //actualizar el total
        $this ->total = Cart::getTotal();
        //actualizar items quanyity
        $this->itemsQuantity = Cart::getTotalQuantity();
        //emitir el evento
        $this ->emit('scan-ok', 'Producto eliminado');

    }
    //metodo decrementar producto
    public function decreaseQty($productId)
    {
        //recuperar el carrito
        $item = Cart::get($productId);
        //eliminarlo del carrito
        Cart::remove($productId);
        //decrementar la cantidad del producto
        $newQty = ($item->quantity) -1;
        //validacion
        if($newQty > 0)
        {
            Cart::add($item->id, $item->name, $item->price, $newQty, $item->attributes[0]);
        }
            
        
        //actualizar el total
        $this->total = Cart::getTotal();
        //actualizar items quanyity
        $this->itemsQuantity = Cart::getTotalQuantity();
        //emitir el evento
        $this ->emit('sacn-ok', 'Cantidad actualizada');
    }

    //metodo limpiar
    public function clearCart()
    {
        //
        Cart::clear();
        $this->efectivo = 0;
        $this->change = 0;

         //actualizar el total
         $this ->total = Cart::getTotal();
         //actualizar items quanyity
         $this->itemsQuantity = Cart::getTotalQuantity();
         //emitir el evento
         $this ->emit('sacn-ok', 'Carrito Vacio');
    }
    //metodo guardar producto
    public function saveSale()
    {
        //validar el total
        if($this->total <= 0)
        {
            $this->emit('sale-error', 'AGREGA PRODUCTOS A LA VENTA');
            return;
        }

        //validar el efectivo
        if($this->efectivo <= 0)
        {
            $this->emit('sale-error', 'INGRESA EL EFECTIVO');
            return;
        }

        //validar el total
        if($this->total > $this->efectivo)
        {
            $this->emit('sale-error', 'EL EFECTIVO DEBE SER MAYOR O IGUAL A TOTAL');
            return;
        }

        //para usar las transacciones en laravel
        DB::beginTransaction();

        try {
            //guardar primero la venta
            $sale = Sale::create([
                'total' => $this->total,
                'items' => $this->itemsQuantity,
                'cash' => $this->efectivo,
                'change' => $this->change,
                'user_id' => Auth()->user()->id
            ]);
            //validar si se guardo
            if($sale)
            {
                //guardar detalle de venta
                $items = Cart::getContent();
                foreach ($items as $item) {
                    SaleDetails::create([
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                        'product_id' => $item->id,
                        'sale_id' => $sale->id
                    ]);
                    //actualizar stock
                    $product = Product::find($item->id);
                    //actualizar el stock
                    $product->stock = $product->stock - $item->quantity;
                    //
                    $product->save();
                }
            }
            //confirma la transaccion
           DB::commit();

           //limpiar el carrito y reinicar las variables

           Cart::clear();
           $this->efectivo = 0;
           $this->change = 0;
            //actualizar el total
            $this ->total = Cart::getTotal();
            //actualizar items quanyity
            $this->itemsQuantity = Cart::getTotalQuantity();
            $this->emit('sale-ok','Venta registrada con exito');
            $this->emit('print-ticket', $sale->id);
        } catch (Exception $e) {
            //borrar las acciones incompletas
            DB::rollback();
            $this->emit('sale-error', $e->getMessage());

        }

    }

    public function printTicket($sale)
    {
        return Redirect::to("print://$sale->id");
    }
}

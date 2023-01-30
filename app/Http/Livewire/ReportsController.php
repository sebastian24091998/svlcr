<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Sale;
use App\Models\SaleDetails;
use Carbon\Carbon;

class ReportsController extends Component
{
    public $componentName, $data, $details, $sumDetails, $countDetails, 
    $reportType, $userId, $dateFrom, $dateTo, $saleId;
    //propiedades de las vistas
    public function mount(){
        $this->componentName = 'Reportes de Ventas';
        $this->data = [];
        $this->details = [];
        $this->sumDetails = 0;
        $this->countDetails = 0;
        $this->reportType = 0;
        $this->userId = 0;
        $this->saleId = 0;

    }
    public function render()
    {
        $this->SalesByDate();
        return view('livewire.reports.component',[
            'users' => User::orderBy('name','asc')->get()
        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }
    //metodo retornar reporte de la fecha
    public function SalesByDate()
    {
        //obtener las ventas del dia 
        if($this->reportType == 0)// ventas del dia
        {
            //fecha
            $from = Carbon::parse(Carbon::now())->format('Y-m-d') . ' 00:00:00';
            $to = Carbon::parse(Carbon::now())->format('Y-m-d') . ' 23:59:59';
        } else {
            //fechas especificadas por el usuario
            $from = Carbon::parse($this->dateFrom)->format('Y-m-d') . ' 00:00:00';
            $to = Carbon::parse($this->dateTo)->format('Y-m-d') . ' 23:59:59';
        }
        //validar si el usuario esta usando un tipo de reporte
        if($this->reportType == 1 && ($this->dateFrom == '' || $this->dateTo == '')) {
            return;
        }
        //validar si seleccionamos algun usuario
        if($this->userId == 0){
            //consulta
            $this->data = Sale::join('users as u','u.id','sales.user_id')
            ->select('sales.*','u.name as user')
            ->whereBetween('sales.created_at', [$from,$to])
            ->get();
        } else {
            $this->data = Sale::join('users as u','u.id','sales.user_id')
            ->select('sales.*','u.name as user')
            ->whereBetween('sales.created_at', [$from,$to])
            ->where('user_id', $this->userId)
            ->get();
        }
    }

    //metodo obtener los detalles de venta del saleDetails mas la cantidad y la suma total
    public function getDetails($saleId)
    {
        $this->details = SaleDetails::join('products as p' , 'p.id', 'sale_details.product_id')
        ->select('sale_details.id','sale_details.price','sale_details.quantity','p.name as product')
        //dtalle de ventas
        ->where('sale_details.sale_id', $saleId)
        ->get();
        //closhorts
        //obtener la suma usando la funcion closhorts
        
        $suma = $this->details->sum(function($item){
            return $item->price * $item->quantity;
        });
        $this->sumDetails = $suma;
        //sumar las cantidades
        $this->countDetails = $this->details->sum('quantity');
        $this->saleId = $saleId;

        //mostrar la ventada
        $this->emit('show-modal','detalles cargando');

    }
}

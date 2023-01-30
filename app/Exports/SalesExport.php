<?php

namespace App\Exports;

use App\Models\Sale;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;          // para trabajar con colecciones y obtener la data
use Maatwebsite\Excel\Concerns\WithHeadings;          //para definir los titulos de encabezado
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;       //para interactuar con el libro
use Maatwebsite\Excel\Concerns\WithCustomStartCell;     //para definir la celda donde inicia el reporte
use Maatwebsite\Excel\Concerns\WithTitle;               //para colocar el nombre a las hojas del libro
use Maatwebsite\Excel\Concerns\WithStyles;              //para dar formato a las celdas
class SalesExport implements FromCollection, WithHeadings, WithCustomStartCell, WithTitle, WithStyles
{
    
    protected $userId, $dateFrom, $dateTo, $reportType;
    
    function __construct($userId, $reportType, $f1, $f2)
    {
        $this->userId = $userId;
        $this->reportType = $reportType;
        $this->dateFrom = $f1;
        $this->dateTo = $f2;
    }
    
    
    public function collection()
    {
        $data = [];
        //validar el tipo de reporte
        if($this->reportType == 1)
        {
            $from = Carbon::parse($this->dateFrom)->format('Y-m-d') . ' 00:00:00';
            $to = Carbon::parse($this->dateTo)->format('Y-m-d') . ' 23:59:59';
        } else {
             //fecha de ahora 
             $from = Carbon::parse(Carbon::now())->format('Y-m-d') . ' 00:00:00';
             $to = Carbon::parse(Carbon::now())->format('Y-m-d') . ' 23:59:59';
        }

        //validar si seleccionamos algun usuario
        if($this-> userId == 0){
            //consulta
            $data = Sale::join('users as u','u.id','sales.user_id')
            ->select('sales.id','sales.items','sales.status','u.name as user','sales.created_at')
            ->whereBetween('sales.created_at', [$from,$to])
            ->get();
        } else {
            $data = Sale::join('users as u','u.id','sales.user_id')
            ->select('sales.id','sales.items','sales.status','u.name as user','sales.created_at')
            ->whereBetween('sales.created_at', [$from,$to])
            ->where('user_id', $this->userId)
            ->get();
        }
        //retornar datos para el exel
        return $data;
    }
    //CABECERAS DEL REPORTE
    public function headings(): array
    {
        return ["FOLIO". "IMPORTE", "ITEMS", "ESTATUS", "USUARIO", "FECHA"];
    }

    //Definiendo en que cel se imprimira el reporte
    public function startCell(): string
    {
        return 'A2';
    }

    //Estilos para el excel
    public function styles(Worksheet $sheet)
    {
        return [
            2 => ['font' => ['bold' => true]],
            
        ];
    }
    //Titulo del Excel
    public function title(): string
    {
        return 'Reporte de Ventas';
    }
}

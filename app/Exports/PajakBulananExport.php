<?php
namespace App\Exports;
use App\Models\PajakBulanan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;

class PajakBulananExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    public function view(): View
    {

        return view('pajakbulanan.excel', $this->data);
    }

}
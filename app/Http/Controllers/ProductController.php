<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use Kamaln7\Toastr\Facades\Toastr;
use App\Exports\ExportProduct;
use App\Exports\exportProductCollection;
use App\Exports\exportProductCollectionQuery;
use Carbon\Carbon;
use App\Exports\ExportProductView;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        // $kpi_outputs = KpiOutput::where('role_id','=',$roleSelected->id)->orderBy('updated_at', 'desc')->paginate(10);
        $products = Product::orderBy('updated_at', 'desc')->paginate(10);

        // return view('kpi.index',compact('roleSelected','usersHaveRoleArray','kpi_outputs'));
        return view('product.index',compact('products'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'productId' => 'required',
            'productName' => 'required',
        ]);


        if($request->remark == null){
            $request->remark = '';
        }
        // save
        $productToBeSave = new Product();
        // $productToBeSave->input_date        = Carbon::createFromFormat('d-m-Y', $request->date_input)->format('Y-m-d');
        $productToBeSave->user_key_in_id    = Auth::user()->id;
        $productToBeSave->productId         = $request->productId;
        $productToBeSave->productName       = $request->productName;
        $productToBeSave->remark            = $request->remark;

        $productToBeSave->save();

        $products = Product::orderBy('updated_at', 'desc')->paginate(10);

        $message = "Successfully add data";
        Toastr::success($message, $title = "Successfully Action", $options = []);
        return view('product.index',compact('products'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\KpiOutput  $kpiOutput
     * @return \Illuminate\Http\Response
     */
    public function show(KpiOutput $kpiOutput)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KpiOutput  $kpiOutput
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // dd($id);
        $productSelected = Product::findOrFail($id);
        // dd($userSelected);
        // $users = User::all();
        return view('product.edit',compact('productSelected'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KpiOutput  $kpiOutput
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $productSelected = Product::findOrFail($id);

        $validatedData = $request->validate([
            'productId' => 'required|max:255',
            'productName' => 'required|max:255',
        ]);

        // update
        $productSelected->productId     = $request['productId'];
        $productSelected->productName   = $request['productName'];
        $productSelected->remark        = $request['remark'];
        $productSelected->save();

        $products = Product::orderBy('updated_at', 'desc')->paginate(10);

        $message = "Successfully add data";
        Toastr::success($message, $title = "Successfully Action", $options = []);
        return view('product.index',compact('products'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KpiOutput  $kpiOutput
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {

    }


    public function exportProduct(Request $request)
    {
        return Excel::download(new ExportProduct($request), 'product.xlsx');
    }

    public function exportProductView(Request $request)
    {

        return Excel::download(new ExportProductView($request), 'productview.xlsx');
    }


    public function exportProductCollection(Request $request)
    {

        $data = [
            [
                'name' => 'Povilas',
                'surname' => 'Korop',
                'email' => 'povilas@laraveldaily.com',
                'twitter' => '@povilaskorop'
            ],
            [
                'name' => 'Taylor',
                'surname' => 'Otwell',
                'email' => 'taylor@laravel.com',
                'twitter' => '@taylorotwell'
            ]
        ];
        return Excel::download(new ExportProductCollection($data), 'productcollection.xlsx');
    }


    public function exportProductCollectionQuery(Request $request)
    {
        $startDate = Carbon::createFromFormat('d-m-Y', $request->startDate)->format('Y-m-d');
        $endDate = Carbon::createFromFormat('d-m-Y', $request->endDate)->format('Y-m-d');

        // dd($startDate);

        $productArray =  Product::where('updated_at','>=',$startDate)->where('updated_at','<=',$endDate)
        ->orderby('id', 'asc');

        // $collectionArray = $productArray;
        // foreach ($collectionArray as $key => $collect) {
            // $collect->num = $key;
            // dd($collect->num);
        // }
        // dd(sizeof($productArray));


        // query data

        foreach ($productArray as $key => $product) {

        }

        $age_obj_1 = array(['Name'=>'Peter','Age'=>'10'],
                    ['Name'=>'Ben','Age'=>'20']);
        $age_obj_2 = array(['Name'=>'Kem','Age'=>'30']);

        $age_obj_3 = $age_obj_1+$age_obj_2;


        dd($age_obj_3);

        foreach ($productArray as $key => $productInLoop) {
            # code...
        }

        $data = [

            [
                'name' => 'Povilas',
                'surname' => 'Korop',
                'email' => 'povilas@laraveldaily.com',
                'twitter' => '@povilaskorop'
            ],
            [
                'name' => 'Taylor',
                'surname' => 'Otwell',
                'email' => 'taylor@laravel.com',
                'twitter' => '@taylorotwell'
            ]
        ];
        return Excel::download(new ExportProductCollectionQuery($productArray), 'productcollection.xlsx');
    }
}

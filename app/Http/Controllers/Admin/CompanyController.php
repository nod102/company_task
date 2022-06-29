<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Validator;
use DataTables;

class CompanyController extends Controller
{
    use FileUploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = "الشركات";
        $view_title = "عرض الشركات";
        $dpage_id = 1;

        if ($request->ajax()) {
            $data = Company::get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $edit = '<a href="' . route('company.edit', $row->id) . '" type="button" class="btn btn-sm btn-info">تعديل</a> ';
                        $delete = '
                    <form action="'. route('company.destroy',$row->id) .'" method="POST">
                    '.csrf_field().'
                    '.method_field("DELETE").'
                    <button type="submit" class="btn btn-sm btn-danger"
                        onclick="return confirm(\'Are You Sure Want to Delete?\')">حذف</a>
                    </form>';

                        return $edit . ' ' . $delete;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
      
        return view('admin.company.index', compact('title', 'view_title', 'dpage_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "الشركات";
        $view_title = "اضف الشركات";
        $dpage_id = 1;
        return view('admin.company.add', compact('title', 'view_title', 'dpage_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            //validation
            $validateData = Validator::make($request->all(), [
                'page_name'=>'required',
                'page_address'=>'required',
                'page_picture' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);
    
            if($validateData->fails()){
                return redirect()->back()->with('failed_error', $validateData->errors()->first());
            }

              $img_name = "";
              if ($request->hasFile('page_picture')) {
                  $img_name = $this->uploadFile($request->file('page_picture'), 'company');
              }
      
              $new_data = new Company;
              $new_data->name = $request->page_name;
              $new_data->address = $request->page_address;
              $new_data->image = $img_name;
              $new_data->save();
            return redirect(route('company.index'))->with('success_error', 'Saved successfully');
          }catch(\Exception $e){
            return redirect()->back()->with('failed_error', 'Sorry, an error occurred, please try again');
          }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = "الشركات";
        $view_title = "تعديل الشركات";
        $dpage_id = 1;
        $result_page = Company::where('id', $id)->first();
        return view('admin.company.edit', compact('result_page', 'title', 'view_title', 'dpage_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            //validation
            $validateData = Validator::make($request->all(), [
                'page_name'=>'required',
                'page_address'=>'required',
                'page_picture' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);
    
            if($validateData->fails()){
                return redirect()->back()->with('failed_error', $validateData->errors()->first());
            }
            $new_data = Company::find($id);

            $img_name = $new_data->image;
            if ($request->hasFile('page_picture')) {
                $img_name = $this->uploadFile($request->file('page_picture'), 'company');
                $folder_path = public_path($new_data->image);
                @unlink($folder_path);
            }

            $new_data->name = $request->page_name;
            $new_data->address = $request->page_address;
            $new_data->image = $img_name;
            $new_data->save();
            return redirect(route('company.index'))->with('success_error', 'Saved successfully');
          }catch(\Exception $e){
            return redirect()->back()->with('failed_error', 'Sorry, an error occurred, please try again');
          }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $new_data = Company::find($id);
            if ($new_data->image != "") {
                $folder_path = public_path('/uploads/company');
                @unlink($folder_path . '/' . $new_data->image);
            }
            $new_data->delete();
            return redirect(route('company.index'))->with('success_error', 'Saved successfully');
          }catch(\Exception $e){
            return redirect()->back()->with('failed_error', 'Sorry, an error occurred, please try again');
        }
    }
}

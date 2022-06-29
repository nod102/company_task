<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Illuminate\Support\Facades\Event;
use App\Events\EmployeeNotification;

class EmployeeController extends Controller
{
    use FileUploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = "الموظفين";
        $view_title = "عرض الموظفين";
        $dpage_id = 2;
        $result_companies = Company::get();

        if ($request->ajax()) {

            if ($request->get('company_id') != 0) {
                $company_id = $request->get('company_id');
                $data = Employee::where('company_id',$company_id)->get();
            } else { 
                $data = Employee::get();
            }

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $edit = '<a href="' . route('employee.edit', $row->id) . '" type="button" class="btn btn-sm btn-info">تعديل</a> ';
                        $delete = '
                    <form action="'. route('employee.destroy',$row->id) .'" method="POST">
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
      
        return view('admin.employee.index', compact('title', 'view_title', 'dpage_id', 'result_companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "الموظفين";
        $view_title = "اضف الموظفين";
        $dpage_id = 2;
        $result_companies = Company::get();
        return view('admin.employee.add', compact('title', 'view_title', 'dpage_id', 'result_companies'));
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
                'email' => 'required|unique:employees',
                'page_password'=>'required',
                'page_picture' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);
    
            if($validateData->fails()){
                return redirect()->back()->with('failed_error', $validateData->errors()->first());
            }

              $img_name = "";
              if ($request->hasFile('page_picture')) {
                  $img_name = $this->uploadFile($request->file('page_picture'), 'employee');
              }
      
              $new_data = new Employee;
              $new_data->company_id = $request->page_company_id;
              $new_data->name = $request->page_name;
              $new_data->email = $request->email;
              $new_data->password = Hash::make($request->page_password);
              $new_data->image = $img_name;
              $new_data->save();

              //send notification
              Event::dispatch(new EmployeeNotification($new_data->id));
            return redirect(route('employee.index'))->with('success_error', 'Saved successfully');
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
        $title = "الموظفين";
        $view_title = "تعديل الموظفين";
        $dpage_id = 2;
        $result_page = Employee::where('id', $id)->first();
        $result_companies = Company::get();
        return view('admin.employee.edit', compact('result_page', 'title', 'view_title', 'dpage_id', 'result_companies'));
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
            $new_data = Employee::find($id);
            //validation
            $validateData = Validator::make($request->all(), [
                'page_name'=>'required',
                'email'=>'required|unique:employees,email,'.$new_data->id.',id',
                'page_password'=>'required',
                'page_picture' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);
    
            if($validateData->fails()){
                return redirect()->back()->with('failed_error', $validateData->errors()->first());
            }

            $img_name = $new_data->image;
            if ($request->hasFile('page_picture')) {
                $img_name = $this->uploadFile($request->file('page_picture'), 'employee');
                $folder_path = public_path($new_data->image);
                @unlink($folder_path);
            }

            $new_data->company_id = $request->page_company_id;
            $new_data->name = $request->page_name;
            $new_data->email = $request->email;
            $new_data->password = Hash::make($request->page_password);
            $new_data->image = $img_name;
            $new_data->save();
            return redirect(route('employee.index'))->with('success_error', 'Saved successfully');
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
            $new_data = Employee::find($id);
            if ($new_data->image != "") {
                $folder_path = public_path('/uploads/employee');
                @unlink($folder_path . '/' . $new_data->image);
            }
            $new_data->delete();
            return redirect(route('employee.index'))->with('success_error', 'Saved successfully');
          }catch(\Exception $e){
            return redirect()->back()->with('failed_error', 'Sorry, an error occurred, please try again');
        }
    }
}

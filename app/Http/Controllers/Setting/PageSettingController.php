<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Setting\PageSetting;
use DB;

class PageSettingController extends Controller
{
    public function getPage()
    {
        return view('admin.setting.pages.pageSetting');
    }

    public function getPageData()
    {
    	return PageSetting::paginate(5);
    }

    public function PageStore(Request $request)
    {
    	$request->validate([
            	'title'  => 'Required',
            	'description' => 'Required'
        	]);
    	try {

    		DB::beginTransaction();
    		$result = PageSetting::create($request->all());
            DB::commit();
            // cache clear 
            cache()->forget('static-page');
            $message = ['status' => 'success', 'message' => 'Page Created successfully'];
            
            
     
    		
    	} catch (Exception $e) {
    		DB::rollback();
    		$message = ['status' => 'error', 'message' => $e->errorInfo[2]];
    	}
    	
    	return $message;
    }

    public function update($id, Request $request)
    {
        $request->validate([
                'title'  => 'Required',
                'description' => 'Required'
            ]);
        try {
            DB::beginTransaction();
            $result = PageSetting::find($id);
            $result->title = $request->title;
            $result->description = $request->description;
            $result->update();
            DB::commit();
            cache()->forget('static-page');
            $message = ['status' => 'success', 'message' => 'Page Updated Successfully'];
        } catch (Exception $e) {
            DB::rollback();
            $message = ['status' => 'error', 'message' => $e->errorInfo[2]];
        }
        
        return $message;
    }

    public function changePublish($id)
    {
       $result = PageSetting::find($id);
       $setvalue = $result->publish == 1 ? 0 : 1;
       $result->publish = $setvalue;
       $result->update();
       cache()->forget('static-page');
       $message = ['status' => 'success', 'message' => 'Page Status Changed!'];
       return $message;
    }

    public function destroy($id)
    {
       $result = PageSetting::find($id);
       $result->delete();
       cache()->forget('static-page');
       $message = ['status' => 'success', 'message' => 'Page Deleted Successfully'];
       return $message;
    }
}

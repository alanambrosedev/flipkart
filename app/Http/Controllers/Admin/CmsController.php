<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use Illuminate\Http\Request;
use Session;

class CmsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Session::put('page', 'cms-pages');
        $cmsPages = CmsPage::get()->toArray();

        return view('admin.pages.cms_pages')->with(compact('cmsPages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CmsPage $cmsPage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id = null)
    {
        Session::put('page', 'cms-pages');
        if ($id == '') {
            $title = 'Add CMS Page';
            $cmsPage = new CmsPage;
            $message = 'CMS Page added successfully';
        } else {
            $title = 'Edit CMS Page';
            $cmsPage = CmsPage::find($id);
            $message = 'CMS Page updated successfully';
        }
        if ($request->isMethod('post')) {
            $data = $request->all();
            //CMS page validations
            $rules = [
                'title' => 'required',
                'url' => 'required',
                'description' => 'required',
            ];
            $customMessages = [
                'title.required' => 'Page title is required',
                'url.required' => '{Page url is required',
                'description.required' => 'Page description is required',
            ];
            $this->validate($request, $rules, $customMessages);

            $cmsPage->title = $data['title'];
            $cmsPage->url = $data['url'];
            $cmsPage->description = $data['description'];
            $cmsPage->meta_title = $data['meta_title'];
            $cmsPage->meta_description = $data['meta_description'];
            $cmsPage->meta_keywords = $data['meta_keywords'];
            $cmsPage->status = 1;
            $cmsPage->save();

            return redirect('admin/cms-pages')->with('success_message', $message);

        }

        return view('admin.pages.add_edit_cms_page')->with(compact('title', 'cmsPage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            if ($data['status'] == 'Active') {
                $status = 0;
            } else {
                $status = 1;
            }
            CmsPage::where('id', $data['page_id'])->update(['status' => $status]);

            return response()->json(['status' => $status, 'page_id' => $data['page_id']]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //Delete CMS Page
        CmsPage::where('id', $id)->delete();

        return redirect()->back()->with('success_message', 'CMS Page Deleted Successfully');
    }
}

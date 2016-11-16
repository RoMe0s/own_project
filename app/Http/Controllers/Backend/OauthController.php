<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Backend\BackendController;
use App\Http\Requests\Backend\Oauth\OauthCreateRequest;
use App\Http\Requests\Backend\Oauth\OauthUpdateRequest;
use App\Traits\Controllers\AjaxFieldsChangerTrait;
use Illuminate\Routing\ResponseFactory;
use Meta;
use App\Models\Oauth;
use Datatables;
use DB;
use FlashMessages;
use Redirect;
use Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OauthController extends BackendController
{

    use AjaxFieldsChangerTrait;

    /**
     * @var string
     */
    public $module = "oauth";

    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'oauth.read',
        'create'          => 'oauth.create',
        'store'           => 'oauth.create',
        'show'            => 'oauth.read',
        'edit'            => 'oauth.read',
        'update'          => 'oauth.write',
        'destroy'         => 'oauth.delete',
        'ajaxFieldChange' => 'oauth.write',
    ];

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \App\Services\PageService                     $pageService
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.oauth'));

        $this->breadcrumbs(trans('labels.oauth'), route('admin.oauth.index'));

        $this->middleware('slug.set', ['only' => ['store', 'update']]);
    }
    

    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Oauth::select(
                'id',
                'name',
                'client_id',
                'redirect_uri',
                'status'
            );

            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'id', '=', '$1')
                ->filterColumn('client_id', 'where', 'client_id', 'LIKE', '%$1%')
                ->editColumn(
                    'name',
                    function ($model) {
                        return config('oauth.names.' . $model->name);
                    }
                )
                ->editColumn(
                    'status',
                    function ($model) {
                        return view(
                            'partials.datatables.toggler',
                            ['model' => $model, 'type' => $this->module, 'field' => 'status']
                        )->render();
                    }
                )
                ->addColumn(
                    'actions',
                    function ($model) {
                        return view(
                            'partials.datatables.control_buttons',
                            ['model' => $model, 'front_link' => false, 'type' => $this->module]
                        )->render();
                    }
                )
                ->setIndexColumn('id')
                ->removeColumn('client_secret')
                ->removeColumn('display')
                ->removeColumn('response_type')
                ->make();
        }

        $this->data('page_title', trans('labels.oatuh'));
        $this->breadcrumbs(trans('labels.oauth_list'));

        return $this->render('views.oauth.index');
    }

    /**
     * Show the form for creating a new resource.
     * GET /page/create
     *
     * @return Response
     */
    public function create()
    {
        $this->data('model', new Oauth);

        $this->data('page_title', trans('labels.oauth_create'));

        $this->breadcrumbs(trans('labels.ouath_create'));

        return $this->render('views.oauth.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /page
     *
     * @param OauthCreateRequest $request
     *
     * @return \Response
     */
    public function store(OauthCreateRequest $request)
    {
        $input = $request->all();

        DB::beginTransaction();

        try {
            $model = new Oauth($input);

            $model->save();

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.oauth.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add('error', trans('messages.save_failed'));

            return Redirect::back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     * GET /page/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        return $this->edit($id);
    }

    /**
     * Show the form for editing the specified resource.
     * GET /page/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = Oauth::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.oauth.index');
        }

        $this->data('page_title', '"'.$model->name.'"');

        $this->breadcrumbs(trans('labels.oauth_editing'));

        return $this->render('views.oauth.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     * PUT /page/{id}
     *
     * @param  int              $id
     * @param OauthUpdateRequest $request
     *
     * @return \Response
     */
    public function update($id, OauthUpdateRequest $request)
    {
        try {
            $model = Oauth::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.oauth.index');
        }

        $input = $request->all();

        DB::beginTransaction();

        try {
            $model->fill($input);

            $model->update();

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.oauth.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add("error", trans('messages.update_error').': '.$e->getMessage());

            return Redirect::back()->withInput($input);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /page/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = Oauth::findOrFail($id);

            if (!$model->delete()) {
                FlashMessages::add("error", trans("messages.destroy_error"));
            } else {
                FlashMessages::add('success', trans("messages.destroy_ok"));
            }
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
        }

        return Redirect::route('admin.oauth.index');
    }

}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Category\CategoryCreateRequest;
use App\Http\Requests\Backend\Category\CategoryUpdateRequest;
use App\Models\Category;
use App\Traits\Controllers\AjaxFieldsChangerTrait;
use Datatables;
use DB;
use Event;
use Exception;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Meta;
use Redirect;
use Response;

class CategoryController extends BackendController
{

    use AjaxFieldsChangerTrait;

    /**
     * @var string
     */
    public $module = "categories";

    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'category.read',
        'create'          => 'category.create',
        'store'           => 'category.create',
        'show'            => 'category.read',
        'edit'            => 'category.read',
        'update'          => 'category.write',
        'destroy'         => 'category.delete',
        'ajaxFieldChange' => 'category.write',
    ];


    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.categories'));

        $this->breadcrumbs(trans('labels.categories'), route('admin.categories.index'));

        $this->middleware('slug.set', ['only' => ['store', 'update', 'create', 'delete']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Category::joinTranslations('categories', 'category_translations')->select(
                'categories.id',
                'category_translations.name',
                'categories.parent_id',
                'categories.status',
                'categories.position'
            );
            return $dataTables = Datatables::of($list)
                ->filterColumn('categories.id', 'where', 'categories.id', '=', '$1')
                ->filterColumn('category_translations.name', 'where', 'category_translations.name', 'LIKE', '%$1%')
                ->filterColumn('category_translations.parent_id', 'where', 'category_translations.parent_id', 'LIKE', '%$1%')
                ->editColumn(
                    'parent_id',
                    function ($model) {
                        return (isset($model->parents)) ? $model->parents->first()->name : trans('labels.no');
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
                ->editColumn(
                    'position',
                    function ($model) {
                        return view(
                            'partials.datatables.text_input',
                            ['model' => $model, 'type' => $this->module, 'field' => 'position']
                        )->render();
                    }
                )
                ->addColumn(
                    'actions',
                    function ($model) {
                        return view(
                            'partials.datatables.control_buttons',
                            ['model' => $model, 'front_link' => true, 'type' => $this->module]
                        )->render();
                    }
                )
                ->setIndexColumn('id')
                ->remove_column('meta_title')
                ->remove_column('meta_keywords')
                ->remove_column('meta_description')
                ->remove_column('short_content')
                ->remove_column('content')
                ->make();
        }

        $this->data('page_title', trans('labels.categories'));
        $this->breadcrumbs(trans('labels.categories_list'));

        return $this->render('views.category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->data('model', new Category);

        $this->data('page_title', trans('labels.category_create'));

        $this->breadcrumbs(trans('labels.category_create'));

        $this->_fillAdditionTemplateData();

        return $this->render('views.category.create');
    }

    /**
     * @param CategoryCreateRequest $request
     */
    public function store(CategoryCreateRequest $request)
    {
        $input = $request->all();
        DB::beginTransaction();

        try {
            $model = new Category($input);
            $model->save();

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.categories.index');
         } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add('error', trans('messages.save_failed'));

            return Redirect::back()->withInput();
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
        return $this->edit($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $model = Category::with('translations', 'parents')->whereId($id)->firstOrFail();
            $this->data('page_title', '"'.$model->name.'"');

            $this->breadcrumbs(trans('labels.category_editing'));

            $this->_fillAdditionTemplateData($model);

            return $this->render('views.category.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.categories.index');
        }
    }

    /**
     * @param CategoryUpdateRequest $request
     * @param $id
     */
    public function update(CategoryUpdateRequest $request, $id)
    {
        try {
            $model = Category::findOrFail($id);

            $input = $request->all();

            DB::beginTransaction();
            if(empty($input['category_id'])) {
                $input['parent_id'] = NULL;
            } else {
                $input['parent_id'] = $input['category_id'];
            }
            unset($input['category_id']);
            $model->fill($input);
            $model->update();

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.categories.index');
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add("error", trans('messages.update_error').': '.$e->getMessage());
        }

        return Redirect::back()->withInput($input);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $model = Category::findOrFail($id);

            if (!$model->delete()) {
                FlashMessages::add("error", trans("messages.destroy_error"));
            } else {
                FlashMessages::add('success', trans("messages.destroy_ok"));
            }
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
        } catch (Exception $e) {
            FlashMessages::add("error", trans('messages.delete_error').': '.$e->getMessage());
        }

        return Redirect::route('admin.categories.index');
    }


    private function _fillAdditionTemplateData($model = null)
    {
        $categories = Category::joinTranslations('categories', 'category_translations');
         if($model) {
            $categories = $categories->where('categories.id', '<>', $model->id);
             $model->category_id = $model->parent_id;
         }
        $categories = [NULL => trans('labels.no')] + $categories->lists('name', 'categories.id')->toArray();
        $this->data('categories_array', $categories);
    }
}

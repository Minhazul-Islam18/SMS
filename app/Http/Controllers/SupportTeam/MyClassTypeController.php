<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use Illuminate\Http\Request;
use App\Repositories\UserRepo;
use App\Repositories\MyClassRepo;
use App\Http\Controllers\Controller;
use App\Http\Requests\MyClass\ClassType;
use App\Models\ClassType as ModelsClassType;

class MyClassTypeController extends Controller
{
    protected $user;

    public function __construct(MyClassRepo $my_class, UserRepo $user)
    {
        $this->middleware('teamSA', ['except' => ['destroy',]]);
        $this->middleware('super_admin', ['only' => ['destroy',]]);
        $this->user = $user;
    }
    public function index()
    {
        $class_types = ModelsClassType::all();
        return view('pages.support_team.classes.index', compact('class_types'));
    }
    public function store(ClassType $req)
    {
        $data = $req->all();
        $mc = ModelsClassType::create($data);

        return Qs::jsonStoreOk();
    }

    public function edit($id)
    {
        $d['c'] = $c = ModelsClassType::find($id);

        return is_null($c) ? Qs::goWithDanger('classes.index') : view('pages.support_team.classes.edit_type', $d);
    }

    public function update(ClassType $req, $id)
    {
        $data = $req->all();
        ModelsClassType::find($id)->update($data);

        return Qs::jsonUpdateOk();
    }

    public function destroy($id)
    {
        ModelsClassType::find($id)->delete($id);
        return back()->with('flash_success', __('msg.del_ok'));
    }
}

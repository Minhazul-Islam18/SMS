<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Requests\SettingUpdate;
use App\Models\ClassType;
use App\Repositories\MyClassRepo;
use App\Repositories\SettingRepo;

class SettingController extends Controller
{
    protected $setting, $my_class;

    public function __construct(SettingRepo $setting, MyClassRepo $my_class)
    {
        $this->setting = $setting;
        $this->my_class = $my_class;
    }

    public function index()
    {
        $s = $this->setting->all();
        $d['class_types'] = $this->my_class->getTypes();
        $d['s'] = $s->flatMap(function ($s) {
            return [$s->type => $s->description];
        });
        // $class_type = ClassType::all();
        // dd($d);
        return view('pages.super_admin.settings', $d);
    }

    public function update(SettingUpdate $req)
    {
        $sets = $req->except('_token', '_method', 'logo');
        $sets['lock_exam'] = $sets['lock_exam'] == 1 ? 1 : 0;
        $keys = array_keys($sets);
        $values = array_values($sets);
        for ($i = 0; $i < count($sets); $i++) {
            $this->setting->update($keys[$i], $values[$i]);
        }

        if ($req->hasFile('logo')) {
            $logo = $req->file('logo');
            $f = Qs::getFileMetaData($logo);
            $f['name'] = 'logo.' . $f['ext'];
            // $f['path'] = $logo->storeAs(Qs::getPublicUploadPath(), $f['name']);
            // $logo_path = asset('storage/' . $f['path']);
            $logo_path = $req->logo->move(public_path('assets/images/logo'), $f['name']);
            $this->setting->update('logo', $f['name']);
        }
        $ct = ClassType::select('code')->get();

        foreach ($req as $request) {
            foreach ($ct as $ctc) {
                $code = $ctc->code;
                if ($request->has($code)) {
                    $value = $request->get($code);
                    $find = ClassType::where('code', $code)->first();
                    $find->next_term_fee = $value;
                    $find->save();
                }
            }
        }


        return back()->with('flash_success', __('msg.update_ok'));
    }
}

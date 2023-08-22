@extends('layouts.master')
@section('page_title', 'Edit Class - ' . $c->name)
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Edit Class</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form class="ajax-update" data-reload="#page-header" method="post"
                        action="{{ route('class_type.update', $c->id) }}">
                        @csrf @method('PUT')
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Class Type <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="name" value="{{ old('name') }}" required type="text"
                                    class="form-control" placeholder="Class Type">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="code" class="col-lg-3 col-form-label font-weight-semibold">Code</label>
                            <div class="col-lg-9">
                                <input name="code" value="{{ old('code') }}" required type="text"
                                    class="form-control" placeholder="Code">
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Submit form <i
                                    class="icon-paperplane ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Class Edit Ends --}}

@endsection

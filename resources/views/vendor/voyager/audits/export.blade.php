@extends('voyager::master')

@section('page_title', 'Export')

@section('page_header')
    <h1 class="page-title">
        <i class=""></i> Export &nbsp;
    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <form role="form"
                            class="form-edit-add"
                            method="POST" enctype="multipart/form-data">

                        <!-- CSRF TOKEN -->
                        {{ csrf_field() }}

                        <div class="panel-body">

                            <div class="form-group col-md-12">
                                <label class="control-label" for="start_date">From (created date)</label>
                                <input required="" type="date" class="form-control" name="start_date" id="start_date">
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label" for="end_date">To (created date)</label>
                                <input required="" type="date" class="form-control" name="end_date" id="end_date">
                            </div>

                        </div><!-- panel-body -->

                        <div class="panel-footer">
                            @section('submit-buttons')
                                <button type="submit" class="btn btn-primary save">Export</button>
                            @stop
                            @yield('submit-buttons')
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

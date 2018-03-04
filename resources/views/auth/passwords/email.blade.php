@extends('layouts.master')

@section('content')
<!-- <div class="container" style="float: right; margin-top: 20px">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default"> -->
            <div class="authorization">
                <div class="container-fluid authorization-container">
                    <div class="row">
                        <div class="authorization-container-content">
                            <div class="col-xs-12 col-md-12">
                <div class="panel-heading" style="color: #fff;padding: 5px;text-align: center;font-size: 18px;background-color: #4197e2;">Восстановить пароль</div>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label style="text-align: center;" for="email" class="col-md-12 control-label">E-Mail Адрес</label>

                            <div class="col-md-12">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div style="    text-align: center;" class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    Отправить
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

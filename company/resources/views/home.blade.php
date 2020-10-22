@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Вы дома!</div>
                @if(Auth::user()->role == 1)
                <div class="card-body">
                  <table class="table ">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Тема</th>
                        <th scope="col">Сообщение</th>
                        <th scope="col">Имя клиента</th>
                        <th scope="col">Почта клиента</th>
                        <th scope="col">Ссылка на прикрепленный файл</th>
                        <th scope="col">Дата</th>
                        <th scope="col">Действие</th>
                        <th scope="col">Удалить</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($about as $b)
                      <tr>
                        <th scope="row">{{ $b->id }}</th>
                        <td>{{ $b->theme }}</td>
                        <td>{{ $b->message }}</td>
                        <td>{{ $b->name }}</td>
                        <td>{{ $b->email }}</td>
                        <td>{{ $b->link }}</td>
                        <td>{{ $b->created_at }}</td>
                        @if($b->status == 1)<td><a href="{{ route('success', $b->id)}}" style="color:blue;pointer-events: none !important;">Подтвердить</a></td> @else <td><a href="{{ route('success', $b->id)}}" style="color:green;">Подтвердить</a></td> @endif
                        <td><a href="{{ route('destroy', $b->id)}}" style="color: red;">Удалить</a></td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                @else
                <div class="card-body">
                  <form method="post" action="about" enctype="multipart/form-data" runat="server">
                    @csrf
                    <div class="form-group">
                      <label>Тема обращения</label>
                      <input type="text" name="theme" class="form-control">
                    </div>

                    <div class="form-group">
                      <label>Введите имя</label>
                      <input type="text" name="name" class="form-control">
                      <small class="form-text text-muted">Это будет показано модераторам, акуратнее)</small>
                    </div>

                    <div class="form-group">
                      <label>Почта</label>
                      <input type="email" name="email" class="form-control">
                    </div>

                    <div class="form-group">
                      <label>Сообщение</label>
                      <textarea name="message" class="form-control"></textarea>
                    </div>

                    <div class="form-group">
                      <label>Прикрепите файл (Не обязательно)</label>
                      <input type="file" name="link[]" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary">Отправить</button>
                  </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<style>
.alert-success {
  color: #1d643b;
  background-color: #d7f3e3;
  border-color: #c7eed8;
  position: fixed;
  top: 82px;
  right: 25px;
}
.alert.alert-error {
    color: #ffffff;
    background-color: #f3d7d7;
    border-color: #f3d7d7;
    position: fixed;
    top: 82px;
    right: 25px;
}
</style>
@if(Session::has('success'))
<div class="alert alert-success">
    {{Session::get('success')}}
</div>
@endif
@if(Session::has('error'))
<div class="alert alert-error">
    {{Session::get('error')}}
</div>
@endif
@endsection

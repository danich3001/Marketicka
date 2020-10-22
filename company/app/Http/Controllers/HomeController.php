<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;
use App\Abouts;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $about = Abouts::get();
        return view('home', ['about' => $about]);
    }

    public function success($id)
    {
        $about = Abouts::where('id', $id)->first();
        $about->update([
          'status' => 1
        ]);
        return back();
    }

    public function destroy($id)
    {
        $about = Abouts::where('id', $id)->delete();
        return back();
    }

    public function about(Request $r)
    {
        $about = Abouts::first();
        // Проверяем на наличие записей в базе, если нету то не проверяем на время (Без данной проверки в следующей записи он ищет user_id которого нет!)
        if($about == null){
          // Указываем директорию
          $destinationPath = public_path('files');
          // Получаем link из input
          $file = $r->file('link');
          // Создаём запись в базе
          $send = Abouts::create([
            'theme' => $r->input('theme'),
            'name' => $r->input('name'), // Можно было и не запрашивать => Auth::user()->name
            'message' => $r->input('message'),
            'user_id' => Auth::user()->id,
            'email' => $r->input('email')
          ]);

          if($file){
            $images_array = [];
            $images_index = '';
            foreach ($r->file() as $file) {
              // Принимаем все файлы и заносим их в папку files
              foreach ($file as $key => $f) {
                if($key == 0) {
                  $images_index = '/files'.'/'.time().''.$f->getClientOriginalName();
                }
                  $images_array[$key] = '/files'.'/'.time().''.$f->getClientOriginalName();
                  $f->move(public_path('files'), time().''.$f->getClientOriginalName());
              }
            }
            // Обновляем файл который ищем по имени
            Abouts::where('name', $r->get('name'))->update([
              'link' => $images_index
            ]);
          }
          return back();
        } elseif(Abouts::where('user_id', Auth::user()->id)->where('created_at', '<', Carbon::now()->subMinutes(1440))->first()){
        // Тут делаем проверку на ID юзера и проверяем что с момента создания его обращения прошло ровно 1440 мин = 1 день.
        // Указываем директорию
        $destinationPath = public_path('files');
        // Получаем link из input
        $file = $r->file('link');
        // Создаём запись в базе
        $send = Abouts::create([
          'theme' => $r->input('theme'),
          'name' => $r->input('name'), // Можно было и не запрашивать => Auth::user()->name
          'user_id' => Auth::user()->id,
          'email' => $r->input('email')
        ]);

        if($file){
          $images_array = [];
          $images_index = '';
          foreach ($r->file() as $file) {

            foreach ($file as $key => $f) {
              if($key == 0) {
                $images_index = '/files'.'/'.time().''.$f->getClientOriginalName();
              }
                $images_array[$key] = '/files'.'/'.time().''.$f->getClientOriginalName();
                $f->move(public_path('files'), time().''.$f->getClientOriginalName());
            }
          }

          Abouts::where('name', $r->get('name'))->update([
            'link' => $images_index
          ]);

        }
        return back();

      } else {
        // Выводим ответ юзеру...
        return redirect()->back()->with('success', 'Запрещено отправлять заявку более одного раза в сутки!');
      }
    }
}

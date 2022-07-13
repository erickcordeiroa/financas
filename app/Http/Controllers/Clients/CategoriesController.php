<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Clients\AppCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\Catch_;

class CategoriesController extends Controller
{
    public function index()
    {
        return view('client.categories.index', [
            'categories' => AppCategory::where('user_id', Auth::user()->id)->paginate(25)
        ]);
    }

    public function create()
    {
        return view('client.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->only(['name', 'type']);

        $validate = Validator::make($data, [
            'name' => "string|required|min:2",
            'type' => "string|required"
        ]);

        if($validate->fails()){
            return redirect()->route('app.categories.create')
                ->withErrors($validate);
        }

        $category = new AppCategory();
        $category->name = $data['name'];
        $category->type = $data['type'];
        $category->user_id = Auth::user()->id;
        $category->save();

        return redirect()->route('app.categories')
                ->with('success', 'Parabéns categoria cadastrada com sucesso!');
    }

    public function edit($id)
    {
        return view('client.categories.edit', [
            'category' => AppCategory::where('user_id', Auth::user()->id)->where('id', $id)->first()
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->only(['name', 'type']);

        $validate = Validator::make($data, [
            'name' => "string|required|min:2",
            'type' => "string|required"
        ]);

        if($validate->fails()){
            return redirect()->route('app.categories.edit', ['id' => $id])
                ->withErrors($validate);
        }

        $category = AppCategory::find($id);
        $category->name = $data['name'];
        $category->type = $data['type'];
        $category->user_id = Auth::user()->id;
        $category->save();

        return redirect()->route('app.categories')
                ->with('success', 'Parabéns categoria cadastrada com sucesso!');
    }

    public function destroy($id)
    {
        $category = AppCategory::find($id);

        if(!$category){
            return redirect()->route('app.categories')
                ->with('errors', 'Ocorreu um erro ao excluir a sua categoria');
        }

        $category->delete();

        return redirect()->route('app.categories')
                ->with('success', 'Parabéns categoria excluida com sucesso!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Bookdata;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Bookdata::all();
        return view('book.index', ['books' => $books]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('book.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $book = new Bookdata;
      $form = $request->all();
      unset($form['_token']);
      $book->fill($form)->save();
      return redirect('/book');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $book = Bookdata::find($id);
      // $action = "/book/";
      return view('book.edit', ['form' => $book]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      // $param = [
      //   'id' => $request->id,
      //   'name' => $request->name,
      //   'mail' => $request->mail,
      //   'age' => $request->age,
      // ];
      // DB::table('people')
      //   ->where('id', $request->id)
      //   ->update($param);
      // return redirect('/hello');

      // $book = Bookdata::find($id);
      // $form = $request->all();
      // unset($form['_token']);
      // // $book->fill($form)->update();
      // $book->fill($form)->save();
      // return redirect('/book');

      // DB::table('people')
      //   ->where('id', $request->id)
      //   ->update($param);

      $book = Bookdata::find($id);
      $form = $request->all();
      unset($form['_token']);
      $book->fill($form)->save();
      return redirect('/book');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

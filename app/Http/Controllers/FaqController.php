<?php

namespace App\Http\Controllers;

use App\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ImageUpload;
use Auth;
use App\Logging;

class FaqController extends Controller
{
    public function load(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        Logging::log("Faq Screen");

        return FaqController::view();
    }

    public function view(){
        $faq = DB::table('faq')->get();
        return view('faq', ['ifaq' => $faq, 'texton' => "", 'text' => '']);
    }

    public function add(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        FaqController::dataMassive($request, 1);
        return FaqController::view();
    }

    public function delete(Request $request){
        if (!Auth::check())
            return \Redirect::route('/');

        $id = $request->input('id');
        $question = DB::table('faq')->where('id',$id)->get()->first()->question;
        if (Settings::isDemoMode()){
            Logging::log3("Faq->delete", "Question:" . $question, "Abort! This is demo mode");
            return response()->json(['ret'=>false, 'text'=>'This is demo app. You can\'t change this section']);
        }

        Logging::log3("Faq delete", "Question:" . $question, "");

        DB::table('faq')
            ->where('id',$id)
            ->delete();

        return response()->json(['ret'=>true]);
    }

    function dataMassive(Request $request, $update){
        $id = $request->input('id');

        $question = $request->input('question') ?: "";
        $answer = $request->input('answer') ?: "";
        $published = $request->input('published') ?: "";
        if ($published == 'on') $published = true;  else $published = false;

        $values = array('question' => $question, 'answer' => $answer, 'published' => $published,
            'updated_at' => new \DateTime());

        if ($update == 2){
            DB::table('faq')
                ->where('id',$id)
                ->update($values);
            Logging::log2("Faq->Update", "Question:" . $question);
        }
        if ($update == 1){
            $values['created_at'] = new \DateTime();
            DB::table('faq')->insert($values);
            Logging::log2("Faq->Insert", "Question:" . $question);
        }
    }

    public function edit(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        FaqController::dataMassive($request, 2);
        return FaqController::view();
    }

}

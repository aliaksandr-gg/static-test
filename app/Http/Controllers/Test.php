<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Test extends Controller{
    public function test1(Request  $request)
    {
        $bb = 'test';
        echo $bb['test'];
        $a = DB::table('users')
            ->where('id', $request->get('test'))
            ->first();
        $b = DB::table('users')
            ->where('id', $_POST['test'])
            ->first();
        $sql = 'SELECT * FROM users WHERE id=' . $request->input('test');
        $c = DB::select($sql);

        $newPassword = $_POST['test'];
        $username = $_POST['username'];
        DB::statement("UPDATE users SET password=".$newPassword. "  WHERE username =" . $username);

        return response()->json([
            'data' => $a,
        ]);
    }

    public function test2(Request $request)  {
        $dompdf = new Dompdf();
        $dompdf->loadHtml('TEST');
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream();
    }

    public function test3() {
        $a = [];
        echo $a['aaa'];
        echo 'Test ' . $_GET['country'];
    }
}

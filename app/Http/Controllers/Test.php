<?php

namespace App\Http\Controllers;

use App\Services\TestCorrectService;
use App\Services\TestIncorrectService;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Test extends Controller{
    public const bbbbb = 'b';
    public function test1(Request  $request)
    {
        $aaaa = [];
        $bb = 'test';
        $aaaa['test'];
        echo $bb['test'];
        $a = DB::table('users')
            ->where('id', $request->get('test'))
            ->first();
        $b = DB::table('users')
            ->where('id', $_POST['test'])
            ->first();
        $sql = 'SELECT * FROM users WHERE id=' . $request->input('test');
        $c = DB::select($sql);
       // $a =  new TestIncorrectService();

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
        $a =  new TestCorrectService();
    }

    public function _test() {
        $a = 1;
        $c = 0;
        $b = $a / $c;
    }

    public function _test_rec() {
        $a = fn () => $this->_test_();
        return $a();
    }

    public function _test_rec_2() {
        $a = function () {
            echo 'test';
            $this->_test_test();
        };
        $a();
    }

    public function _test_rec_3() {
        echo 'test rec cycled';
        $this->_test_rec_3();
    }
}

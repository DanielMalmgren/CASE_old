<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Announcement;
use App\ClosedMonth;

class HomeController extends Controller
{
    public function index() {
        if(empty(Auth::user()["workplace_id"]) || ! Auth::user()->accepted_gdpr) {
            return redirect('/firstlogin');
        } else {
            $announcements = Announcement::All()->sort()->reverse()->take(5);

            setlocale(LC_TIME, Auth::user()->locale_id);
            $previous_month = date("m", strtotime("first day of previous month"));
            $previous_month_year = date("Y", strtotime("first day of previous month"));
            $monthstr = strftime('%B', strtotime("first day of previous month"));

            $last_month_is_closed = ClosedMonth::all()->where('month', $previous_month)->where('year', $previous_month_year)->isNotEmpty();
            $last_month_is_attested = Auth::user()->time_attests->where('attestlevel', 1)->where('month', $previous_month)->where('year', $previous_month_year)->isNotEmpty();
            $time_rows = Auth::user()->time_rows($previous_month_year, $previous_month);
            $time = end($time_rows)[32];

            $data = [
                'announcements' => $announcements,
                'lesson' => Auth::user()->next_lesson(),
                'should_attest' => !$last_month_is_closed && !$last_month_is_attested && $time>=1.0 && Auth::user()->workplace->includetimeinreports,
                'previous_month' => $previous_month,
                'monthstr' => $monthstr,
            ];
            return view('pages.index')->with($data);
        }
    }

    public function about() {
        return view('pages.about');
    }

    /*public function login() {
        return view('pages.login');
    }*/

    public function logout() {
        session()->flush();
        Auth::logout();
        return view('pages.logout');
    }
}

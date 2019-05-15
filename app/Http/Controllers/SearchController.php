<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lesson;
use App\Track;
use App\Announcement;

class SearchController extends Controller
{
    //Return a json containing lessons, tracks and announcements matching a search string sent from a select2 object. See https://select2.org/data-sources/ajax
    public function select2(Request $request) {
        $results = ['results' => []];

        $i=-1;

        $lessons = Lesson::whereTranslationLike('name', '%'.$request->q.'%')->orWhereHas('contents', function ($q) use($request){
            $q->where('content', 'like', '%'.$request->q.'%')->orWhereTranslationLike('text', '%'.$request->q.'%');
        })->get();
        if($lessons->isNotEmpty()) {
            $results['results'][++$i]['text'] = _('Lektioner');
            foreach($lessons as $key => $lesson) {
                $results['results'][$i]['children'][$key] = [
                    'id' => $lesson->id,
                    'text' => $lesson->name,
                    'url' => '/lessons/'.$lesson->id
                ];
            }
        }

        $tracks = Track::whereTranslationLike('name', '%'.$request->q.'%')->orWhereTranslationLike('subtitle', '%'.$request->q.'%')->get();
        if($tracks->isNotEmpty()) {
            $results['results'][++$i]['text'] = _('Spår');
            foreach($tracks as $key => $track) {
                $results['results'][$i]['children'][$key] = [
                    'id' => $track->id,
                    'text' => $track->name,
                    'url' => '/track/'.$track->id
                ];
            }
        }

        $announcements = Announcement::where('heading', 'like', '%'.$request->q.'%')->orWhere('preamble', 'like', '%'.$request->q.'%')->get();
        if($announcements->isNotEmpty()) {
            $results['results'][++$i]['text'] = _('Nyheter');
            foreach($announcements as $key => $announcement) {
                $results['results'][$i]['children'][$key] = [
                    'id' => $announcement->id,
                    'text' => $announcement->heading,
                    'url' => '/announcements/'.$announcement->id
                ];
            }
        }

        return $results;
    }
}

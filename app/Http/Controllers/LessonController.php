<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lesson;
use App\Track;
use App\Question;
use App\Title;
use App\LessonResult;
use App\Content;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    public function show(Lesson $lesson) {
        $question = Question::where('lesson_id', $lesson->id)->first();
        $lesson->times_started++;
        $lesson->save();
        $data = [
            'question' => $question,
            'lesson' => $lesson,
        ];
        return view('lessons.show')->with($data);
    }

    public function create(Track $track) {
        $titles = Title::all();
        $data = [
            'track' => $track,
            'titles' => $titles,
        ];
        return view('lessons.create')->with($data);
    }

    public function store(Request $request) {
        usleep(50000);
        $this->validate($request, [
            'name' => 'required',
            'track_id' => 'required',
        ],
        ['name.required' => __('Du måste ange ett namn på lektionen!')]);

        $currentLocale = \App::getLocale();

        $track = Track::find($request->track_id);

        $lesson = new Lesson();
        $lesson->track_id = $request->track_id;
        $lesson->order = $track->lessons->max('order')+1;
        $lesson->translateOrNew($currentLocale)->name = $request->name;
        $lesson->save();

        return $this->update($request, $lesson);
    }

    public function vote(Request $request, Lesson $lesson) {
        $lesson_result = LessonResult::where([['user_id', '=', Auth::user()->id],['lesson_id', '=', $lesson->id]])->first();
        $lesson_result->rating = $request->vote;
        $lesson_result->save();
    }

    public function reorder(Request $request) {
        parse_str($request->data, $data);
        $ids = $data['id'];

        foreach($ids as $order => $id){
            $lesson = Lesson::findOrFail($id);
            $lesson->order = $order+1;
            $lesson->save();
        }
    }

    public function edit(Lesson $lesson) {
        $titles = Title::all();
        $data = [
            'lesson' => $lesson,
            'titles' => $titles,
        ];
        return view('lessons.edit')->with($data);
    }

    public function editquestions(Lesson $lesson) {
        $questions = $lesson->questions->sortBy('order');
        $data = [
            'lesson' => $lesson,
            'questions' => $questions,
        ];
        return view('lessons.editquestions')->with($data);
    }

    public function finish(Lesson $lesson) {
        LessonResult::updateOrCreate(
            ['user_id' => Auth::user()->id, 'lesson_id' => $lesson->id]
        );
        return redirect('/');
    }

    public function update(Request $request, Lesson $lesson) {
        usleep(50000);
        $this->validate($request, [
            'name' => 'required',
            'new_audio.*' => 'file|mimetypes:audio/mpeg|max:20000',
            'new_office.*' => 'file|mimetypes:application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.presentationml.presentation|max:20000',
            'new_image.*' => 'file|image|max:20000',
            'new_file.*' => 'file|max:20000',
            'new_html.*' => 'string',
            'html.*' => 'string',
            'new_vimeo.*' => 'integer',
            'vimeo.*' => 'integer',
        ],
        [
            'name.required' => __('Du måste ange ett namn på lektionen!'),
            'new_audio.*.mimetypes' => __('Din ljudfil måste vara i mp3-format!'),
            'new_office.*.mimetypes' => __('Din file måste vara antingen ett Word-dokument, en Excel-fil eller en Powerpoint-presentation!'),
            'new_image.*.image' => __('Felaktigt bildformat!'),
            'new_audio.*.file' => __('Du måste välja en fil att ladda upp!'),
            'new_office.*.file' => __('Du måste välja en fil att ladda upp!'),
            'new_image.*.file' => __('Du måste välja en fil att ladda upp!'),
            'new_file.*.file' => __('Du måste välja en fil att ladda upp!'),
            'new_audio.*.max' => __('Din fil är för stor! Max-storleken är 20MB!'),
            'new_office.*.max' => __('Din fil är för stor! Max-storleken är 20MB!'),
            'new_image.*.max' => __('Din fil är för stor! Max-storleken är 20MB!'),
            'new_file.*.max' => __('Din fil är för stor! Max-storleken är 20MB!'),
            'new_html.*.string' => __('Du måste skriva någon text i textrutan!'),
            'html.*.string' => __('Du måste skriva någon text i textrutan!'),
            'new_vimeo.*.integer' => __('Ett giltigt Vimeo-id har bara siffror!'),
            'vimeo.*.integer' => __('Ett giltigt Vimeo-id har bara siffror!'),
        ]);

        $currentLocale = \App::getLocale();
        $user = Auth::user();
        logger("Lesson ".$lesson->id." is being edited by ".$user->name);

        //Store this in a local variable. We'll have to replace all the temporary id's in it for real ones before we do the ordering
        $content_order = $request->content_order;

        //Loop through all changed html contents
        if($request->html) {
            foreach($request->html as $html_id => $html_text) {
                $content = Content::find($html_id);
                $content->translateOrNew($currentLocale)->text = $content->add_target_to_links($html_text);
                $content->save();
                logger("HTML content ".$html_id." is being changed");
            }
        }

        //Loop through all added html contents
        if($request->new_html) {
            foreach($request->new_html as $temp_key => $new_html) {
                $content = new Content('html', $lesson->id, null, $new_html);
                $content_order = str_replace("[".$temp_key."]", "[".$content->id."]", $content_order);
                logger("HTML content ".$content->id." is being added");
            }
        }

        //Loop through all deleted html contents
        if($request->remove_html) {
            foreach(array_keys($request->remove_html) as $remove_html_id) {
                Content::destroy($remove_html_id);
                logger("HTML content ".$remove_html_id." is being removed");
            }
        }

        //Loop through all changed vimeo contents
        if($request->vimeo) {
            foreach($request->vimeo as $vimeo_id => $vimeo_text) {
                $content = Content::find($vimeo_id);
                $content->content = $vimeo_text;
                $content->save();
                logger("Vimeo content ".$vimeo_id." is being changed");
            }
        }

        //Loop through all added vimeo contents
        if($request->new_vimeo) {
            foreach($request->new_vimeo as $temp_key => $new_vimeo) {
                $content = new Content('vimeo', $lesson->id, $new_vimeo);
                $content_order = str_replace("[".$temp_key."]", "[".$content->id."]", $content_order);
                logger("Vimeo content ".$content->id." is being added");
            }
        }

        //Loop through all deleted vimeo contents
        if($request->remove_vimeo) {
            foreach(array_keys($request->remove_vimeo) as $remove_vimeo_id) {
                Content::destroy($remove_vimeo_id);
                logger("Vimeo content ".$remove_vimeo_id." is being removed");
            }
        }

        //Loop through all added audio contents
        if($request->new_audio) {
            foreach($request->new_audio as $temp_key => $new_audio) {
                $content = new Content('audio', $lesson->id, $new_audio->getClientOriginalName());
                $new_audio->storeAs("public/files/", $content->filename());
                $content_order = str_replace("[".$temp_key."]", "[".$content->id."]", $content_order);
                logger("Audio content ".$content->id." is being added");
            }
        }

        //Loop through all deleted audio contents
        if($request->remove_audio) {
            foreach(array_keys($request->remove_audio) as $remove_audio_id) {
                $content = Content::find($remove_audio_id);
                Content::destroy($remove_audio_id);
                logger("Deleting public/files/".$content->filename()." from disk");
                Storage::delete("public/files/".$content->filename());
            }
        }

        //Loop through all added office contents
        if($request->new_office) {
            foreach($request->new_office as $temp_key => $new_office) {
                $content = new Content('office', $lesson->id, $new_office->getClientOriginalName());
                $new_office->storeAs("public/files/", $content->filename());
                $content_order = str_replace("[".$temp_key."]", "[".$content->id."]", $content_order);
                logger("Office content ".$content->id." is being added");
            }
        }

        //Loop through all deleted office contents
        if($request->remove_office) {
            foreach(array_keys($request->remove_office) as $remove_office_id) {
                $content = Content::find($remove_office_id);
                Content::destroy($remove_office_id);
                logger("Deleting public/files/".$content->filename()." from disk");
                Storage::delete("public/files/".$content->filename());
            }
        }

        //Loop through all added image files
        if($request->new_image) {
            foreach($request->new_image as $temp_key => $new_image) {
                $content = new Content('image', $lesson->id, $new_image->getClientOriginalName());
                $new_image->storeAs("public/files/", $content->filename());
                $content_order = str_replace("[".$temp_key."]", "[".$content->id."]", $content_order);
                logger("Image content ".$content->id." is being added");
            }
        }

        //Loop through all deleted image files
        if($request->remove_image) {
            foreach(array_keys($request->remove_image) as $remove_image_id) {
                $content = Content::find($remove_image_id);
                Content::destroy($remove_image_id);
                logger("Deleting public/files/".$content->filename()." from disk");
                Storage::delete("public/files/".$content->filename());
            }
        }

        //Loop through all added file contents
        if($request->new_file) {
            foreach($request->new_file as $temp_key => $new_file) {
                $content = new Content('file', $lesson->id, $new_file->getClientOriginalName());
                $new_file->storeAs("public/files/", $content->filename());
                $content_order = str_replace("[".$temp_key."]", "[".$content->id."]", $content_order);
                logger("File content ".$content->id." is being added");
            }
        }

        //Loop through all deleted file contents
        if($request->remove_file) {
            foreach(array_keys($request->remove_file) as $remove_file_id) {
                $content = Content::find($remove_file_id);
                Content::destroy($remove_file_id);
                logger("Deleting public/files/".$content->filename()." from disk");
                Storage::delete("public/files/".$content->filename());
            }
        }

        //Fix sort order of all contents
        $i = 0;
        if(strlen($content_order) > 0) {
            foreach(explode(",", $content_order) as $order) {
                preg_match('#\[(.*?)\]#', $order, $match); //Exctract the id, which is between []
                $id = $match[1];
                $content = Content::find($id);
                if($content) {
                    $content->order = $i;
                    $content->save();
                    $i++;
                }
            }
        }

        $lesson->translateOrNew($currentLocale)->name = $request->name;
        $lesson->active = $request->active;
        $lesson->limited_by_title = $request->limited_by_title;
        $lesson->save();

        $lesson->titles()->sync($request->titles);

        return redirect('/lessons/'.$lesson->id)->with('success', __('Ändringar sparade'));
    }
}

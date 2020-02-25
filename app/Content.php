<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use \Astrotomic\Translatable\Translatable;

    public $translatedAttributes = ['text'];

    function __construct($type=null, $lesson_id=null, $content=null, $text=null) {
        parent::__construct();
        if($type != null) {
            $currentLocale = \App::getLocale();
            $this->type = $type;
            $this->lesson_id = $lesson_id;
            $this->content = $content;
            if($text != null) {
                $this->translateOrNew($currentLocale)->text = $this->add_target_to_links($text);
            }
            $this->save();
        }
    }

    public function lesson()
    {
        return $this->belongsTo('App\Lesson');
    }

    public function url() {
        return env('APP_URL')."/storage/files/".$this->id.'.'.pathinfo($this->content, PATHINFO_EXTENSION);
    }

    public function filename() {
        return $this->id.'.'.pathinfo($this->content, PATHINFO_EXTENSION);
    }

    public static function add_target_to_links($text) {
        return str_replace('<a href=', '<a target="_blank" href=', $text);
    }
}


class ContentTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['text'];
}

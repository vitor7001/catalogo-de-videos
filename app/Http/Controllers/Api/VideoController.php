<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends BasicCrudController
{
    private $rules;

    public function __construct()
    {
       $this->rules = [
        'title' => 'required|max:255',
        'description' => 'required',
        'year_launched' => 'required|date_format:Y',
        'opened' => 'boolean',
        'rating' => 'required|in:' . implode(',', Video::RATING_LIST),
        'duration' => 'required|integer',      
       ];
    }


    public function model()
    {
        return Video::class;
    }

    public function rulesStore()
    {
        return $this->rules;
    }

    public function rulesUpdate()
    {
        return $this->rules;
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use App\Http\Requests\StoreResume;
use App\Http\Requests\UpdateResume;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ResumesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(
            [
                'resumes' => auth()->user()->resumesSlagId,
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreResume $request)
    {
        $user = auth()->user();
        $validated = $request->validated();

        if(count($user->resumes) < 3 || !isset($user->resumes)) {
            $validated += ['user_id' => $user->id];

            Resume::create($validated);

            return response()->json(
                [
                    'success' => 'Resume '.$validated['slug'].' user`s '.$user->name.' created!',
                ],
                201
            );
        }
        else{
            return response()->json(
                [
                    'message' => 'Resume '.$validated['slug'].' user`s '.$user->name.' no created! You already have three resumes!',
                ],
                409
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateResume $request, $slug)
    {
        $resume = Resume::slug($slug);
        if ($resume->user->id == auth()->user()->id) {
            $validated = $request->validated();
            $resume->update($validated);

            return response()->json(
                [
                    'success' => 'Resume '.$resume->slug.' update!',
                ],
                201
            );
        }
        else {
            return response()->json(
                [
                    'message' => 'Unauthorized!',
                ],
                401
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  object  $resume
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Resume $resume)
    {
        if ($resume->user->id === auth()->user()->id) {
            $resume->delete();
            $resumes = auth()->user()->resumes;

            return $resumes;
        }
        else {
            return response()->json(
                [
                    'message' => 'Unauthorized!',
                ],
                401
            );
        }

    }
}

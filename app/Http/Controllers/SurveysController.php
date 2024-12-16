<?php

namespace App\Http\Controllers;

use App\Models\Surveys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SurveysController extends Controller
{
    public function index()
    {
        $surveys = Surveys::with('sections.content')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data successfully fetched',
            'data' => $surveys
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'surveyTitle' => 'required|string|max:255',
            'surveyDescription' => 'nullable|string|max:255',
            'backgroundImage' => 'required|file|mimes:jpeg,png,jpg,gif|max:10240',
            'thumbnail' => 'required|file|mimes:jpeg,png,jpg,gif|max:10240',
            'sections' => 'required|array',
            'sections.*.title' => 'required|string|max:255',
            'sections.*.content' => 'nullable|array',
            'sections.*.contentText' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = [];

        // Handle file uploads
        if ($request->hasFile('backgroundImage')) {
            $backgroundImage = $request->file('backgroundImage');
            $validated['backgroundImage'] = $backgroundImage->store('background', 'public');
        }

        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $validated['thumbnail'] = $thumbnail->store('thumbnail', 'public');
        }

        // Create the survey
        $survey = Surveys::create([
            'user_id' => auth()->id(),
            'surveyTitle' => $request->surveyTitle,
            'surveyDescription' => $request->surveyDescription,
            'backgroundImage' => $validated['backgroundImage'],
            'thumbnail' => $validated['thumbnail'],
            'bgColor' => $request->bgColor,
            'createdByAI' => $request->createdByAI,
            'respondents' => $request->respondents,
            'maxRespondents' => $request->maxRespondents,
            'coinAllocated' => $request->coinAllocated,
            'coinUsed' => $request->coinUsed,
            'kriteria' => $request->kriteria,
            'status' => $request->status,
        ]);

        // Handle sections and content
        foreach ($request->sections as $sectionData) {
            $section = $survey->sections()->create([
                'icon' => $sectionData['icon'] ?? null,
                'label' => $sectionData['label'] ?? null,
            ]);

            if (isset($sectionData['content'])) {
                foreach ($sectionData['content'] as $contentData) {
                    $section->content()->create([
                        'bgColor' => $contentData['bgColor'] ?? '#FFFFFF',
                        'bgOpacity' => $contentData['bgOpacity'] ?? 1,
                        'buttonColor' => $contentData['buttonColor'] ?? null,
                        'buttonText' => $contentData['buttonText'] ?? null,
                        'buttonTextColor' => $contentData['buttonTextColor'] ?? null,
                        'contentText' => $contentData['contentText'] ?? null,
                        'dateFormat' => $contentData['dateFormat'] ?? null,
                        'description' => $contentData['description'] ?? null,
                        'largeLabel' => $contentData['largeLabel'] ?? null,
                        'listChoices' => $contentData['listChoices'] ?? null,
                        'maxChoices' => $contentData['maxChoices'] ?? null,
                        'midLabel' => $contentData['midLabel'] ?? null,
                        'minChoices' => $contentData['minChoices'] ?? null,
                        'mustBeFilled' => $contentData['mustBeFilled'] ?? false,
                        'optionsCount' => $contentData['optionsCount'] ?? null,
                        'otherOption' => $contentData['otherOption'] ?? false,
                        'smallLabel' => $contentData['smallLabel'] ?? null,
                        'textColor' => $contentData['textColor'] ?? null,
                        'timeFormat' => $contentData['timeFormat'] ?? null,
                        'toggleResponseCopy' => $contentData['toggleResponseCopy'] ?? false,
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Survey successfully created with sections and content',
            'data' => $survey->load('sections.content')
        ], 201);
    }

    public function show($id)
    {
        $survey = Surveys::with('sections.content')->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Survey data retrieved successfully',
            'data' => $survey
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $survey = Surveys::findOrFail($id);

        $request->validate([
            'surveyTitle' => 'required|string|max:255',
            'surveyDescription' => 'nullable|string|max:255',
            'sections' => 'nullable|array',
            'sections.*.title' => 'nullable|string|max:255',
            'sections.*.contentText' => 'nullable|string',
        ]);

        $validated = [];

        // Handle file uploads if any
        if ($request->hasFile('backgroundImage')) {
            $backgroundImage = $request->file('backgroundImage');
            $validated['backgroundImage'] = $backgroundImage->store('background', 'public');
        }

        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $validated['thumbnail'] = $thumbnail->store('thumbnail', 'public');
        }

        // Update the survey data
        $survey->update([
            'surveyTitle' => $request->surveyTitle,
            'surveyDescription' => $request->surveyDescription,
            'backgroundImage' => $validated['backgroundImage'] ?? $survey->backgroundImage,
            'bgColor' => $request->bgColor,
            'createdByAI' => $request->createdByAI,
            'respondents' => $request->respondents,
            'maxRespondents' => $request->maxRespondents,
            'coinAllocated' => $request->coinAllocated,
            'coinUsed' => $request->coinUsed,
            'kriteria' => $request->kriteria,
            'status' => $request->status,
            'thumbnail' => $validated['thumbnail'] ?? $survey->thumbnail,
        ]);

        // Update sections and content
        if ($request->has('sections')) {
            foreach ($request->sections as $sectionData) {
                $section = $survey->sections()->updateOrCreate([
                    'id' => $sectionData['id'] ?? null
                ], [
                    'icon' => $sectionData['icon'] ?? null,
                    'label' => $sectionData['label'] ?? null,
                ]);

                if (isset($sectionData['content'])) {
                    foreach ($sectionData['content'] as $contentData) {
                        $section->content()->updateOrCreate([
                            'id' => $contentData['id'] ?? null
                        ], [
                            'bgColor' => $contentData['bgColor'] ?? '#FFFFFF',
                            'bgOpacity' => $contentData['bgOpacity'] ?? 1,
                            'buttonColor' => $contentData['buttonColor'] ?? null,
                            'buttonText' => $contentData['buttonText'] ?? null,
                            'buttonTextColor' => $contentData['buttonTextColor'] ?? null,
                            'contentText' => $contentData['contentText'] ?? null,
                            'dateFormat' => $contentData['dateFormat'] ?? null,
                            'description' => $contentData['description'] ?? null,
                            'largeLabel' => $contentData['largeLabel'] ?? null,
                            'listChoices' => $contentData['listChoices'] ?? null,
                            'maxChoices' => $contentData['maxChoices'] ?? null,
                            'midLabel' => $contentData['midLabel'] ?? null,
                            'minChoices' => $contentData['minChoices'] ?? null,
                            'mustBeFilled' => $contentData['mustBeFilled'] ?? false,
                            'optionsCount' => $contentData['optionsCount'] ?? null,
                            'otherOption' => $contentData['otherOption'] ?? false,
                            'smallLabel' => $contentData['smallLabel'] ?? null,
                            'textColor' => $contentData['textColor'] ?? null,
                            'timeFormat' => $contentData['timeFormat'] ?? null,
                            'toggleResponseCopy' => $contentData['toggleResponseCopy'] ?? false,
                        ]);
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Survey successfully updated',
            'data' => $survey->load('sections.content')
        ], 200);
    }

    public function destroy($id)
    {
        $survey = Surveys::findOrFail($id);
        $survey->delete();

        return response()->json([
            'success' => true,
            'message' => 'Survey deleted successfully',
        ], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Surveys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SurveysController extends Controller
{
    public function index()
    {
        $surveys = Surveys::with('sections')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data successfully fetched',
            'data' => $surveys
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:draft,published',
            'surveyTitle' => 'required|string|max:255',
            'surveyDescription' => 'nullable|string|max:255',
            // 'backgroundImage' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:10240|string|url',
            // 'thumbnail' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:10240|string|url',
            'sections' => 'required|array|min:1',
            'sections.*.label' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $bgImgUrl = $this->processImage($request, 'backgroundImage');
        $thumbnailUrl = $this->processImage($request, 'thumbnail');

        $survey = Surveys::create([
            'user_id' => $request->user_id,
            'surveyTitle' => $request->surveyTitle,
            'surveyDescription' => $request->surveyDescription,
            'backgroundImage' => $bgImgUrl,
            'thumbnail' => $thumbnailUrl,
            'bgColor' => $request->bgColor,
            'createdByAI' => $request->createdByAI,
            'respondents' => $request->respondents,
            'maxRespondents' => $request->maxRespondents,
            'coinAllocated' => $request->coinAllocated,
            'coinUsed' => $request->coinUsed,
            'kriteria' => $request->kriteria,
            'status' => $request->status,
        ]);

        $this->processSections($survey, $request->sections, $survey->id);

        return response()->json([
            'success' => true,
            'message' => 'Survey successfully created',
            'data' => $survey
        ], 201);
    }

    public function show($id)
    {
        $survey = Surveys::with('sections')->findOrFail($id);

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
            // 'backgroundImage' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:10240|string|url',
            // 'thumbnail' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:10240|string|url',
            'sections' => 'nullable|array',
        ]);
        
        $bgImgUrl = $this->processImage($request, 'backgroundImage', $survey->backgroundImage);
        $thumbnailUrl = $this->processImage($request, 'thumbnail', $survey->thumbnail);

        $survey->update([
            'surveyTitle' => $request->surveyTitle,
            'surveyDescription' => $request->surveyDescription,
            'backgroundImage' => $bgImgUrl,
            'thumbnail' => $thumbnailUrl,
            'bgColor' => $request->bgColor,
            'createdByAI' => $request->createdByAI,
            'respondents' => $request->respondents,
            'maxRespondents' => $request->maxRespondents,
            'coinAllocated' => $request->coinAllocated,
            'coinUsed' => $request->coinUsed,
            'kriteria' => $request->kriteria,
            'status' => $request->status,
        ]);

        if ($request->has('sections')) {
            $this->processSections($survey, $request->sections, $survey->id);
        }

        return response()->json([
            'success' => true,
            'message' => 'Survey updated successfully',
            'data' => $survey
        ], 200);
    }

    private function processImage(Request $request, $fieldName, $default = null)
    {
        if ($request->hasFile($fieldName)) {
            return $request->file($fieldName)->store('uploads', 'public');
        }

        return $request->input($fieldName) ?: $default;
    }

    private function processSections($survey, $sections, $surveyId)
    {
        foreach ($sections as $sectionData) {
            $listChoices = $sectionData['listChoices'] ?? null;
    
            if (is_array($listChoices) && isset($listChoices[0]) && is_string($listChoices[0])) {
                $listChoices = array_map(function ($choice, $index) {
                    return [
                        'label' => $choice,
                        'value' => chr(65 + $index),
                    ];
                }, $listChoices, array_keys($listChoices));
            }

            $survey->sections()->updateOrCreate(
                ['id' => $sectionData['id'] ?? null],
                [
                    'survey_id' => $surveyId,
                    'icon' => $sectionData['icon'] ?? null,
                    'label' => $sectionData['label'] ?? null,
                    'bgColor' => $sectionData['bgColor'] ?? null,
                    'bgOpacity' => $sectionData['bgOpacity'] ?? null,
                    'buttonColor' => $sectionData['buttonColor'] ?? null,
                    'buttonText' => $sectionData['buttonText'] ?? null,
                    'buttonTextColor' => $sectionData['buttonTextColor'] ?? null,
                    'contentText' => $sectionData['contentText'] ?? null,
                    'dateFormat' => $sectionData['dateFormat'] ?? null,
                    'description' => $sectionData['description'] ?? null,
                    'largeLabel' => $sectionData['largeLabel'] ?? null,
                    // 'listChoices' => json_encode($sectionData['listChoices']) ?? null,
                    'listChoices' => $listChoices ? json_encode($listChoices) : null,
                    'maxChoices' => $sectionData['maxChoices'] ?? null,
                    'midLabel' => $sectionData['midLabel'] ?? null,
                    'minChoices' => $sectionData['minChoices'] ?? null,
                    'mustBeFilled' => $sectionData['mustBeFilled'] ?? null,
                    'optionsCount' => $sectionData['optionsCount'] ?? null,
                    'otherOption' => $sectionData['otherOption'] ?? null,
                    'smallLabel' => $sectionData['smallLabel'] ?? null,
                    'textColor' => $sectionData['textColor'] ?? null,
                    'timeFormat' => $sectionData['timeFormat'] ?? null,
                    'title' => $sectionData['title'] ?? null,
                    'toggleResponseCopy' => $sectionData['toggleResponseCopy'] ?? null,
                ]
            );
        }
    }

    public function destroy($id)
    {
        $survey = Surveys::with('sections')->find($id);

        if (!$survey) {
            return response()->json([
                'success' => false,
                'message' => 'Not Found',
            ], 404);
        }

        $survey->sections()->delete();

        $survey->delete();

        return response()->json([
            'success' => true,
            'message' => 'Survey and sections deleted successfully',
        ], 200);
    }
}

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
            // 'kriteria' => 'required|array|min:1',
            'sections.*.label' => 'required|string|min:1',
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
            'status' => $request->status,
        ]);

        $this->processSections($survey, $request->sections, $survey->id);
        $this->processKriteria($survey, $request->kriteria, $survey->id);

        // $survey->kriteria()->create($request->kriteria);
        
        return response()->json([
            'success' => true,
            'message' => 'Survey successfully created',
            'data' => $survey
        ], 201);
    }

    public function show($id)
    {
        // $survey = Surveys::with('sections')->findOrFail($id);
        $survey = Surveys::with(['sections', 'kriteria'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Surveys data retrieved successfully',
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
    public function update(Request $request, $id)
    {
        $survey = Surveys::findOrFail($id);
    
        $validator = Validator::make($request->all(), [
            'surveyTitle' => 'required|string|max:255',
            'surveyDescription' => 'nullable|string|max:255',
            'sections' => 'nullable|array|min:1',
            'sections.*.label' => 'required|string',
            'kriteria' => 'required|array',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }
    
        $bgImgUrl = $this->processImage($request, 'backgroundImage', $survey->backgroundImage);
        $thumbnailUrl = $this->processImage($request, 'thumbnail', $survey->thumbnail);
    
        // Update survey
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
            'status' => $request->status,
        ]);
    
        // Handle sections
        if ($request->has('sections')) {
            $this->processSections($survey, $request->sections, $survey->id);
        }
        if ($request->has('kriteria')) {
            $this->processKriteria($survey, $request->kriteria, $survey->id);
        }

        // if ($request->has('kriteria')) {
        //     $survey->kriteria()->updateOrCreate([], $request->kriteria);
        // }
    
        return response()->json([
            'success' => true,
            'message' => 'Survey updated successfully',
            'data' => $survey->load('sections')
        ], 200);
    }
    
    private function processSections($survey, $sections, $surveyId)
    {
        $existingSectionIds = $survey->sections()->pluck('id')->toArray();
        $updatedSectionIds = [];
    
        foreach ($sections as $sectionData) {
            $sectionId = $sectionData['id'] ?? null;
    
            $listChoices = $sectionData['listChoices'] ?? null;
            if (is_array($listChoices) && isset($listChoices[0]) && is_string($listChoices[0])) {
                $listChoices = array_map(function ($choice, $index) {
                    return [
                        'label' => $choice,
                        'value' => chr(65 + $index),
                    ];
                }, $listChoices, array_keys($listChoices));
            }
    
            $section = $survey->sections()->updateOrCreate(
                ['id' => $sectionId], 
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
                    'listChoices' => $listChoices ? json_encode($listChoices) : null,
                    'maxChoices' => $sectionData['maxChoices'] ?? null,
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
    
            $updatedSectionIds[] = $section->id;
        }
    
        $sectionsToDelete = array_diff($existingSectionIds, $updatedSectionIds);
        if (!empty($sectionsToDelete)) {
            $survey->sections()->whereIn('id', $sectionsToDelete)->delete();
        }
    }

    private function processKriteria($survey, $kriteria, $surveyId)
    {
        $existingKriteriaIds = $survey->kriteria()->pluck('id')->toArray();
        $updatedKriteriaIds = [];

        foreach ($kriteria as $kriteriaData) {
            $kriteriaId = $kriteriaData['id'] ?? null;

            $kriteriaItem = $survey->kriteria()->updateOrCreate(
                ['id' => $kriteriaId],
                [
                    'survey_id' => $surveyId,
                    'gender_target' => $kriteriaData['gender_target'] ?? null,
                    'age_target' => $kriteriaData['age_target'] ?? null,
                    'lokasi' => $kriteriaData['lokasi'] ?? null,
                    'hobi' => $kriteriaData['hobi'] ?? null,
                    'pekerjaan' => $kriteriaData['pekerjaan'] ?? null,
                    'tempat_bekerja' => $kriteriaData['tempat_bekerja'] ?? null,
                    'responden_target' => $kriteriaData['responden_target'] ?? null,
                    'poin_foreach' => $kriteriaData['poin_foreach'] ?? null,
                ]
            );

            $updatedKriteriaIds[] = $kriteriaItem->id;
        }

        $kriteriaToDelete = array_diff($existingKriteriaIds, $updatedKriteriaIds);
        if (!empty($kriteriaToDelete)) {
            $survey->kriteria()->whereIn('id', $kriteriaToDelete)->delete();
        }
    }


    public function destroy($id)
    {
        $survey = Surveys::with(['sections', 'kriteria'])->find($id);

        if (!$survey) {
            return response()->json([
                'success' => false,
                'message' => 'Not Found',
            ], 404);
        }

        $survey->sections()->delete();
        $survey->kriteria()->delete();

        $survey->delete();

        return response()->json([
            'success' => true,
            'message' => 'Surveys deleted Asuccessfully',
        ], 200);
    }

}

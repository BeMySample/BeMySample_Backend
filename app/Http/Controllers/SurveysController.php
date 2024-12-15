<?php

namespace App\Http\Controllers;

use App\Models\Surveys;
use Illuminate\Http\Request;

class SurveysController extends Controller
{
    public function index()
    {
        $surveys = Surveys::with('sections.choices')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data successfully fetched',
            'data' => $surveys
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'survey_title' => 'required|string|max:255',
            'sections' => 'required|array',
            'sections.*.title' => 'required|string|max:255',
            'sections.*.content_text' => 'required|string',
        ]);

        $survey = Surveys::create($request->only([
            'survey_title',
            'active_section',
            'background_image',
            'bg_color',
            'created_by_ai',
            'respondents',
            'status'
        ]));

        foreach ($request->sections as $sectionData) {
            $section = $survey->sections()->create([
                'title' => $sectionData['title'],
                'description' => $sectionData['description'] ?? null,
                'content_text' => $sectionData['content_text'],
                'bg_color' => $sectionData['bg_color'] ?? '#FFFFFF',
                'bg_opacity' => $sectionData['bg_opacity'] ?? 1, // Default opacity 1
                'button_text' => $sectionData['button_text'] ?? null,
                'button_color' => $sectionData['button_color'] ?? null,
                'button_text_color' => $sectionData['button_text_color'] ?? null,
                'text_color' => $sectionData['text_color'] ?? null,
                'must_be_filled' => $sectionData['must_be_filled'] ?? false,
                'max_choices' => $sectionData['max_choices'] ?? null,
                'min_choices' => $sectionData['min_choices'] ?? null,
                'options_count' => $sectionData['options_count'] ?? null,
                'other_option' => $sectionData['other_option'] ?? false,
                'large_label' => $sectionData['large_label'] ?? null,
                'mid_label' => $sectionData['mid_label'] ?? null,
                'small_label' => $sectionData['small_label'] ?? null,
            ]);

            if (isset($sectionData['choices'])) {
                foreach ($sectionData['choices'] as $choiceData) {
                    $section->choices()->create([
                        'label' => $choiceData['label'],
                        'value' => $choiceData['value'],
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Survey successfully created with sections and choices',
            'data' => $survey->load('sections.choices')
        ], 201);
    }

    public function show($id)
    {
        $survey = Surveys::with('sections.choices')->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Survey data retrieved successfully',
            'data' => $survey
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $survey = Surveys::findOrFail($id);

        $survey->update($request->only([
            'survey_title',
            'active_section',
            'background_image',
            'bg_color',
            'created_by_ai',
            'respondents',
            'status'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Survey successfully updated',
            'data' => $survey->load('sections.choices')
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

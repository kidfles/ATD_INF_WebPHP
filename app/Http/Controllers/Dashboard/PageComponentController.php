<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PageComponent;
use Illuminate\Http\Request;

class PageComponentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:hero,text,featured_ads',
        ]);

        $company = $request->user()->companyProfile;

        // Define default content based on type
        $defaultContent = match($request->type) {
            'hero' => ['title' => 'New Hero Section', 'subtitle' => 'Add your subtitle here'],
            'text' => ['heading' => 'About Us', 'body' => 'Write something about your company.'],
            'featured_ads' => ['limit' => 3],
        };

        // Create the component
        $company->pageComponents()->create([
            'component_type' => $request->type,
            'content' => $defaultContent,
            'order' => $company->pageComponents()->count() + 1,
        ]);

        return back()->with('status', 'New section added! You can now edit it.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'exists:page_components,id',
        ]);

        foreach ($request->order as $index => $id) {
            PageComponent::where('id', $id)
                ->where('company_id', $request->user()->companyProfile->id)
                ->update(['order' => $index + 1]);
        }

        return response()->json(['status' => 'Order updated']);
    }

    public function update(Request $request, PageComponent $component)
    {
        // Security: Ensure the user owns this component
        if ($component->companyProfile->user_id !== $request->user()->id) {
            abort(403);
        }

        // Validate content structure based on type
        $data = $request->validate([
            'content' => 'required|array',
        ]);

        $component->update(['content' => $data['content']]);

        return back()->with('status', 'Section updated successfully.');
    }

    public function destroy(PageComponent $component)
    {
        if ($component->companyProfile->user_id !== auth()->id()) {
            abort(403);
        }

        $component->delete();

        return back()->with('status', 'Section removed.');
    }
}

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

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'ordered_ids' => 'present|array',
            'ordered_ids.*' => 'exists:page_components,id',
            'components' => 'present|array',
            'components.*.content' => 'present|array',
        ]);

        foreach ($request->ordered_ids as $index => $id) {
            $component = PageComponent::find($id);

            // Security check
            if (!$component || $component->companyProfile->user_id !== $request->user()->id) {
                continue; 
            }

            $updateData = [
                'order' => $index + 1,
            ];

            // Update content if present in the components array
            if (isset($request->components[$id]['content'])) {
                $updateData['content'] = $request->components[$id]['content'];
            }

            $component->update($updateData);
        }

        return back()->with('status', 'Page updated successfully!');
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

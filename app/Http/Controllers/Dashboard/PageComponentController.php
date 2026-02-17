<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePageComponentRequest;
use App\Http\Requests\BulkUpdatePageComponentsRequest;
use App\Models\PageComponent;
use Illuminate\Http\Request;

class PageComponentController extends Controller
{
    public function store(StorePageComponentRequest $request)
    {
        // Validation handled by Form Request

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

        return back()->with('status', __('New section added! You can now edit it.'));
    }

    public function bulkUpdate(BulkUpdatePageComponentsRequest $request)
    {
        // Validation handled by Form Request

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

        return back()->with('status', __('Page updated successfully!'));
    }

    public function destroy(PageComponent $component)
    {
        if ($component->companyProfile->user_id !== auth()->id()) {
            abort(403);
        }

        $component->delete();

        return back()->with('status', __('Section removed.'));
    }
}

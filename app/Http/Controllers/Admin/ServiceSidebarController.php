<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateServiceSidebarRequest;
use App\Models\ServiceSidebarSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceSidebarController extends Controller
{
    public function edit(): View
    {
        $setting = ServiceSidebarSetting::singleton();
        $this->authorize('update', $setting);

        return view('admin.service-sidebar.edit', [
            'setting' => $setting,
        ]);
    }

    public function update(UpdateServiceSidebarRequest $request, ServiceSidebarSetting $service_sidebar_setting): RedirectResponse
    {
        $service_sidebar_setting->fill([
            'categories_title' => $request->input('categories_title'),
            'nav_groups' => $this->buildNavGroups($request),
            'nav_links' => $this->buildNavLinks($request),
            'spare_parts_title' => $request->input('spare_parts_title'),
            'spare_parts_text' => $request->input('spare_parts_text'),
            'spare_parts_button_label' => $request->input('spare_parts_button_label'),
            'brochures_title' => $request->input('brochures_title'),
            'brochures_text' => $request->input('brochures_text'),
            'brochure_label' => $request->input('brochure_label'),
            'brochure_url' => $request->input('brochure_url'),
            'quote_title' => $request->input('quote_title'),
        ]);
        $service_sidebar_setting->save();

        return redirect()
            ->route('admin.service-sidebar.edit')
            ->with('status', 'Service sidebar updated.');
    }

    /**
     * @return list<array{id: string, label: string, children: list<array{label: string, href: string}>}>
     */
    private function buildNavGroups(Request $request): array
    {
        $ids = $request->input('nav_group_id', []);
        if (! is_array($ids)) {
            return [];
        }

        $groups = [];
        foreach (array_keys($ids) as $gi) {
            $id = trim((string) ($request->input("nav_group_id.{$gi}") ?? ''));
            $label = trim((string) ($request->input("nav_group_label.{$gi}") ?? ''));
            if ($id === '' || $label === '') {
                continue;
            }

            $childLabels = $request->input("nav_child_label.{$gi}", []);
            $childHrefs = $request->input("nav_child_href.{$gi}", []);
            $children = [];
            if (is_array($childLabels)) {
                foreach (array_keys($childLabels) as $ci) {
                    $childLabel = trim((string) ($childLabels[$ci] ?? ''));
                    if ($childLabel === '') {
                        continue;
                    }
                    $children[] = [
                        'label' => $childLabel,
                        'href' => trim((string) ($childHrefs[$ci] ?? '#')) ?: '#',
                    ];
                }
            }

            $groups[] = [
                'id' => $id,
                'label' => $label,
                'children' => $children,
            ];
        }

        return $groups;
    }

    /**
     * @return list<array{label: string, href: string}>
     */
    private function buildNavLinks(Request $request): array
    {
        $labels = $request->input('nav_link_label', []);
        if (! is_array($labels)) {
            return [];
        }

        $links = [];
        foreach (array_keys($labels) as $i) {
            $label = trim((string) ($labels[$i] ?? ''));
            if ($label === '') {
                continue;
            }
            $links[] = [
                'label' => $label,
                'href' => trim((string) ($request->input("nav_link_href.{$i}") ?? '#')) ?: '#',
            ];
        }

        return $links;
    }
}

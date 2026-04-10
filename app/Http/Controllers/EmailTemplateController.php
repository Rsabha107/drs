<?php

namespace App\Http\Controllers;

use App\Models\Gms\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    //
    public function index()
{
    $q = request('q');
    $locale = request('locale');
    $active = request('active');

    $templates = EmailTemplate::query()
        ->when($q, fn ($qq) =>
            $qq->where('key', 'like', "%{$q}%")
               ->orWhere('name', 'like', "%{$q}%")
        )
        ->when($locale, fn ($qq) => $qq->where('locale', $locale))
        ->when($active !== null && $active !== '', fn ($qq) => $qq->where('active', $active))
        ->orderBy('key')
        ->orderBy('locale')
        ->paginate(20)
        ->withQueryString();

    return view('admin.email_templates.index', compact('templates', 'q'));
}

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;

class ReportController extends Controller
{   
    public function store(Request $request)
{
    $validatedData = $request->validate([
        'reportType' => 'nullable|array', 
        'reportType.*' => 'string', 
        'customContent' => 'nullable|string',
        'postId' => 'required|exists:posts,post_id', 
        'commentId' => 'nullable|exists:comments_,comment_id', 
    ]);

    $combinedReportTypes = $request->has('reportType') && count($validatedData['reportType']) > 0
        ? implode(', ', $validatedData['reportType'])
        : null;

    Report::create([
        'report_type' => $combinedReportTypes, 
        'custom_content' => $validatedData['customContent'],
        'post_id' => $validatedData['postId'],
        'comment_id' => $validatedData['commentId'], 
        'userID' => $request->user()->id,
    ]);

    return redirect()->back()->with('success', 'Report submitted successfully.');
}
}

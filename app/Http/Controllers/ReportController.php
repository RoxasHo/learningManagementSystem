<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;

class ReportController extends Controller
{   
    public function store(Request $request)
{
    // Validate the request
    $validatedData = $request->validate([
        'reportType' => 'nullable|array', // Make reportType nullable and an array
        'reportType.*' => 'string', // Validate each report type as a string if provided
        'customContent' => 'nullable|string',
        'postId' => 'required|exists:posts,post_id', // Ensure postId exists in the posts table
    ]);

    // Check if 'reportType' exists and combine it, or set it to null
    $combinedReportTypes = $request->has('reportType') && count($validatedData['reportType']) > 0
        ? implode(', ', $validatedData['reportType'])
        : null;

    // Store the report in the database
    Report::create([
        'report_type' => $combinedReportTypes, // Store combined report types or null
        'custom_content' => $validatedData['customContent'],
        'post_id' => $validatedData['postId'],
    ]);

    return redirect()->back()->with('success', 'Report submitted successfully.');
}
}

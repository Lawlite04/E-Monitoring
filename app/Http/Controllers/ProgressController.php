<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProgressRequest;
use App\Models\Progress;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function update(UpdateProgressRequest $request, $project_id): RedirectResponse
    {
        $req = $request->validated();

        try {
            foreach ($req as $value) {
                $progress = Progress::find($value['id']);
                $progress->isFinished = $value['isFinished'];
                $progress->save();
            }
            return redirect()->route('project.detail', ['id' => $project_id]);
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}

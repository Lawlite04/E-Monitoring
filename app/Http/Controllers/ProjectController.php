<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Client;
use App\Models\Leader;
use App\Models\Progress;
use App\Models\Project;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function index(): Response
    {
        $leaders = Leader::all();
        $clients = Client::all();
        $projects = Project::with(['leader', 'client', 'progress'])->get();

        return Inertia::render('Project/index', [
            'leaders' => $leaders,
            'clients' => $clients,
            'projects' => $projects
        ]);
    }

    public function detail($id): Response
    {
        $project = Project::with(['leader', 'client', 'progress'])->find($id);

        return Inertia::render('Project/detail', [
            'project' => $project
        ]);
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $req = $request->validated();

        try {
            // init
            $leader_id = $req['leader_id'];
            $client_id = $req['client_id'];
            $project_name = $req['project_name'];
            $start_date = $req['start_date'];
            $end_date = $req['end_date'];
            $progress = $req['progress'];

            $project = Project::create([
                'leader_id' => $leader_id,
                'client_id' => $client_id,
                'project_name' => $project_name,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ]);

            foreach ($progress as $value) {
                $this->createProgress($project->id, $value);
            }

            return redirect()->route('project.home');
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    private function createProgress($project_id, $data): void
    {
        Progress::create([
            'project_id' => $project_id,
            'progress_name' => $data['progress_name'],
            'isFinished' => $data['isFinished'],
        ]);
    }

    public function update(UpdateProjectRequest $request, $id): RedirectResponse
    {
        $req = $request->validated();


        try {
            // init
            $client_id = $req["client_id"];
            $leader_id = $req["leader_id"];
            $project_name = $req["project_name"];
            $start_date = $req["start_date"];
            $end_date = $req["end_date"];
            $progress = $req['progress'];

            $project = Project::find($id);
            $project->client_id = $client_id;
            $project->leader_id = $leader_id;
            $project->project_name = $project_name;
            $project->start_date = $start_date;
            $project->end_date = $end_date;
            $project->save();

            $this->updateProgress($progress, $id);

            return redirect()->route('project.home');
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    private function updateProgress($data, $project_id): void
    {
        // data yang di hapus
        $progress_by_project_id = Progress::where('project_id', $project_id)->get();
        foreach ($progress_by_project_id as $value) {
            $data_filter = array_filter($data, function ($item) use ($value) {
                return $item['id'] == $value->id;
            });

            if (count($data_filter) == 0) {
                Progress::find($value->id)->delete();
            }
        }

        foreach ($data as $value) {
            $progress = null;
            if (!empty($value['id'])) {
                $progress = Progress::find($value['id']);
            }

            if (!empty($progress)) {
                // data yang di update
                $progress->progress_name = $value['progress_name'];
                $progress->save();
            } else {
                // data yang di tambah
                Progress::create([
                    'project_id' => $project_id,
                    'progress_name' => $value['progress_name'],
                    'isFinished' => $value['isFinished']
                ]);
            }
        }
    }

    public function destroy($id): RedirectResponse
    {
        try {
            $project = Project::find($id);
            $project->delete();

            return redirect()->route('project.home');
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}

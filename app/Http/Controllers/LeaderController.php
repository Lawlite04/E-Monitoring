<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeaderRequest;
use App\Http\Requests\UpdateLeaderRequest;
use App\Models\Leader;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LeaderController extends Controller
{
    public function index(): Response
    {
        $leaders = Leader::all();

        return Inertia::render('Leader/index', ['leaders' => $leaders]);
    }

    public function store(StoreLeaderRequest $request): RedirectResponse
    {
        $req = $request->validated();

        try {
            $leader = new Leader();

            if (!empty($req['leader_avatar'])) {
                $imageName = time() . "." . $req['leader_avatar']->extension();
                $req['leader_avatar']->move(public_path('images/avatars/leaders/'), $imageName);
                $leader->leader_avatar = $imageName;
            }

            $leader->leader_name = $req['leader_name'];
            $leader->leader_email = $req['leader_email'];
            $leader->leader_telp = $req['leader_telp'];
            $leader->save();

            return redirect()->route('leader.home');
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function update(UpdateLeaderRequest $request, $id): RedirectResponse
    {

        $req = $request->validated();
        $leader_avatar = $req['leader_avatar'];
        $leader_name = $req['leader_name'];
        $leader_email = $req['leader_email'];
        $leader_telp = $req['leader_telp'];
        $isDeleteAvatar = $req['is_delete_avatar'];

        try {
            $leader = Leader::find($id);
            $public_path = public_path('images/avatars/leaders/');

            if (!empty($leader_avatar) && !$isDeleteAvatar) {
                // hapus file yang lama
                if (!empty($leader->leader_avatar) && file_exists($public_path . $leader->leader_avatar)) {
                    unlink($public_path . $leader->leader_avatar);
                }

                // ganti file dengan yang baru
                $imageName = time() . "." . $leader_avatar->extension();
                $leader_avatar->move($public_path, $imageName);
                // simpan nama avatar baru
                $leader->leader_avatar = $imageName;
            } else if($isDeleteAvatar) {
                // hapus file yang lama
                if (!empty($leader->leader_avatar) && file_exists($public_path . $leader->leader_avatar)) {
                    unlink($public_path . $leader->leader_avatar);
                }

                $leader->leader_avatar = null;
            }

            $leader->leader_name = $leader_name;
            $leader->leader_email = $leader_email;
            $leader->leader_telp = $leader_telp;
            $leader->save();

            return redirect()->route('leader.home');
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function destroy($id): RedirectResponse
    {
        try {
            $leader = Leader::find($id);

            // hapus avatar file
            $public_path = public_path('images/avatars/leaders/');
            if (!empty($leader->leader_avatar) && file_exists($public_path . $leader->leader_avatar)) {
                unlink($public_path . $leader->leader_avatar);
            }

            $leader->delete();
            return redirect()->route('leader.home');
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}

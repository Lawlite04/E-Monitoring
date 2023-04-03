<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ClientController extends Controller
{
    public function index(): Response
    {
        $clients = Client::all();

        return Inertia::render('Client/index', ['clients' => $clients]);
    }

    public function store(StoreClientRequest $request): RedirectResponse
    {
        $req = $request->validated();

        try {
            $client = new Client();

            if (!empty($req['client_avatar'])) {
                $imageName = time() . "." . $req['client_avatar']->extension();
                $req['client_avatar']->move(public_path('images/avatars/clients/'), $imageName);
                $client->client_avatar = $imageName;
            }

            $client->client_name = $req['client_name'];
            $client->client_email = $req['client_email'];
            $client->client_address = $req['client_address'];
            $client->client_telp = $req['client_telp'];
            $client->save();

            return redirect()->route('client.home');
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function update(UpdateClientRequest $request, $id): RedirectResponse
    {

        $req = $request->validated();
        $client_avatar = $req['client_avatar'];
        $client_name = $req['client_name'];
        $client_email = $req['client_email'];
        $client_address = $req['client_address'];
        $client_telp = $req['client_telp'];
        $isDeleteAvatar = $req['is_delete_avatar'];

        try {
            $client = Client::find($id);
            $public_path = public_path('images/avatars/clients/');

            if (!empty($client_avatar) && !$isDeleteAvatar) {
                // hapus file yang lama
                if (!empty($client->client_avatar) && file_exists($public_path . $client->client_avatar)) {
                    unlink($public_path . $client->client_avatar);
                }

                // ganti file dengan yang baru
                $imageName = time() . "." . $client_avatar->extension();
                $client_avatar->move($public_path, $imageName);
                // simpan nama avatar baru
                $client->client_avatar = $imageName;
            } else if($isDeleteAvatar) {
                // hapus file yang lama
                if (!empty($client->client_avatar) && file_exists($public_path . $client->client_avatar)) {
                    unlink($public_path . $client->client_avatar);
                }

                $client->client_avatar = null;
            }

            $client->client_name = $client_name;
            $client->client_email = $client_email;
            $client->client_address = $client_address;
            $client->client_telp = $client_telp;
            $client->save();

            return redirect()->route('client.home');
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function destroy($id): RedirectResponse
    {
        try {
            $client = Client::find($id);

            // hapus avatar file
            $public_path = public_path('images/avatars/clients/');
            if (!empty($client->client_avatar) && file_exists($public_path . $client->client_avatar)) {
                unlink($public_path . $client->client_avatar);
            }

            $client->delete();
            return redirect()->route('client.home');
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}

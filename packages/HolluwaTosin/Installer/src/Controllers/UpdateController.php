<?php

namespace HolluwaTosin\Installer\Controllers;

use HolluwaTosin\Installer\Helpers\Traits\MigrationsHelper;
use HolluwaTosin\Installer\Installer;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UpdateController extends Controller
{
    use MigrationsHelper;

    /**
     * Display the updater welcome page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('installer::update.index', [
            'pendingUpdates' => $this->countPendingMigrations()
        ]);
    }

    /**
     * Migrate and seed the database.
     *
     * @return \Illuminate\View\View
     */
    public function update()
    {
        $validator = Validator::make([], []);

        if ($this->countPendingMigrations() > 0) {
            try {
                $this->migrateAndSeed();

                Installer::createLog();

            } catch (\Exception $e) {
                $validator->getMessageBag()->add(
                    'verification', $e->getMessage()
                );

                return redirect()->route('Installer::update.index')
                    ->withErrors($validator);
            }
        }

        return redirect()->route('Installer::update.finish');
    }

    /**
     * Update installed file and display finished view.
     *
     * @return \Illuminate\View\View
     */
    public function finish()
    {
        if ($this->countPendingMigrations() <= 0) {
            return view('installer::update.finished');
        }

        return redirect()->route('Installer::update.index');
    }
}

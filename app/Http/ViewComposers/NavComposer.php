<?php

namespace App\Http\ViewComposers;

use App\Models\Module;
use Auth;
use Illuminate\Contracts\View\View;

class NavComposer {

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        if (Auth::check()) {
            /**
             * Select the active modules to the navigation bar.
             */
            if (Auth::user()->hasRole('Admin'))
                $modules = Module::with('submodules')
                                 ->where('status', '=', 'Ativo')
                                 ->orderBy('position', 'asc')
                                 ->get();
            else
                $modules = Module::with('submodules')
                    ->where('status', '=', 'Ativo')
                    ->orderBy('position', 'asc')
                    ->get();

            $view->with('nav', $modules);


            /**
             * Select the active modules for config.
             */
            $modules = null;
            if (!Auth::user()->hasRole('Usuário')) {
                $modules = Module::where('status', '=', 'Ativo')
                                 ->orderBy('position', 'asc')
                                 ->get();
            }

            $view->with('navConfig', $modules);
        }
    }

}

<?php
namespace App\Http\Controllers\Config;
use App\Models\Unit\Department;
use App\Http\Controllers\Controller;
use App\Models\Unit\Portal;
use App\Models\Unit\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Image;
class ProfilesController extends Controller
{
    protected $rules = [
        'name' => 'required',
    ];
    /**
     * Show the user profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $user = Auth::user();
        return view('backend.profiles.index', compact('user'));
    }
    /**
     * Show the form for editing the user profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit() {
        return view('backend.profiles.edit');
    }
    /**
     * Update the user profile in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {
        $this->validate($request, $this->rules);
        //data to save
        $data['name'] = $request->name;
        $data['email'] = $request->email;

        //save in storage
        $user = Auth::user();
        //upload file
        if ($request->hasFile('photo')) {
            //filename
            $filename = str_slug($request->name);
            $filename .= '-' . uniqid() . '.';
            $filename .= $request->file('photo')->getClientOriginalExtension();
            //destination folder
            $path = public_path() . "/img/profile/";
            //crop the image
            $width = intval($request->width);
            $height = intval($request->height);
            $x = intval($request->x);
            $y = intval($request->y);
            $img = Image::make($request->file('photo'));
            $img->crop($width, $height, $x, $y);
            $img->resize(200, 200);
            $img->save($path.$filename, 80);
            //data to save
            $data['photo'] = $filename;
            File::delete(public_path() . '/img/profile/' . $user->photo); //delete old photo
        }


        $user->fill($data)->save();
        $request->session()->flash('success', 'Perfil alterado com sucesso.');
        return redirect()->route('profile.edit');
    }
    /**
     * Show the form for editing the user password.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editPassword() {
        return view('backend.profiles.edit-password');
    }
    /**
     * Update the user password in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request) {
        //check if the old_password is compatible with the current password
        if (!Hash::check($request->old_password, Auth::user()->password)) {
            $request->session()->flash('error', 'A senha antiga está incorreta!');
            return redirect()->back();
        }
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);
        $user = Auth::user();
        $user->password = bcrypt($request->password);
        $user->save();
        $request->session()->flash('success', 'Senha alterada com sucesso.');
        return redirect()->back();
    }
}

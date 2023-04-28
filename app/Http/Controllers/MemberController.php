<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Models\Member;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    /**
     * Returns the members of the conference.
     * @param Request $request
     * @return Collection
     */
    public function index(Request $request): Collection
    {
        $showHidden = $request->query('showHidden');
        $columns = json_decode(str_replace("'", '"', $request->query('fields')));
        if(empty($columns)){
            $columns = ['*'];
        }
        $members = [];
        if($showHidden == null || $showHidden == 'false'){
            $members = Member::where('hidden', 'false')->get($columns);
        }
        else{
            $members = Member::all($columns);
        }
        if(in_array('photo', $columns) || $columns == ['*']){
            $members = $members->map(function($member) {
                if($member->photo){
                    $photoUrl = App::make('url')->to(Storage::url('public/photos/'.$member->photo));
                }else{
                    $photoUrl = 'https://cdn-icons-png.flaticon.com/512/847/847969.png?w=826&t=st=1682674212~exp=1682674812~hmac=3d05333ae74b2270ae2f3f88a63a36b000915801d18058d245ffc55e99c5b94a';
                }
                $member->photo = $photoUrl;
                return $member;
            });
        }
        return $members;
    }

    /**
     * Returns the count of the visible members of the conference.
     * @return array
     */
    public function count():array
    {
        return ['count' => Member::where('hidden', false)->count()];
    }

    /**
     * Returns the member of the conference.
     * @param Request $request
     * @param string $id
     * @return Member
     */
    public function show(Request $request, string $id): Member
    {
        $columns = json_decode(str_replace("'", '"', $request->query('fields')));
        if(empty($columns)){
            $columns = ['*'];
        }
        $member = Member::findOrFail($id, $columns);
        if(in_array('photo', $columns) || $columns == ['*']){
            if($member->photo){
                $photoUrl = App::make('url')->to(Storage::url('public/photos/'.$member->photo));
            }else{
                $photoUrl = 'https://cdn-icons-png.flaticon.com/512/847/847969.png?w=826&t=st=1682674212~exp=1682674812~hmac=3d05333ae74b2270ae2f3f88a63a36b000915801d18058d245ffc55e99c5b94a';
            }
            $member->photo = $photoUrl;
        }
        return $member;
    }

    /**
     * Registers the member of the conference.
     * @param RegisterMemberRequest $request
     * @return array
     */
    public function register(RegisterMemberRequest $request): array
    {
        $member = Member::create($request->validated());
        return ['id'=>$member->id, 'success' => true];
    }

    /**
     * Updates the members of the conference.
     * @param UpdateMemberRequest $request
     * @param string $id
     * @return array
     */
    public function update(UpdateMemberRequest $request, string $id): array
    {
        $data = $request->validated();
        if($request->hasFile('photo')){
            $file = $request->file('photo');
            $filename = uniqid().'.'.$file->getClientOriginalExtension();
            $file->storeAs('public/photos/', $filename);
            $data['photo'] = $filename;
        }
        Member::where('id', $id)->update($data);
        return ['success' => true];
    }
    /**
     * Deletes the members of the conference.
     * @param string $id
     * @return array
     */
    public function delete(string $id): array
    {
        $member = Member::findOrFail($id);
        $member->delete();
        return ['success' => true];
    }
}

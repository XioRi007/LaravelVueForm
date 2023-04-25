<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Models\Member;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Returns the members of the conference.
     */
    public function index(): Collection
    {
        return Member::all();
    }

    /**
     * Returns the members of the conference.
     */
    public function show(Request $request, string $id): Member
    {
        $columns = json_decode(str_replace("'", '"', $request->query('fields')));
        return Member::findOrFail($id, $columns);
    }

    /**
     * Returns the members of the conference.
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
        Member::where('id', $id)->update($request->validated());
        return ['success' => true];
    }
}

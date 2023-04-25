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
     * @return Collection
     */
    public function index(): Collection
    {
        return Member::all();
    }

    /**
     * Returns the count of the members of the conference.
     * @return array
     */
    public function count():array
    {
        return ['count' => Member::count()];
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
        return Member::findOrFail($id, $columns);
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
        Member::where('id', $id)->update($request->validated());
        return ['success' => true];
    }
}
